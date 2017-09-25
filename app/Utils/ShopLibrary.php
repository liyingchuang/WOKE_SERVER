<?php

namespace App\Utils;

use DB;
use App\Http\Controllers\Controller;

/**
 * 购物公共实现
 */
class ShopLibrary extends Controller {

    /**
     * 用户购物车列表
     * @param type user_id
     * @return type date
     *
     */
    public function get_cart_goods($user_id) {
        $cart_data = DB::table('woke_cart')
                ->leftJoin('woke_goods', 'woke_cart.goods_id', '=', 'woke_goods.goods_id')
                ->select('woke_cart.rec_id', 'woke_cart.is_select', 'woke_cart.goods_name', 'woke_cart.goods_number as cart_goods_number', 'woke_cart.goods_id', 'woke_goods.is_delete', 'woke_goods.is_on_sale', 'woke_goods.goods_number', 'woke_goods.supplier_id', 'woke_goods.goods_thumb', 'woke_goods.shop_price', 'woke_goods.market_price')
                ->where('woke_cart.user_id', '=', $user_id)
                ->orderBy('woke_cart.parent_id', 'asc')
                ->get();
        $total = ['goods_price' => 0, 'market_price' => 0];
        $goods_list = [];
        foreach ($cart_data as $v) {
            //每次看购物车都更新商品为最新价格
            DB::table('woke_cart')->where('rec_id', $v->rec_id)->update(['goods_price' => $v->shop_price, 'market_price' => $v->market_price]);
            $v->subtotal = $this->price_format($v->shop_price * $v->cart_goods_number, false);
            $v->shop_price = $this->price_format($v->shop_price, false);
            $v->market_price = $this->price_format($v->market_price, false);
            if ($v->supplier_id) {
                $supplier = DB::table('woke_supplier')->select('supplier_name')->where('supplier_id', $v->supplier_id)->first();
                $supplier_name = '供货商：' . $supplier->supplier_name;
            } else {
                $supplier_name = '蜗客自营';
            }
            //删除  下架  购物车数量超过库存
            if ($v->is_delete || $v->is_on_sale == 0 || $v->goods_number < $v->cart_goods_number) {
                $v->status = 1;
            } else {
                //1.计算选中的价格
                if($v->is_select==1){
                    $total['goods_price'] = $total['goods_price'] + $v->shop_price * $v->cart_goods_number;
                    $total['market_price'] = $total['market_price'] + $v->market_price * $v->cart_goods_number;
                }
                $v->status = 0;
            }
            $goods_list[$v->supplier_id]['goods_list'][] = $v;
            $goods_list[$v->supplier_id]['supplier_name'] = $supplier_name;
            $goods_list[$v->supplier_id]['supplier_id'] = $v->supplier_id;
        }
        $total['goods_amount'] = $total['goods_price'];
        $total['saving'] = $this->price_format($total['market_price'] - $total['goods_price'], false);
        if ($total['market_price'] > 0) {
            $total['save_rate'] = $total['market_price'] ? round(($total['market_price'] - $total['goods_price']) *
                            100 / $total['market_price']) . '%' : 0;
        }
        $total['goods_price'] = $this->price_format($total['goods_price'], false);
        $total['market_price'] = $this->price_format($total['market_price'], false);
        $goods_list = array_values($goods_list);
        return ['supplier_list' => $goods_list, 'total' => $total];
    }

    /**
     * 商品库存 限购 统一检查
     * @param type $goods_id
     * @param type $goods_number
     * @return type
     */
    public function goodCheck($goods_id, $goods_number, $user_id, $type='update') {
        $goods = DB::table('woke_goods')
                ->select('goods_id','goods_sn','is_real','extension_code','goods_name','market_price','is_shipping','shop_price','goods_thumb','supplier_id','is_on_sale','goods_name','goods_number','is_delete','is_buy','buymax','buymax_start_date','buymax_end_date')
                ->where('goods_id', $goods_id)
                ->first();
        //0.检查商品是否存在
        if (empty($goods)) {
            return ['status' => 1, 'info' => '您选择的商品不存在/下架！'];
        }
        //1.检查库存
        if (!empty($goods) && $goods_number > $goods->goods_number) {
            return ['status' => 1, 'info' => '您选择的'. $goods->goods_name.'物品已经没有库存了，请重新选择购买'];
        } 
        //1.1检查购物里的库存＋现在购买数大于库存 只在天津购物车时判断
        if ($type == 'add') {//添加购物车
            $cart_data = DB::table('woke_cart')->where('user_id', $user_id)->where('goods_id',$goods_id)->first();
             if(!empty($cart_data)){
               $number=$goods_number+$cart_data->goods_number;
                if($number>$goods->goods_number){
                       return ['status' => 1, 'info' => '您选择的'. $goods->goods_name.'物品已经没有库存了，请重新选择购买']; 
                }
           }
        }
        //2.检查是否在卖／删除
        if ($goods->is_delete || $goods->is_on_sale == 0) {
            return ['status' => 1, 'info' => '您选择的'.$goods->goods_name.'物品已经下架，请重新选择购买'];
        }
        //3.检测限购
        $time = time()-28800;
        if ($goods->is_buy && $goods->buymax > 0 && $goods->buymax_start_date < $time && $goods->buymax_end_date > $time) {
            //3.1检查限购时间内购买的数量
            $history_total = DB::table('ecs_order_goods')
                    ->leftJoin('woke_order_info', 'woke_order_info.order_id', '=', 'woke_order_goods.order_id')
                    ->where('woke_order_info.user_id', $user_id)
                    ->where('woke_order_goods.goods_id', $goods_id)
                    ->where('woke_order_info.add_time', '>', $goods->buymax_start_date)
                    ->where('woke_order_info.add_time', '<', $goods->buymax_end_date)
                    ->sum('woke_order_goods.goods_number');
            if ($type == 'add') {//添加购物车
                $cart_total = DB::table('woke_cart')->where('user_id', $user_id)->where('goods_id', $goods_id)->sum('goods_number');
                $total = $history_total + $cart_total + $goods_number;
                if ($total > $goods->buymax) {
                    $number = $goods->buymax - $history_total;
                    if ($history_total > 0) {
                        return ['status' => 1, 'info' => '该物品已限购' . $goods->buymax . '件!您已购' . $history_total . '件, 您只能再购买' . $number . '件哦！'];
                    }
                    return ['status' => 1, 'info' => '该物品已限购' . $goods->buymax . '件!您只能购买' . $number . '件哦！'];
                }
            }
            if ($type == 'update') {//更新购物车
                $total = $history_total + $goods_number;
                if ($total > $goods->buymax) {
                    $number = $goods->buymax - $history_total;
                    DB::table('woke_cart')->where('user_id', $user_id)->where('goods_id', $goods_id)->update(['goods_price' => $number]);
                    return ['status' => 1, 'info' => '该物品已限购' . $goods->buymax . '件!您只能购买' . $number . '件哦！'];
                }
            }
        }
        return ['status' => 0, 'info' => '正常', 'goods' => $goods];
    }

    /**
     * 格式化商品价格
     *
     * @access  public
     * @param   float   $price  商品价格
     * @return  string
     */
    public function price_format($price, $change_price = true, $price_format = 2, $currency_format = "%s") {
        if ($price === '') {
            $price = 0;
        }
        if ($change_price) {
            switch ($price_format) {
                case 0:
                    $price = number_format($price, 2, '.', '');
                    break;
                case 1: // 保留不为 0 的尾数
                    $price = preg_replace('/(.*)(\\.)([0-9]*?)0+$/', '\1\2\3', number_format($price, 2, '.', ''));

                    if (substr($price, -1) == '.') {
                        $price = substr($price, 0, -1);
                    }
                    break;
                case 2: // 不四舍五入，保留1位
                    $price = substr(number_format($price, 2, '.', ''), 0, -1);
                    break;
                case 3: // 直接取整
                    $price = intval($price);
                    break;
                case 4: // 四舍五入，保留 1 位
                    $price = number_format($price, 1, '.', '');
                    break;
                case 5: // 先四舍五入，不保留小数
                    $price = round($price);
                    break;
            }
        } else {
            $price = number_format(floatval($price), 2, '.', '');
        }

        return sprintf($currency_format, $price);
    }

}
