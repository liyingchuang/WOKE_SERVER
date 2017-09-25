<?php

namespace App\Http\Controllers\client\Response\V1;

use App\Cart;
use App\Http\Controllers\client\Response\InterfaceResponse;
use App\OrderAction;
use App\OrderGoods;
use App\OrderInfo;
use App\User;
use App\Goods;
use App\GroupGoods;
use Illuminate\Http\Request;
use App\OrderTotal;

class OrderController extends AccountController implements InterfaceResponse
{
    public function __construct()
    {
        $this->except = ['index'];
    }

    /**
     * 修改订单状态
     * @param Request $request
     */
    public function update(Request $request)
    {
        $order_id = $request->get('order_id', null); //pay_name 必须填写
        $user_id = $request->get('user_id', null); //用户id 必须填写
        $opt = $request->get('opt', null); //用户id 必须填写
        if ($opt == 'cancel') {
            OrderInfo::where('order_id', $order_id)->where('user_id', $user_id)->update(['order_status' => 2]);
        }
        if ($opt == 'agree') {
            OrderInfo::where('order_id', $order_id)->where('user_id', $user_id)->update(['order_status' => 5, 'shipping_status' => 2, 'pay_status' => 2]);
        }
        if ($opt == 'remove') {
            OrderInfo::where('order_id', $order_id)->where('user_id', $user_id)->update(['is_delete' => 1]);
        }
        return $this->success(null, '操作成功');
    }

    /**
     * 待支付状态从新支付
     * @param Request $request
     */
    public function orderPay(Request $request)
    {
        $pay_name = $request->get('pay_name', null); //pay_name 必须填写
        $user_id = $request->get('user_id', null); //用户id 必须填写
        $order_sn = $request->get('order_sn', null); //pay_name 必须填写
        $openid = $request->get('openid',''); // 使用酒币数量
        $client_ip = $request->getClientIp();
      ///  $user = User::select('user_money')->where('user_id', $user_id)->first();
        $order = OrderInfo::where('pay_status', '<>', 2)->where('order_sn', $order_sn)->where('user_id', $user_id)->first();
        $goods = OrderGoods::where('order_id', $order->order_id)->get();
        foreach($goods as $val){
            $group = GroupGoods::where('goods_id', $val->goods_id)->where('start_time','>',time())->where('end_time','<',time())->where('examine_status', 2)->first();
            $type = Goods::select('goods_id')->where('goods_id', $val->goods_id)->where('is_delete', 1)->where('is_on_sale', 0)->first();
            if(!empty($type) || !empty($group)){
               return $this->error(0,'商品已下架或已过期');
            }
        }
        if (!empty($order)) {
            if ($pay_name == 'woke') {
               // $this->_http('http://woke.377123.com/webhook', json_encode(['type' => 'charge.succeeded', 'data' => ['object' => ['channel' => 'woke', 'order_no' => $order_sn]]]));
              //  $result = array('charge' => ['order_no' => $order_sn]);
            } else {
               // $order->order_amount=$order->order_amount+$order->integral_money;
                $result = $this->pay($order_sn, $pay_name, $order->order_amount, $client_ip,$openid);
                $order->integral_money=0.00;
                $order->save();
            }
            return $this->success($result, 'ok');
        } else {
            return $this->error($order, '订单已支付或不存在！');
        }
    }

    /**
     * 订单状态
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        $user_id = $request->get('user_id', null); //用户id 必须填写
        $order_sn = $request->get('order_sn', null); //pay_name 必须填写
        $a=substr($order_sn, 0, 1 );
        if($a=='A'){
            $order = OrderTotal::select('pay_status')->whereOrder_total_sn($order_sn)->first();
        }else{
            $order = OrderInfo::select('order_sn', 'pay_status', 'order_status', 'pay_name')->where('order_sn', $order_sn)->where('user_id', $user_id)->first();
        }
        return $this->success($order, 'ok');
    }

    /**
     * 生成订单
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pay_name = $request->get('pay_name', null); //pay_name 必须填写 酒币支付 woke
        $invoice = $request->get('invoice', ''); //invoice 发票抬头
        $vat_inv_taxpayer_id = $request->get('taxpayerid', ''); //invoice 发票抬头
        $user_id = $request->get('user_id', null); //用户id 必须填写
        $node = $request->get('node', '无'); //备注
        $openid = $request->get('openid'); // 使用酒币数量
        $integral = $request->get('integral', 0.00); // 使用酒币数量
        $client_ip = $request->getClientIp();
        $data = parent::index($request);
        //11.如果全用就币支付就检查酒币数量够不够
        $user = User::select('user_money')->where('user_id', $user_id)->first();
        if ($data['status'] == 1) {
            $order_sn = date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
            $goods_amount = $data['data']['total_goods_price'];
            $order_amount = $data['data']['total_order_amount'];
            $bonus= $data['data']['total_price'];
            $userintegral=$data['data']['total_integral'];
            $cate_gray = $data['data']['cate_gray'];
            $shipping_fee=$data['data']['shipping_fee'];
            if ($order_amount < $user->user_money && $integral >= $order_amount) {
                $pay_name == 'woke';
            }
            if ($pay_name == 'woke' && $order_amount > $user->user_money) {
                return $this->error(null, '酒币不足！');
            }
            if ($pay_name == 'woke' && $order_amount < $user->user_money) {
                $integral = $order_amount;
            }
            if ($pay_name != 'woke' && $integral > 0 && $integral > $user->user_money) {
                return $this->error(null, '酒币不足！');
            }
            if ($goods_amount == 0.00) {
                return $this->error(null, '订单金额不正确');
            }
            $discount = $data['data']['total_market_price'] - $data['data']['total_goods_price'];
            // todo 1.设置 integral 的值购物返酒币 2.OrderGoods表添加相关字段
            if( $cate_gray >= 200 ){
                $pack_fee =  40;
            }else{
                $pack_fee =  0;
            }
            $order = OrderInfo::create(['order_sn' => $order_sn, 'user_id' => $user_id, 'order_status' => 1, 'consignee' => $data['data']['address']->consignee, 'province' => $data['data']['address']->province, 'city' => $data['data']['address']->city, 'district' => $data['data']['address']->district, 'address' => $data['data']['address']->address,
                'mobile' => $data['data']['address']->mobile, 'tel' => $data['data']['address']->mobile,'shipping_fee'=>$shipping_fee, 'pack_fee'=>$pack_fee, 'froms' => 'app','bonus'=>$bonus,'integral'=>$userintegral, 'integral_money' => $integral, 'postscript' => $node, 'goods_amount' => $goods_amount, 'order_amount' => $order_amount, 'discount' => $discount, 'add_time' => time(), 'vat_inv_company_name' => $invoice,'vat_inv_taxpayer_id'=>$vat_inv_taxpayer_id, 'inv_money' => $order_amount]);
            foreach ($data['data']['list'] as $k => $v) {
                OrderGoods::create(['order_id' => $order->order_id, 'goods_id' => $v['goods_id'], 'goods_name' => $v['goods_name'], 'goods_sn' => $v['goods_sn'], 'goods_number' => $v['goods_number'], 'goods_price' => $v['shop_price'], 'market_price' => $v['market_price'], 'goods_attr' => $v['goods_attr'], 'goods_attr_id' => $v['goods_attr_id']]);
                Cart::where('rec_id', $v['rec_id'])->where('user_id', $user_id)->delete();
            }

            OrderAction::create(['order_id' => $order->order_id, 'action_note' => '下单', 'pay_status' => 0, 'action_user' => '客户', 'order_status' => 1, 'log_time' => time()]);
            if ($pay_name == 'woke') {
               $x=$this->_http(env('PINGXX'), json_encode(['type' => 'charge.succeeded', 'data' => ['object' => ['id' => 'woke', 'channel' => 'woke', 'order_no' => $order_sn]]]));
               $result = array('charge' => ['order_no' => $order_sn,'dd'=>$x]);
            } else {
                if ($pay_name != 'woke' && $integral > 0 && $integral <= $user->user_money) {
                    $order_amount = $order_amount - $integral;
                    $order_amount=$this->getFormatPrice($order_amount);
                }
                $result = $this->pay($order_sn, $pay_name, $order_amount, $client_ip,$openid);
            }
            return $this->success($result, '订单信息');
        } else {
            return $this->error($data['data'], $data['msg'], $data['code']);
        }
    }

    private function pay($order_sn, $pay_name, $order_amount, $client_ip,$openid='')
    {
        $debut = env('APP_DEBUG');
        if($debut){
            $path= storage_path('pingxx/develop.pem');
        }else{
            $path= storage_path('pingxx/release.pem');
        }
        \Pingpp\Pingpp::setApiKey(env('PINGXX_API_KEY'));
        \Pingpp\Pingpp::setPrivateKeyPath($path);
        $subject=$order_sn . '订单';
        $extra = array();
        switch ($pay_name) {
            case 'alipay_wap':
                $extra = array(
                    // success_url 和 cancel_url 在本地测试不要写 localhost ，请写 127.0.0.1。URL 后面不要加自定义参数
                    'success_url' => 'http://woke.jiugubao.com/finish.html',
                    'cancel_url' => 'http://woke.jiugubao.com/finish.html'
                );
                break;
            case 'wx_pub':
                $extra = array(
                    'open_id' =>$openid// 用户在商户微信公众号下的唯一标识，获取方式可参考 pingpp-php/lib/WxpubOAuth.php
                );
                break;
        }
        $ch = \Pingpp\Charge::create(
            array(
                'order_no' => $order_sn,
                'app' => array('id' =>env('PINGXX_API_ID')),
                'channel' => $pay_name,
                'amount' => $order_amount * 100,
                'client_ip' => $client_ip,
                'currency' => 'cny',
                'subject' =>$subject,
                'body' => 'Your Body',
                'extra'     => $extra,
            )
        );
        $result = array('charge' => json_decode($ch, true));
        return $result;
    }

    /**
     * 返回接口名称
     * @return string
     */
    public function getMethod()
    {

    }
}
