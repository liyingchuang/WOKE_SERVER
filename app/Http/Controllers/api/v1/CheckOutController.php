<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Utils\ShopLibrary;
use Illuminate\Support\Facades\Log;


/**
 * 结算相关
 *
 */
class CheckOutController extends Controller {
    const ERROR=0;
    const ERROR_GO_CART=2;
    const ERROR_GO_ADDRESS=3;

        /**
     * 结算
     *
     * @return \Illuminate\Http\Response
     */
    public function postIndex(Request $request) {
        $goods = $request->get('goods'); //商品列表 必须写
        $user_id = $request->get('user_id', null); //用户id 必须填写
        $address_id = $request->get('address_id', null); //用户地址id
        $express = $request->get('express', null); //用户id
        $express_array = json_decode($express, true); //快递列表
        $goods_array = json_decode($goods, true); //商品列表
        $express_data = [];
        if($express_array){
            foreach ($express_array as $value) {
                $express_data[$value['supplier_id']] = $value['shipping_id'];
            }
        }
        //1.查询地址
        if ($address_id) {
            $address = DB::table('ecs_user_address')->where('user_id', $user_id)->where('address_id', $address_id)->first();
        } else {
            $address = DB::table('ecs_user_address')->where('user_id', $user_id)->where('is_default', 1)->first();
        }
        if (empty($address)) {
            return $this->error(null, '请选择收货地址',self::ERROR_GO_ADDRESS);
        }
        $province = DB::table('ecs_region')->select('region_name')->where('region_id', $address->province)->first(); //省
        $city = DB::table('ecs_region')->select('region_name')->where('region_id', $address->city)->first(); //市
        $district = DB::table('ecs_region')->select('region_name')->where('region_id', $address->district)->first(); //区
        $addres = !empty($province) ? $province->region_name : '';
        $addres .=!empty($city) ? $city->region_name : '';
        $addres.=!empty($district) ? $district->region_name : '';
        $result['address']['address'] = $addres . $address->address;
        $result['address']['mobile'] = $address->mobile;
        $result['address']['country'] = $address->country;
        $result['address']['province'] = $address->province;
        $result['address']['city'] = $address->city;
        $result['address']['district'] = $address->district;
        $result['address']['consignee'] = $address->consignee;
        $result['address']['address_id'] = $address->address_id;
        //2.查询所有快递
        $express_list = DB::table('ecs_shipping')->select('shipping_name', 'shipping_id')->where('enabled', 1)->orderBy('shipping_id', 'desc')->get();
        //3.商品归店
        $result['suppliers'] = [];
        $result['total_market_price'] = 0.00;
        $result['total_goods_price'] = 0.00;
        $result['total_goods_number'] = 0;
        $result['total_express_fee'] = 0.00;
        $shopLibrary = new ShopLibrary();
        foreach ($goods_array as $v) {
            $goods_info = $shopLibrary->goodCheck($v['goods_id'], $v['val'], $user_id); //检查库存 下架 限购
            if ($goods_info['status']) {//状态
                return $this->error(null, $goods_info['info'],self::ERROR_GO_CART);

            } else {
                $goods_info = $goods_info['goods'];
            }
            if ($goods_info->supplier_id) {
                $supplier = DB::table('ecs_supplier')->select('supplier_name')->where('supplier_id', $goods_info->supplier_id)->first();
                $supplier_name = '供货商：' . $supplier->supplier_name;
            } else {
                $supplier_name = '蜗客自营';
            }
            $goods_info->cart_goods_number = $v['val'];
            $result['suppliers'][$goods_info->supplier_id]['express_list'] = $express_list; //可选快递
            $result['suppliers'][$goods_info->supplier_id]['goods_list'][] = $goods_info;
            $result['suppliers'][$goods_info->supplier_id]['supplier_name'] = $supplier_name;
            $result['suppliers'][$goods_info->supplier_id]['supplier_id'] = $goods_info->supplier_id;
            if (array_key_exists('suppliers_goods_price', $result['suppliers'][$goods_info->supplier_id])) {//店铺真正总价
                $result['suppliers'][$goods_info->supplier_id]['suppliers_goods_price'] +=$goods_info->shop_price * $v['val'];
            } else {
                $result['suppliers'][$goods_info->supplier_id]['suppliers_goods_price'] = $goods_info->shop_price * $v['val'];
            }
            if (array_key_exists('suppliers_market_price', $result['suppliers'][$goods_info->supplier_id])) {//店铺虚假总价
                $result['suppliers'][$goods_info->supplier_id]['suppliers_market_price'] +=$goods_info->market_price * $v['val'];
            } else {
                $result['suppliers'][$goods_info->supplier_id]['suppliers_market_price'] = $goods_info->market_price * $v['val'];
            }
            if (array_key_exists('suppliers_goods_number', $result['suppliers'][$goods_info->supplier_id])) {//店铺虚假总价
                $result['suppliers'][$goods_info->supplier_id]['suppliers_goods_number']+=$v['val'];
            } else {
                $result['suppliers'][$goods_info->supplier_id]['suppliers_goods_number'] = $v['val'];
            }
            if (!array_key_exists('shipping_id', $result['suppliers'][$goods_info->supplier_id])) {//快递操作
                if (isset($express_data[$goods_info->supplier_id])) {
                    $result['suppliers'][$goods_info->supplier_id]['shipping_id'] = $express_data[$goods_info->supplier_id]; //用户选的快递
                } else {
                    $result['suppliers'][$goods_info->supplier_id]['shipping_id'] = 12; //默认快递
                }
            }
            $result['total_goods_number']+=$v['val'];
            $result['total_market_price']+=$goods_info->market_price * $v['val'];
            $result['total_goods_price']+=$goods_info->shop_price * $v['val'];
            if ($goods_info->is_shipping == 0) {//有不免费的全收快递费
                $result['suppliers'][$goods_info->supplier_id]['shipping_fee'] = 1;
            }
        }
        //快递费运算  
        foreach ($result['suppliers'] as $key => $value) {
            $shipping_id = $result['suppliers'][$key]['shipping_id'];
            if (array_key_exists('shipping_fee', $result['suppliers'][$key])) {//快递操作
                $shipping_fee = $result['suppliers'][$key]['shipping_fee'];
            } else {
                $shipping_fee = 0;
            }
            $express_fee = $this->express_fee($result['suppliers'][$key]['goods_list'], $shipping_id, $shipping_fee, $result['address']);
            $result['suppliers'][$key]['express_fee'] = $express_fee;
            //店铺总费用
            $result['suppliers'][$key]['suppliers_goods_price']+=$express_fee;
            $result['suppliers'][$key]['suppliers_market_price']+=$express_fee;
            // suppliers_goods_price
            //总费用
            $result['total_goods_price']+=$express_fee;
            $result['total_market_price']+=$express_fee;
            $result['total_express_fee']+=$express_fee;
        }
        $payment_list = DB::table('ecs_payment')->select('pay_name', 'pay_code', 'pay_id')->where('enabled', 1)->where('pay_code','!=','faqpay')->get();
        $result['payment_list'] = $payment_list;
        $result['suppliers'] = array_values($result['suppliers']);
        return $this->success($result, 'ok');
    }

    /**
     * 给出商品计算出快递费
     * @param type $goods 商品列表
     * @param type $express 快递类型
     * @param type $shipping_fee  是否免运费 默认不免
     * @param type $address Description
     * 
     */
    private function express_fee($goods = array(), $express, $shipping_fee = 1, $address) {
        if ($shipping_fee) {//要计算运费
            return $this->shipping_fee($express, $address);
        } else {
            $shipping_data = [];
            foreach ($goods as $value) {
                $shipping_data[] = $value->is_shipping;
            }
            $min_shipping = min($shipping_data);
            if ($min_shipping == 2) {//全免快递费 /1.免普通  2.全免  3.免顺丰
                return 0.00;
            }
            if ($min_shipping == 1 && $express == 12) {//免普通
                return 0.00;
            }
            //免普通选择了顺丰收快递费
            return $this->shipping_fee($express, $address);
        }
    }

    /**
     * 计算运费
     * @param type $shipping_id
     */
    private function shipping_fee($shipping_id, $shoping_address) {
        unset($shoping_address['mobile']);
        unset($shoping_address['consignee']);
        unset($shoping_address['address']);
        $shipping_data = DB::table('ecs_area_region')
                ->leftJoin('ecs_shipping_area', 'ecs_shipping_area.shipping_area_id', '=', 'ecs_area_region.shipping_area_id')
                ->where('ecs_shipping_area.shipping_id', '=', $shipping_id)
                ->whereIn('ecs_area_region.region_id', $shoping_address)
                ->first();
        $shipping_config=$this->unserialize_config($shipping_data->configure);
        if(empty($shipping_config)){
          Log::info('api/v1/checkoutok/ function shipping_fee'.$shipping_data->configure);
        }
       return $shipping_config['base_fee'];
    }
    /**
     * 反序列
     * @param type $param
     */
    private function unserialize_config($str) {
        if (is_string($str) && ($arr = unserialize($str)) !== false) {
            $config = array();
            foreach ($arr as $val) {
                $config[$val['name']] = $val['value'];
            }
            return $config;
        } else {
            return false;
        }
    }

}
