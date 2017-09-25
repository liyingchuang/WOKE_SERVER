<?php

namespace App\Http\Controllers\client;


use App\Events\IntegralEvent;
use App\Goods;
use App\GoodsAttr;
use App\GroupGoods;
use App\GroupInfo;
use App\GroupOpen;
use App\Http\Controllers\ApiController;
use App\Jobs\BonusJobs;
use App\OrderAction;
use App\OrderGoods;
use App\OrderInfo;
use App\OrderTotal;
use Endroid\QrCode\QrCode;
use Illuminate\Http\Request;
use Pingpp\WxpubOAuth;


class RouterController extends ApiController
{
    /**
     *
     * 接口入口
     * @param Request $request
     */
    public function index(Request $request)
    {
        //2.用户行为统计
        $server = new Server($request, new Error);
        return $server->run();
    }


    /**
     * 支付异步
     * @param Request $request
     */
    public function webhook(Request $request)
    {
        $event = json_decode(file_get_contents("php://input"));
        if (!isset($event->type)) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request');
            exit("fail");
        }
        switch ($event->type) {
            case "charge.succeeded":
                // 开发者在此处加入对支付异步通知的处理代码
                $order_sn = $event->data->object->order_no;//订单号
                $channel = $event->data->object->channel;//渠道
                $id = $event->data->object->id;//渠道
                //修改订单状态
                $t = substr($order_sn, 0, 1);
                if ($t == 'A') {
                    $order_t = OrderTotal::where('order_total_sn', $order_sn)->where('pay_status', 0)->first();
                    $order_id = explode(',', $order_t->order_id);
                    OrderTotal::where('Order_total_sn', $order_t->order_total_sn)->update(['pay_status' => 2, 'pay_time' => time()]);
                    OrderInfo::whereIn('order_id', $order_id)->update(['pay_status' => 2, 'order_status' => 1, 'pay_name' => $channel, 'pay_time' => time()]);
                    foreach ($order_id as $k => $v) {
                        OrderAction::create(['order_id' => $v, 'action_note' => '完成支付', 'pay_status' => 2, 'action_user' => '客户', 'order_status' => 1, 'log_time' => time()]);
                    }
                    $orderinfo = OrderGoods::whereIn('order_id', $order_id)->get();
                    $this->sendMessage($order_t->user_id, [], '订单提醒', '亲爱的用户您好，您已下单成功', 0);
                    foreach ($orderinfo as $k => $v) {
                        if ($v->goods_attr_id) {
                            GoodsAttr::where('goods_id', $v->goods_id)->where('id', $v->goods_attr_id)->decrement('goods_number', $v->goods_number);
                            Goods::where('goods_id', $v->goods_id)->decrement('goods_number', $v->goods_number);
                        } else {
                            Goods::where('goods_id', $v->goods_id)->decrement('goods_number', $v->goods_number);
                        }
                    }
                    // 0.如果是酒币支付或者混合支付扣除相关人员的酒币
                    if ($order_t->integral_money > 0) {
                        event(new IntegralEvent($order_t->user_id, -$order_t->integral_money, '用户您好，您在蜗客商城消费' . $order_t->order_total_price . '元，获得酒币', 2));
                    }
                    // 1.直接返酒币
                    if ($order_t->integral > 0) {
                        event(new IntegralEvent($order_t->user_id, $order_t->integral, '用户您好，您在蜗客商城消费' . $order_t->order_total_price . '元，获得酒币', 3));
                    }
                    $order = new OrderInfo();
                    //$order->order($order_t->order_total_id, $order_t->user_id, $order_t->bonus, $order_t->order_total_price);
                    $order->setAttribute('order_id', $order_t->order_total_id);
                    $order->setAttribute('user_id', $order_t->user_id);
                    $order->setAttribute('bonus', $order_t->bonus);
                    $order->setAttribute('order_amount', $order_t->order_total_price);
                    $order->setAttribute('goods_amount', $order_t->bonus);
                    $this->dispatch(new BonusJobs($order));
                    header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK');
                    exit;
                }
                $order = OrderInfo::with('ordergoods')->where('order_status', '<>', 2)->where('pay_status', '<>', 2)->where('extension_code', '<>', 'refund')->where('order_sn', $order_sn)->first();
                if (!empty($order)) {
                    $t = substr($order_sn, 0, 1);
                    if($t=='T'){
                        $info=GroupInfo::where('order_sn', $order_sn)->first();
                        GroupGoods::where('goods_id', $info->goods_id)->increment('ex_have');//add 1 hoe
                        GroupOpen::where('group_id', $info->group_id)->increment('have');//add 1 hoe
                        GroupInfo::where('order_sn', $order_sn)->update(['pay_status' => 2, 'pay_time' => time(), 'vat_inv_company_name' => $id]);
                        $group = GroupOpen::where('group_id', $info->group_id)->where('goods_id', $info->goods_id)->first();
                        $goods = GroupGoods::where('goods_id', $info->goods_id)->first();
                        //up order
                        OrderInfo::where('order_sn', $order_sn)->update(['pay_status' => 2, 'order_status' => 1, 'pay_name' => $channel, 'pay_time' => time()]);
                        OrderAction::create(['order_id' => $order->order_id, 'action_note' => '完成支付', 'pay_status' => 2, 'action_user' => '客户', 'order_status' => 1, 'log_time' => time()]);
                        $this->sendMessage($order->user_id, [], '订单提醒', '亲爱的用户您好，您已下单成功', 0);
                        if ($group && $group->have >= $goods->ex_number) {//成团
                            $group->group_status = 1;
                            $group->save();
                            OrderInfo::where('parent_id', $info->group_id)->update(['extension_code' => 'group_success']);
                            $list = GroupInfo::where('group_id', $info->group_id)->where('tel', '<>', '188888888888')->get();
                            foreach ($list as $k => $v) {
                                $this->sendMessage($v->user_id, [], '订单提醒', '亲爱的用户您好，您已参与的团购已经成团', 0);
                            }
                        }
                        foreach ($order->ordergoods as $k => $v) {
                            if ($v->goods_attr_id) {
                                GoodsAttr::where('goods_id', $v->goods_id)->where('id', $v->goods_attr_id)->decrement('goods_number', $v->goods_number);
                                Goods::where('goods_id', $v->goods_id)->decrement('goods_number', $v->goods_number);
                            } else {
                                Goods::where('goods_id', $v->goods_id)->decrement('goods_number', $v->goods_number);
                            }
                        }
                        header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK');
                        exit;
                    }
                    OrderInfo::where('order_sn', $order_sn)->update(['pay_status' => 2, 'order_status' => 1, 'pay_name' => $channel, 'pay_time' => time()]);
                    OrderAction::create(['order_id' => $order->order_id, 'action_note' => '完成支付', 'pay_status' => 2, 'action_user' => '客户', 'order_status' => 1, 'log_time' => time()]);
                    $this->sendMessage($order->user_id, [], '订单提醒', '亲爱的用户您好，您已下单成功，我们正马不停蹄为您打包发货，请耐心等待，收到产品如有任何问题，您可一定要联系客服妹妹，保证为您排忧解难。', 0);

                    // 0.如果是酒币支付或者混合支付扣除相关人员的酒币
                    if ($order->integral_money > 0) {
                        event(new IntegralEvent($order->user_id, -$order->integral_money, '用户您好，您在蜗客商城消费' . $order->order_amount . '元，获得酒币', 2));
                    }
                    // 1.直接返酒币
                    if ($order->integral > 0) {
                        event(new IntegralEvent($order->user_id, $order->integral, '用户您好，您在蜗客商城消费' . $order->order_amount . '元，获得酒币', 3));
                    }
                    // 2.活动反酒币 分享首次下单返父类10%
                    // 2.1 查看是不是新用户 是了返
                    // 2.2 是不是首单
                    /*
                    $order_num = OrderInfo::where('user_id', $order->user_id)->where('pay_status', 2)->where('shipping_status', '<>', 4)->count();
                    // todo 正式上线 $order->user_id>100 真实用户id
                    if ($order->user_id > 12763 && !$order_num) {
                        if ($order->bonus > 0) {
                            $user = User::where('user_id', $order->user_id)->first();
                            $integral = $order->bonus * 0.05;
                            event(new IntegralEvent($user->parent_id, $integral, '亲爱的用户您好，您推荐的好友首次成功下单，恭喜您获取5%酒币返利，奖励酒币', 1));
                        }
                    }*/
                    //2.3夏季储酒优惠活动方案
                    //满1000、2000、5000金额返酒币
                    /*
                    $allprice = OrderGoods::where('order_id', $order->order_id)->whereIn("goods_id", [33, 35, 57, 60, 61])->select(DB::raw('sum(goods_price*goods_number) as price'))->first();
                    if ($allprice->price < 2000) {
                        $price = $allprice->price * 0.05;
                        event(new IntegralEvent($order->user_id, $price, '用户您好，您在蜗客商城消费'.$order->order_amount.'元，获得酒币', 1));
                    }
                    if ($allprice->price >= 2000 && $allprice->price < 5000) {
                        $price = $allprice->price * 0.08;
                        event(new IntegralEvent($order->user_id, $price, '用户您好，您在蜗客商城消费'.$order->order_amount.'元，获得酒币', 1));
                    }
                    if ($allprice->price >= 5000) {
                        $price = $allprice->price * 0.15;
                        event(new IntegralEvent($order->user_id, $price, '用户您好，您在蜗客商城消费'.$order->order_amount.'元，获得酒币', 1));
                    }*/
                    // 3.算等级返酒币
                    $this->dispatch(new BonusJobs($order));
                    //-1减订单下的所有商品
                    foreach ($order->ordergoods as $k => $v) {
                        if ($v->goods_attr_id) {
                            GoodsAttr::where('id', $v->goods_attr_id)->decrement('goods_number', $v->goods_number);
                        } else {
                            Goods::where('goods_id', $v->goods_id)->decrement('goods_number', $v->goods_number);
                        }
                    }
                }
                header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK');
                break;
            case "refund.succeeded":
                // 开发者在此处加入对退款异步通知的处理代码
                header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK');
                break;
            default:
                header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request');
                break;
        }
    }

    /**
     *
     * 图片上传通用
     * @param Request $request
     */
    public function uploads(Request $request)
    {
        $order = OrderInfo::where('order_sn', '2017050353565551')->first();
        $this->dispatch(new BonusJobs($order));
        echo 'ok';
    }

    public function getQrCode(Request $request)
    {
        $url = $request->get('url');
        $qrCode = new QrCode();
        $qrCode
            ->setText($url)
            ->setSize(300)
            ->setPadding(10)
            ->setErrorCorrection('high')
            ->setForegroundColor(array('r' => 0, 'g' => 0, 'b' => 0, 'a' => 0))
            ->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0))
            ->setLabelFontSize(16)
            ->setImageType(QrCode::IMAGE_TYPE_PNG);
        header('Content-Type: ' . $qrCode->getContentType());
        $qrCode->render();
    }

    /**
     *
     * @param Request $request
     * @return \App\Http\Controllers\type
     */
    public function getopneid(Request $request)
    {
        $code = $request->get('code');
        $t = $request->get('type');
        if ($t == 1) {
            $info = WxpubOAuth::getInfo('wxe2d928572092e5fd', 'db348d4f9a7e96ba6ff3d18820dd19f3', $code);
            $json = file_get_contents('https://api.weixin.qq.com/sns/userinfo?access_token=' . $info['access_token'] . '&openid=' . $info['openid'] . '&lang=zh_CN');
            return $this->success($json, '');
        } else {
            $openid = WxpubOAuth::getOpenid('wxe2d928572092e5fd', 'db348d4f9a7e96ba6ff3d18820dd19f3', $code);
            return $this->success($openid, '');
        }
    }

    /**
     *
     * @param Request $request
     * @return \App\Http\Controllers\type
     */
    public function wechat_redirect(Request $request)
    {
        $redirect_url = $request->get('redirect_url');
        $url = WxpubOAuth::createOauthUrlForCode('wxe2d928572092e5fd', $redirect_url);
        return redirect($url);
    }
}
