<?php

namespace App\Http\Controllers\client\Response\V2;

use App\Cart;
use App\Http\Controllers\client\Response\BaseResponse;
use App\Http\Controllers\client\Response\InterfaceResponse;
use App\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Goods;

class CartController extends BaseResponse implements InterfaceResponse
{

    public function __construct()
    {
        $this->except = ['index','select'];
    }

    /**
     *  购物车首页
     *
     * @param  \Illuminate\Http\Request $request
     * @return  json
     */
    public function index(Request $request)
    {
        $user_id = $request->get('user_id', null);
        $cart_data = Cart::with('goods')->where('user_id', $user_id)->orderBy('rec_id', 'asc')->get();
        $tooal_market_price = 0.00;
        $total_price = 0.00;
        $total_count = 0;
        if (!count($cart_data)) {
            return $this->error(0, '请添加商品');
        }
        foreach ($cart_data as $k => $cart) {
            if (!empty($cart->goods)) {
                $v = $cart->goods;
                unset($cart->goods);
                if ($cart->goods_attr_id) {
                    // 更新Sku价格
                    $attr = DB::table('woke_goods_attr')->where('id', $cart->goods_attr_id)->first();
                    $goods_number = $attr->goods_number;
                    $goods_price = $attr->shop_price;
                    $goods_market_price = $attr->shop_price;
                } else {
                    // 更新商品价格
                    $goods_number = $v->goods_number;
                    $goods_price = $v->shop_price;
                    $goods_market_price = $v->shop_price;
                }
                Cart::where('rec_id', $cart->rec_id)->update(['goods_price' => $goods_price, 'market_price' => $goods_market_price, 'goods_name' => $v->goods_name]);
                //删除  下架  购物车数量超过库存
                if ($v->is_delete || $v->is_on_sale == 0 || $goods_number < $cart->goods_number) {
                    $cart->status = 1;
                } else {
                    $list[$v->supplier_id]['tooal_market_price'] = 0.00;
                    $list[$v->supplier_id]['total_price'] = 0.00;
                    $list[$v->supplier_id]['total_count'] = 0;
                    $cart->goods_thumb = $v->goods_thumb;
                    $cart->status = 0;
                    $list[$v->supplier_id]['list'][] = $cart;
                    //计算选中价格
                    if ($cart->is_select == 1) {
                        $tooal_market_price += $cart->market_price * $cart->goods_number;
                        $total_price += $cart->goods_price * $cart->goods_number;
                        $total_count += 1;
                        $list[$v->supplier_id]['tooal_market_price'] += $cart->market_price * $cart->goods_number;
                        $list[$v->supplier_id]['total_price'] += $cart->goods_price * $cart->goods_number;
                        $list[$v->supplier_id]['total_count'] += 1;
                    }
                }
            }
        }
        list($keys, $value) = array_divide($list);
        foreach ($keys as $k => $v) {
            $list[$v]['info'] = Supplier::where('supplier_id', $v)->first();
        }
        return $this->success(['store' => array_values($list), 'tooal_market_price' => (string) $tooal_market_price, 'total_price' => (string) $total_price, 'total_count' => (string) $total_count], '成功');
    }
}
