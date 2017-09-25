<?php

namespace App\Http\Controllers\client\Response\V2;

use App\Cart;
use App\Http\Controllers\client\Response\BaseResponse;
use App\Http\Controllers\client\Response\InterfaceResponse;
use App\Supplier;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountController extends BaseResponse implements InterfaceResponse
{
    public function __construct()
    {
        $this->except = ['index'];
    }

    /**
     *
     * 结算
     * @param Request $request
     */
    public function index(Request $request)
    {
        $user_id = $request->get('user_id', null);
        $address_id = $request->get('address_id', null); //用户地址id
        $shipp_id = $request->get('shipp_id', null); //模板id
        // todo 查询地址
        if ($address_id) {
            $address = DB::table('woke_user_address')->where('user_id', $user_id)->whereAddress_id($address_id)->first();
        } else {
            $address = DB::table('woke_user_address')->where('user_id', $user_id)->whereIs_default(1)->first();
        }
        if (empty($address)) {
            return $this->success(['is_address' => 0], '请选择收货地址');
        }
        //查询用户酒币
        $user = User::select('user_money', 'frozen_money')->where('user_id', $user_id)->first();
        $total_market_price = 0.00;
        $total_goods_price = 0.00;
        $total_count = 0.00;
        $total_shipp_price = 0.00;
        $total_price = 0.00;//计算参与返成总额
        $total_integral = 0.00;
        $cart_data = Cart::with('goods')->where('user_id', $user_id)->whereIs_select(1)->orderBy('rec_id', 'asc')->get();
        if (!count($cart_data)) {
            return $this->error(null, '请添加商品');
        }
        foreach ($cart_data as $k => $cart) {
            $vv = $cart->goods;
            unset($cart->goods);
            if ($vv->is_real) {
                $total_price += $cart->goods_price * $cart->goods_number;//计算参与返成总额
            }
            if ($vv->integral > 0) {
                $total_integral = $vv->integral * $cart->goods_number;
            }
            $shipp = DB::table('woke_shipp_fee')->where('supplier_id', $vv->supplier_id)->get();
            $cart->goods_thumb = $vv->goods_thumb;
            $list[$vv->supplier_id]['supplier_id'] = $vv->supplier_id;
            $list[$vv->supplier_id]['list'][] = $cart;
            $list[$vv->supplier_id]['shipp'] = $shipp;
            if(!isset($list[$vv->supplier_id]['total_market_price'])){
                  $list[$vv->supplier_id]['total_market_price'] = 0.00;
                  $list[$vv->supplier_id]['total_goods_price'] = 0.00;
                  $list[$vv->supplier_id]['total_shipp_price'] = 0.00;
                  $list[$vv->supplier_id]['total_count'] = 0;
            }
            $list[$vv->supplier_id]['total_market_price'] += $cart->market_price * $cart->goods_number;
            $list[$vv->supplier_id]['total_goods_price'] += $cart->goods_price * $cart->goods_number;
            $list[$vv->supplier_id]['total_count'] += $cart->goods_number;
            $total_market_price += $cart->market_price * $cart->goods_number;
            $total_goods_price += $cart->goods_price * $cart->goods_number;

            $total_count += 1;
        }
        $total_price = $this->getFormatPrice($total_price);
        list($keys, $value) = array_divide($list);
        foreach ($keys as $k => $v) {
            $shipp = explode(",", $shipp_id);
            $info = DB::table('woke_shipp_fee')->whereSupplier_id($v)->whereIn('shipp_fee_id', $shipp)->first();
            if(!$info){
                $info = DB::table('woke_shipp_fee')->whereSupplier_id($v)->whereIs_default(1)->first();
            }
            $list[$v]['info'] = Supplier::where('supplier_id', $v)->first();
            $list[$v]['total_shipp_price'] = $this->shipp($info, $address->province, $v);
            $total_shipp_price += $list[$v]['total_shipp_price'];
        }
        $total_all_price = $total_goods_price + $total_shipp_price;
        return $this->success(['store' => array_values($list), 'user' => $user, 'address' => $address, 'total_all_price' => (string)$total_all_price, 'total_shipp_price' => (string)$total_shipp_price, 'total_market_price' => (string)$total_market_price, 'total_goods_price' => (string)$total_goods_price, 'total_integral' => (string)$total_integral, 'total_price' => (string)$total_price, 'total_count' => (string)$total_count], '成功');
    }

    /**
     * 计算运费
     * @return string
     */
    private function shipp($info, $province, $supplier_id)
    {
        //todo $shipp 店铺模板ID 1,2,3  $province 用户地址(上海市..)
        //todo 1.查出用户的城市ID
        $price = 0.00;
        $province_id = DB::table('woke_area_extends')->where('name', $province)->pluck('id');
        //todo 2.查出所传模板的对应运费
        $thems = DB::table('woke_shipp_fee_extends')->whereShipp_fee_id($info->shipp_fee_id)->get();
        foreach ($thems as $v) {
            $pro = explode(',', $v->province);
            if (in_array($province_id, $pro)) {
                $price = $v->price;
                break;
            } else {
                $price = DB::table('woke_shipp_fee_extends')->whereSupplier_id($supplier_id)->whereShipp_fee_id($v->shipp_fee_id)->whereIs_default(1)->pluck('price');
            }
        }
        return $price;
    }
}
