<?php

namespace App\Http\Controllers\client\Response\V2;

use App\Cart;
use App\Http\Controllers\client\Response\InterfaceResponse;
use App\OrderAction;
use App\OrderGoods;
use App\OrderInfo;
use App\OrderTotal;
use App\User;
use Illuminate\Http\Request;

class OrderController extends AccountController implements InterfaceResponse
{

    public function __construct()
    {
        $this->except = ['index'];
    }

    /**
     * 生成订单
     * @return array
     */
    public function index(Request $request)
    {
        $pay_name = $request->get('pay_name', null); //pay_name 必须填写 酒币支付 woke
        $invoice = $request->get('invoice', ''); //invoice 发票抬头
        $vat_inv_taxpayer_id = $request->get('taxpayerid', ''); //invoice 增值税发票纳税人识别号
        $user_id = $request->get('user_id', null); //用户id 必须填写
        $node = $request->get('node', '无'); //备注
        $openid = $request->get('openid'); //
        $integral = $request->get('integral', 0.00); // 使用酒币数量
        $client_ip = $request->getClientIp();
        $list = parent::index($request);
        //todo 检查酒币数量
        $user = User::select('user_money')->where('user_id', $user_id)->first();
        if ($list['status'] == 1) {
            $order_amount = $list['data']['total_all_price']; //总金额
            $goods_amount = $list['data']['total_market_price']; //商品金额
            $bonus = $list['data']['total_price']; //返成
            $userintegral = $list['data']['total_integral'];
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
            //优惠折扣
            $discount = $list['data']['total_market_price'] - $list['data']['total_goods_price'];
            foreach ($list['data']['store'] as $vv) {
                $vv['order_sn'] = date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
                if($vv['supplier_id'] == 0){
                    $order = OrderInfo::create(['order_sn' => $vv['order_sn'], 'user_id' => $user_id, 'order_status' => 1, 'consignee' => $list['data']['address']->consignee, 'province' => $list['data']['address']->province, 'city' => $list['data']['address']->city, 'district' => $list['data']['address']->district, 'address' => $list['data']['address']->address,
                        'mobile' => $list['data']['address']->mobile, 'tel' => $list['data']['address']->mobile, 'shipping_fee' => $vv['total_shipp_price'], 'froms' => 'app', 'bonus' => $vv['total_market_price'], 'integral' => 0, 'integral_money' => $integral, 'postscript' => $node, 'goods_amount' => $vv['total_market_price'], 'order_amount' => $vv['total_goods_price'] + $vv['total_shipp_price'], 'discount' => $discount, 'supplier_id' => $vv['supplier_id'], 'add_time' => time(), 'vat_inv_company_name' => $invoice, 'vat_inv_taxpayer_id' => $vat_inv_taxpayer_id, 'inv_money' => $order_amount]);
                }else{
                    $order = OrderInfo::create(['order_sn' => $vv['order_sn'], 'user_id' => $user_id, 'order_status' => 1, 'consignee' => $list['data']['address']->consignee, 'province' => $list['data']['address']->province, 'city' => $list['data']['address']->city, 'district' => $list['data']['address']->district, 'address' => $list['data']['address']->address,
                        'mobile' => $list['data']['address']->mobile, 'tel' => $list['data']['address']->mobile, 'shipping_fee' => $vv['total_shipp_price'], 'froms' => 'app', 'bonus' => $vv['total_market_price'], 'integral' => 0, 'postscript' => $node, 'goods_amount' => $vv['total_market_price'], 'order_amount' => $vv['total_goods_price'] + $vv['total_shipp_price'], 'discount' => $discount, 'supplier_id' => $vv['supplier_id'], 'add_time' => time(), 'vat_inv_company_name' => $invoice, 'vat_inv_taxpayer_id' => $vat_inv_taxpayer_id, 'inv_money' => $order_amount]);
                }
                foreach ($vv['list'] as $k => $v) {
                    OrderGoods::create(['order_id' => $order->order_id, 'goods_id' => $v['goods_id'], 'goods_name' => $v['goods_name'], 'goods_sn' => $v['goods_sn'], 'goods_number' => $v['goods_number'], 'goods_price' => $v['goods_price'], 'market_price' => $v['market_price'], 'goods_attr' => $v['goods_attr'], 'goods_attr_id' => $v['goods_attr_id']]);
                    //Cart::where('rec_id', $v['rec_id'])->where('user_id', $user_id)->delete();
                    OrderAction::create(['order_id' => $order->order_id, 'action_note' => '下单', 'pay_status' => 0, 'action_user' => '客户', 'order_status' => 1, 'log_time' => time()]);
                    $orders[] = $order->order_id;
                }
            }
            $order_id = implode(',', $orders);
            $order_sn = "A" . date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
            OrderTotal::create(['order_id' => $order_id, 'user_id' => $user_id, 'order_total_sn' => $order_sn, 'order_total_price' => $order_amount, 'bonus' => $bonus, 'integral' => $userintegral, 'integral_money' => $integral, 'add_time' => time()]);
            if ($pay_name == 'woke') {
                $x = $this->_http(env('PINGXX'), json_encode(['type' => 'charge.succeeded', 'data' => ['object' => ['id' => 'woke', 'channel' => 'woke', 'order_no' => $order_sn]]]));
                $result = array('charge' => ['order_no' => $order_sn, 'dd' => $x]);
            } else {
                if ($pay_name != 'woke' && $integral > 0 && $integral <= $user->user_money) {
                    $order_amount = $order_amount - $integral;
                    $order_amount = $this->getFormatPrice($order_amount);
                }
                $result = $this->pay($order_sn, $pay_name, $order_amount, $client_ip, $openid);
            }
            return $this->success($result, '订单信息');
        } else {
            return $this->error($list['data'], $list['msg'], $list['code']);
        }
    }


    private function pay($order_sn, $pay_name, $order_amount, $client_ip, $openid = '')
    {
        $debut = env('APP_DEBUG');
        if ($debut) {
            $path = storage_path('pingxx/develop.pem');
        } else {
            $path = storage_path('pingxx/release.pem');
        }
        \Pingpp\Pingpp::setApiKey(env('PINGXX_API_KEY'));
        \Pingpp\Pingpp::setPrivateKeyPath($path);
        $subject = $order_sn . '订单';
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
                    'open_id' => $openid// 用户在商户微信公众号下的唯一标识，获取方式可参考 pingpp-php/lib/WxpubOAuth.php
                );
                break;
        }
        $ch = \Pingpp\Charge::create(
            array(
                'order_no' => $order_sn,
                'app' => array('id' => env('PINGXX_API_ID')),
                'channel' => $pay_name,
                'amount' => $order_amount * 100,
                'client_ip' => $client_ip,
                'currency' => 'cny',
                'subject' => $subject,
                'body' => 'Your Body',
                'extra' => $extra,
            )
        );
        $result = array('charge' => json_decode($ch, true));
        return $result;
    }

}