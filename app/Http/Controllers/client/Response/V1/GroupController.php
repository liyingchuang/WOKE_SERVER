<?php

namespace App\Http\Controllers\client\Response\V1;

use App\CollectGoods;
use App\Goods;
use App\GroupInfo;
use App\Http\Controllers\client\Response\BaseResponse;
use App\Http\Controllers\client\Response\InterfaceResponse;
use App\OrderGoods;
use App\OrderInfo;
use Illuminate\Http\Request;

use App\GroupGoods;
use App\GroupOpen;
use Illuminate\Support\Facades\DB;

class GroupController extends BaseResponse implements InterfaceResponse
{


    public function __construct()
    {
        $this->except = ['item', 'index', 'hot', 'android','search'];
    }

    /**
     * @param Request $request
     */
    public function search(Request $request)
    {
        $keyword=$request->get('keyword');
        $time = time();
        $goods = GroupGoods::with('goods')->whereHas('goods', function ($query ) use ($keyword) {
            $query->where('is_on_sale', 1)->where('is_delete', 0)->where('goods_name', 'like', "%$keyword%");
        })->where('start_time', '<=', $time)->where('end_time', '>=', $time)->where('examine_status', 4)->orderBy('created_at', 'desc')->paginate(10)->toArray()['data'];;
        foreach ($goods as $K => $v) {
            $goods[$K]['order_size'] = strval(GroupInfo::where('goods_id', $v['goods_id'])->where('pay_status', 2)->sum('buy_number'));
        }
        return $this->success($goods, '');
    }
    public function item(Request $request)
    {
        $id = $request->get('id');
        $time = time();
        $goods = GroupGoods::with('goods')->whereHas('goods', function ($query) {
            $query->where('is_on_sale', 1)->where('is_delete', 0);
        })->where('start_time', '<=', $time)->where('end_time', '>=', $time)->where('examine_status', 4)->where('ify_id', $id)->orderBy('created_at', 'desc')->paginate(10)->toArray()['data'];
        foreach ($goods as $K => $v) {
            $goods[$K]['order_size']= strval(GroupInfo::where('goods_id', $v['goods_id'])->where('pay_status', 2)->sum('buy_number'));
        }
        return $this->success($goods, '');
    }

    public function hot(Request $request)
    {
        $time = time();
        $goods = GroupGoods::with('goods')->whereHas('goods', function ($query) {
            $query->where('is_on_sale', 1)->where('is_delete', 0);
        })->where('recommend', 1)->where('start_time', '<=', $time)->where('end_time', '>=', $time)->where('examine_status', 4)->orderBy('created_at', 'desc')->get();
        foreach ($goods as $K => $v) {
            $goods[$K]->order_size = strval(GroupInfo::where('goods_id', $v->goods_id)->where('pay_status', 2)->sum('buy_number'));
        }
        return $this->success($goods, '');
    }

    /**
     * 参团商品详情
     *
     * @return \Illuminate\Http\Response
     */
    public function android(Request $request)
    {
        $time = time();
        $user_id = $request->get('user_id');
        $goods_id = $request->get('goods_id');
        $group_id = $request->get('group_id');
        $goods = GroupGoods::with('goods', 'goods.attr', 'goods.item', 'goods.gallery', 'group.user','store')->whereHas('goods', function ($query) {
            $query->where('is_on_sale', 1)->where('is_delete', 0);
        })->where('goods_id', $goods_id)->where('start_time', '<=', $time)->where('end_time', '>=', $time)->where('examine_status', 4)->first();
        if (empty($goods)) {
            return $this->error(null, '团购商品已卖完!或已经下架');
        }
        if ($group_id) {
            $groupopne = GroupOpen::where('group_id', $group_id)->where('goods_id', $goods_id)->first();
            if (empty($groupopne) || $groupopne->time < 0) {
                return $this->error(null, '此团购超过24小时没成团自动关闭！');
            } else {
                $goods->time = strval($groupopne->time);
            }
            $goods->group_list = GroupInfo::with('user')->where('goods_id', $goods_id)->where('group_id', $group_id)->where('pay_status', 2)->get();
        }
        /**
         * if (!empty($goods->goods->attr)) {
         * $list = [];
         * foreach ($goods->goods->attr as $k => $v) {
         * $list["$v->attr_name"][] = $v;
         * }
         * unset($goods->goods->attr);
         * if (empty($list)) {
         * $goods->goods->attr = (object)null;
         * } else {
         * $goods->goods->attr = $list;
         * }
         * } else {
         * $goods->goods->attr = (object)null;
         * }
         */
        if (!empty($user_id)) {
            $collectGoods = CollectGoods::where('user_id', $user_id)->where('goods_id', $goods_id)->first();
            if (!empty($collectGoods) && $collectGoods->is_attention == 1) {
                $goods->collect = 1;
            } else {
                $goods->collect = 0;
            }
        } else {
            $goods->collect = 0;
        }
        if (!empty($group_id)) {
            $group = GroupOpen::where('group_id', $group_id)->where('goods_id', $goods_id)->first();
            if (!empty($group)) {
                $goods->is_group = 1;
            } else {
                $goods->is_group = 0;
            }
        } else {
            $goods->is_group = 0;
        }
        $goods->tel = "4000191818";
        $goods->order_size = strval(GroupInfo::where('goods_id', $goods_id)->where('pay_status', 2)->sum('buy_number') ?: 0);
        return $this->success($goods, '');
    }

    /**
     * 参团商品详情
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $time = time();
        $user_id = $request->get('user_id');
        $goods_id = $request->get('goods_id');
        $group_id = $request->get('group_id');
        $goods = GroupGoods::with('goods', 'goods.attr', 'goods.item', 'goods.gallery', 'group.user','store')->whereHas('goods', function ($query) {
            $query->where('is_on_sale', 1)->where('is_delete', 0);
        })->where('goods_id', $goods_id)->where('start_time', '<=', $time)->where('end_time', '>=', $time)->where('examine_status', 4)->first();
        if (empty($goods)) {
            return $this->error(null, '团购商品已卖完!或已经下架');
        }
        if ($group_id) {
            $groupopne = GroupOpen::where('group_id', $group_id)->where('goods_id', $goods_id)->first();
            if (empty($groupopne) || $groupopne->time < 0) {
                return $this->error(null, '此团购超过24小时没成团自动关闭！');
            } else {
                $goods->time = strval($groupopne->time);
            }
            $goods->group_list = GroupInfo::with('user')->where('goods_id', $goods_id)->where('group_id', $group_id)->where('pay_status', 2)->get();
        }
        if (!empty($goods->goods->attr)) {
            $list = [];
            foreach ($goods->goods->attr as $k => $v) {
                $list["$v->attr_name"][] = $v;
            }
            unset($goods->goods->attr);
            if (empty($list)) {
                $goods->goods->attr = (object)null;
            } else {
                $goods->goods->attr = $list;
            }
        } else {
            $goods->goods->attr = (object)null;
        }

        if (!empty($user_id)) {
            $collectGoods = CollectGoods::where('user_id', $user_id)->where('goods_id', $goods_id)->first();
            if (!empty($collectGoods) && $collectGoods->is_attention == 1) {
                $goods->collect = 1;
            } else {
                $goods->collect = 0;
            }
        } else {
            $goods->collect = 0;
        }
        if (!empty($group_id)) {
            $group = GroupOpen::where('group_id', $group_id)->where('goods_id', $goods_id)->first();
            if (!empty($group)) {
                $goods->is_group = 1;
            } else {
                $goods->is_group = 0;
            }
        } else {
            $goods->is_group = 0;
        }
        $goods->tel = "4000191818";

        $goods->order_size = strval(GroupInfo::where('goods_id', $goods_id)->where('pay_status', 2)->sum('buy_number') ?: 0);

        return $this->success($goods, '');
    }

    /**
     * 结算首页
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function home(Request $request)
    {
        $user_id = $request->get('user_id', null); //用户id 必须填写
        $address_id = $request->get('address_id', null); //用户地址id
        $goods_id = $request->get('goods_id');
        $goods_number = $request->get('number');
        $goods_attr_id = $request->get('goods_attr_id');
        $group_id = $request->get('group_id');
        $time = time();
        if ($group_id) {
            $groupopne = GroupOpen::where('group_id', $group_id)->where('goods_id', $goods_id)->first();
            if (empty($groupopne) || $groupopne->time < 0) {
                return $this->error(null, '此团购超过24小时没成团自动关闭！');
            }
            $grouporder = GroupInfo::where('user_id', $user_id)->where('group_id', $group_id)->where('goods_id', $goods_id)->where('pay_status', 2)->first();
            if ($grouporder) {
                return $this->error(null, '你已经参与了此次团购不能重复参与！');
            }
        }
        //1.查询地址
        if ($address_id) {
            $address = DB::table('woke_user_address')->where('user_id', $user_id)->where('address_id', $address_id)->first();
        } else {
            $address = DB::table('woke_user_address')->where('user_id', $user_id)->where('is_default', 1)->first();
        }
        if (empty($address)) {
            return $this->success(['is_address' => 0], '请选择收货地址');
        }
        $info = GroupGoods::with('goods', 'store')->whereHas('goods', function ($query) {
            $query->where('is_on_sale', 1)->where('is_delete', 0);
        })->where('goods_id', $goods_id)->where('start_time', '<=', $time)->where('end_time', '>=', $time)->where('examine_status', 4)->first();
        if (empty($info)) {
            return $this->error(null, '团购商品已卖完!或已经下架');
        } else {
            //团长免单 团长限购1件
            if($info->head_free&&empty($group_id)){
                $goods_number=1;
            }
            if (empty($goods_attr_id) && $goods_number > $info->goods->goods_number) {
                return $this->error($info, '[' . $info->goods->goods_name . ']已下架或库存不够');
            } else {
                $attr = DB::table('woke_goods_attr')->where('id', $goods_attr_id)->first();
                if (!empty($attr) && $goods_number > $attr->goods_number) {
                    return $this->error($info, '[' . $info->goods->goods_name . ']已下架或库存不够');
                }
                if (!empty($attr)) {
                    $info->goods->attr = $attr;
                    $info->price = $attr->group_price;
                } else {
                    $info->price = $info->group_price;
                }
            }
        }
        $result = $info;
        if($info->head_free&&empty($group_id)){
            $result['order_amount'] = 0.00;
        }else{
            $result['order_amount'] = $this->getFormatPrice($goods_number * $info->price);
        }

        $result['order_number'] = $goods_number;
        $result['address'] = $address;
        $result['is_address'] = 1;
        return $this->success($result, '');
    }

    /**
     * @param Request $request
     */
    public function pay(Request $request)
    {
        $pay_name = $request->get('pay_name', null); //pay_name
        $user_id = $request->get('user_id', null); //用户id 必须填写
        $goods_id = $request->get('goods_id');
        $openid = $request->get('openid');
        $client_ip = $request->getClientIp();
        $group_id = $request->get('group_id', 0);
        $goods_number = $request->get('number');
        $goods_attr_id = $request->get('goods_attr_id');
        $data = $this->home($request);
        if ($data['status'] == 1) {
            $order_sn = 'T' . date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
            if ($group_id) {
                $group = GroupOpen::where('group_id', $group_id)->where('goods_id', $goods_id)->first();
            } else {
                $group = GroupOpen::create(['goods_id' => $goods_id, 'user_id' => $user_id, 'supplier_id' => $data['data']['supplier_id'], 'start_time' => time(), 'have' => 0, 'group_status' => 0]);
            }
            if ($goods_attr_id) {
                $attr = DB::table('woke_goods_attr')->select('attr_name', 'attr_value')->where('id', $goods_attr_id)->first();
                $goods_attr_name = $attr->attr_name . '[' . $attr->attr_value . ']';
            } else {
                $goods_attr_name = '';
            }
            if($data['data']['order_amount']==0.00&&$group->user_id==$user_id){//此时为团长免单用户 'order_status' => 1
                $order_sn = 'TF' . date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
                //生成扩展订单
                GroupInfo::create(['goods_id' => $goods_id, 'user_id' => $user_id, 'group_id' => $group->group_id, 'order_sn' => $order_sn, 'pay_status' =>2, 'supplier_id' => $data['data']['supplier_id'], 'consignee' => $data['data']['address']->consignee, 'address' => $data['data']['address']->province . $data['data']['address']->city . $data['data']['address']->district . $data['data']['address']->address, 'tel' => $data['data']['address']->mobile, 'buy_number' => $goods_number, 'pay_name' => $pay_name, 'pay_time' => 0, 'order_amount' => $data['data']['order_amount'], 'integral_amount' => $data['data']['group_price'], 'vat_inv_taxpayer_id' => $goods_attr_id]);
                //生成订单
                $order = OrderInfo::create(['order_sn' => $order_sn, 'parent_id' => $group->group_id, 'user_id' => $user_id,'pay_status' => 2, 'order_status' => 1, 'consignee' => $data['data']['address']->consignee, 'address' => $data['data']['address']->address, 'province' => $data['data']['address']->province, 'city' => $data['data']['address']->city, 'district' => $data['data']['address']->district,
                    'mobile' => $data['data']['address']->mobile, 'tel' => $data['data']['address']->mobile, 'shipping_fee' => 0.00, 'froms' => 'app', 'bonus' => 0, 'integral' => 0, 'integral_money' => 0, 'postscript' => '', 'goods_amount' => $data['data']['group_price'], 'order_amount' => $data['data']['order_amount'], 'discount' => '', 'add_time' => time(), 'vat_inv_company_name' => '', 'vat_inv_taxpayer_id' => '', 'inv_money' => 0, 'extension_code' => 'group_buy']);
                OrderGoods::create(['order_id' => $order->order_id, 'goods_id' => $goods_id, 'goods_name' => $data['data']['goods']['goods_name'], 'goods_sn' => $order_sn, 'goods_number' => $goods_number, 'goods_price' => $data['data']['price'], 'market_price' => $data['data']['goods']['market_price'], 'goods_attr' => $goods_attr_name, 'goods_attr_id' => $goods_attr_id]);
                GroupGoods::where('goods_id', $goods_id)->increment('ex_have');//add 1 hoe
                GroupOpen::where('group_id',$group->group_id)->increment('have');//add 1 hoe
                $result = array('charge' => ['order_no' => $order_sn,'is_group'=>1]);
                return $this->success($result,"$group->group_id");
            }else{
                $order_sn = 'T' . date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
                //生成扩展订单
                $goupinfo = GroupInfo::create(['goods_id' => $goods_id, 'user_id' => $user_id, 'group_id' => $group->group_id, 'order_sn' => $order_sn, 'pay_status' => 0, 'supplier_id' => $data['data']['supplier_id'], 'consignee' => $data['data']['address']->consignee, 'address' => $data['data']['address']->province . $data['data']['address']->city . $data['data']['address']->district . $data['data']['address']->address, 'tel' => $data['data']['address']->mobile, 'buy_number' => $goods_number, 'pay_name' => $pay_name, 'pay_time' => 0, 'order_amount' => $data['data']['order_amount'], 'integral_amount' => $data['data']['group_price'], 'vat_inv_taxpayer_id' => $goods_attr_id]);
                //生成订单
                $order = OrderInfo::create(['order_sn' => $order_sn, 'parent_id' => $group->group_id, 'user_id' => $user_id, 'order_status' => 1, 'consignee' => $data['data']['address']->consignee, 'address' => $data['data']['address']->address, 'province' => $data['data']['address']->province, 'city' => $data['data']['address']->city, 'district' => $data['data']['address']->district,
                    'mobile' => $data['data']['address']->mobile, 'tel' => $data['data']['address']->mobile, 'shipping_fee' => 0.00, 'froms' => 'app', 'bonus' => 0, 'integral' => 0, 'integral_money' => 0, 'postscript' => '', 'goods_amount' => $data['data']['group_price'], 'order_amount' => $data['data']['order_amount'], 'discount' => '', 'add_time' => time(), 'vat_inv_company_name' => '', 'vat_inv_taxpayer_id' => '', 'inv_money' => 0, 'extension_code' => 'group_buy']);
                OrderGoods::create(['order_id' => $order->order_id, 'goods_id' => $goods_id, 'goods_name' => $data['data']['goods']['goods_name'], 'goods_sn' => $order_sn, 'goods_number' => $goods_number, 'goods_price' => $data['data']['price'], 'market_price' => $data['data']['goods']['market_price'], 'goods_attr' => $goods_attr_name, 'goods_attr_id' => $goods_attr_id]);
                $result = $this->payorder($order_sn, $pay_name, $data['data']['order_amount'], $client_ip, $openid);
                return $this->success($result,"$group->group_id");
            }

        } else {
            return $this->error($data['data'], $data['msg'], $data['code']);
        }
    }

    private function payorder($order_sn, $pay_name, $order_amount, $client_ip, $openid = '')
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

    /**
     * 返回接口名称
     * @return string
     */
    public function getMethod()
    {

    }

//    public function recount()
//    {
//
//        $time = time() - 86400;
//        $goods = DB::table('woke_goods')->join('woke_group_goods_extends', 'woke_goods.goods_id', '=', 'woke_group_goods_extends.goods_id')->join('woke_group_open', 'woke_goods.goods_id', '=', 'woke_group_open.goods_id')->join('woke_group_info', 'woke_goods.goods_id', '=', 'woke_group_info.goods_id')->where('woke_goods.is_on_sale', 1)->where('woke_goods.is_delete', 0)->where('woke_group_goods_extends.recommend', 1)->where('woke_group_goods_extends.start_time', '<=', $time)->where('woke_group_goods_extends.end_time', '>=', $time)->where('woke_group_goods_extends.examine_status', 4)->where('woke_group_info.pay_status', 1)->where('woke_group_open.start_time', '>', time() - 86400)->select('woke_group_info.goods_id', DB::raw('SUM(woke_group_info.buy_number) as buy_number'))->groupBy('woke_group_info.goods_id')->orderBy('buy_number', 'desc')->get();
//        foreach ($goods as $K => $v) {
//            $goods[$K]->order_size = strval(GroupInfo::where('goods_id', $v->goods_id)->count());
//        }
//        return $this->success($goods, '');
//    }
}
