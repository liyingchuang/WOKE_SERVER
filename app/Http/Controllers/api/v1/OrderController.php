<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\api\v1\CheckOutController;
use Illuminate\Support\Facades\DB;

class OrderController extends CheckOutController {

    /**
     * 生成订单
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        //1.调用checkout接口获得数据
        $json_data = $this->postIndex($request)->getContent();
        $user_id = $request->get('user_id', null); //用户id 必须填写
        $pay_id = $request->get('pay_id', null); //pay_id 必须填写
        $aray_data = json_decode($json_data, true);
        if ($aray_data) {
            if ($aray_data['code']) {
                return $this->error(null, $aray_data['info']);
            }
            //1.地址提取
            $address = $aray_data['data']['address'];
            unset($address['address_id']);
            //2.拆单
            $parent_order_id = 0;
            if (count($aray_data['data']['suppliers']) > 1) {//订单数大于1 生成父订单
                $order_sn = $this->build_order_no();
                $total_goods_price = $this->get_price($aray_data['data']['total_goods_price']);
                $parent_order_id = DB::table('ecs_order_info')->insertGetId(['froms' => 'PARENT', 'order_sn' => $order_sn, 'order_amount' => $total_goods_price], 'order_id');
                DB::table('ecs_pay_log')->insert(['order_id' => $parent_order_id, 'order_amount' => $total_goods_price, 'order_type' => 0, 'is_paid' => 0]);
                $return['order_sn'] = $order_sn;
                $return['order_id'] = $parent_order_id;
            }
            $pay_name = DB::table('ecs_payment')->select('pay_name','pay_code', 'pay_id')->where('pay_id', $pay_id)->where('enabled', 1)->first();
            foreach ($aray_data['data']['suppliers'] as $value) {//遍历所有订单
                //1生成订单
                $express = DB::table('ecs_shipping')->select('shipping_name', 'shipping_id')->where('shipping_id', $value['shipping_id'])->where('enabled', 1)->first();
                $suppliers_goods_price = $this->get_price($value['suppliers_goods_price']);
                $order = $address;
                $order['order_sn'] = $this->build_order_no();
                $order['user_id'] = $user_id;
                $order['order_status'] = 0;
                $order['shipping_status'] = 0;
                $order['parent_order_id'] = $parent_order_id;
                $order['pay_status'] = 0;
                $order['shipping_id'] = $value['shipping_id']; //快递id
                $order['shipping_name'] = $express->shipping_name; //快递名称
                $order['goods_amount'] = $this->get_price($suppliers_goods_price - $value['express_fee']); //商品费用
                $order['order_amount'] = $suppliers_goods_price; //订单总计
                $order['shipping_fee'] = $this->get_price($value['express_fee']); //快递费
                $order['referer'] = $value['supplier_name']; //店铺名
                $order['supplier_id'] = $value['supplier_id']; //店铺id
                $order['pay_id'] = $pay_id; //支付方式
                $order['pay_name'] = $pay_name->pay_name; //支付方式名称
                $order['froms'] = "app"; //
                $order['inv_status'] = "unprovided"; //
                $order['add_time'] = time() - 28800; //下单时间
                $order['inv_consignee_country'] = 1; //收票国家
                $order['inv_money'] = $suppliers_goods_price; //收票国家
                $order_id = DB::table('ecs_order_info')->insertGetId($order, 'order_id');
                $this->add_order_goods($value['goods_list'], $user_id, $order_id); //订单明细
                //2.生成支付订单
                DB::table('ecs_pay_log')->insert(['order_id' => $order_id, 'order_amount' => $suppliers_goods_price, 'order_type' => 0, 'is_paid' => 0]);
                if (count($aray_data['data']['suppliers']) == 1) {
                    $return['order_sn'] = $order['order_sn'];
                    $return['order_id'] = $order_id;
                    $return['order_sn_list']=[];
                    $return['order_id_list']=[];
                } else {
                    $return['order_sn_list'][] = $order['order_sn'];
                    $return['order_id_list'][] = $order_id;
                }
                $return['pay_code'] = $pay_name->pay_code;
                unset($order);
            }
            $total_goods_price=$this->get_price($aray_data['data']['total_goods_price'])*100;
            $return['total_goods_price'] =$total_goods_price;
            $return['total_goods_number'] = $aray_data['data']['total_goods_number'];
            return $this->success($return, 'ok');
        } else {
           return $this->error(null, '请求参数异常');
        }
    }

    /**
     * 生成订单明细
     * @param type $goods_list
     * @param type $user_id
     * @param type $order_id
     */
    private function add_order_goods($goods_list, $user_id, $order_id) {
        foreach ($goods_list as $v) {//订单商品列表
            $goods['order_id'] = $order_id;
            $goods['goods_id'] = $v['goods_id'];
            $goods['goods_sn'] = $v['goods_sn'];
            $goods['is_real'] = $v['is_real'];
            // $goods['extension_code']=$v['extension_code'];
            $goods['goods_name'] = $v['goods_name'];
            $goods['goods_number'] = $v['cart_goods_number'];
            $goods['market_price'] = $v['market_price'];
            $goods['goods_price'] = $v['shop_price'];
            $goods['goods_number'] = $v['cart_goods_number'];
            DB::table('ecs_order_goods')->insert($goods); //1.入库
            //2.减库存
            $number = $v['goods_number'] - $v['cart_goods_number'];
            DB::table('ecs_goods')->where('goods_id', $v['goods_id'])->update(['goods_number' => $number]);
            //3.删除购物车
            DB::table('ecs_cart')->where('user_id', $user_id)->where('goods_id', $v['goods_id'])->delete();
        }
        return TRUE;
    }

    /**
     * 订单号生成
     * @return type
     */
    private function build_order_no() {
        return date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }

    /**
     * 给出整书给出钱数
     * @param type
     * @return type
     */
    private function get_price($price) {
        $price = number_format(floatval($price), 2, '.', '');
        return $price;
    }

}
