<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Utils\ShopLibrary;
use Illuminate\Support\Facades\DB;

/**
 * 购物车相关操作
 */
class CartController extends Controller {

    /**
     *  购物车商品列表
     *
     * @param  \Illuminate\Http\Request  $request
     * @return  json
     */
    public function getShow(Request $request) {
        $user_id = $request->get('user_id', null);
        $shopLibrary = new ShopLibrary();
        $cart_data = $shopLibrary->get_cart_goods($user_id);
        if (!count($cart_data['supplier_list'])) {
            return $this->error($cart_data, '购物车无商品');
        } else {
            return $this->success($cart_data, '成功');
        }
    }

    /**
     * 修改购物车单个商品数量
     * 
     * @param \Illuminate\Http\Request $request
     * @return type  json
     */
    public function postUpdate(Request $request) {
        $rec_id = $request->get('rec_id'); //购物车id
        $goods_number = $request->get('number'); //购买数量
        $goods_id = $request->get('goods_id'); //商品id
        $user_id = $request->get('user_id'); //用户ID
        $shopLibrary = new ShopLibrary();
        $goods_info = $shopLibrary->goodCheck($goods_id, $goods_number, $user_id, 'update');
        if ($goods_info['status']) {//状态
            return $this->error(null, $goods_info['info']);
        }
        //修改购物车数量
        $is_update = DB::table('ecs_cart')->where('rec_id', $rec_id)->where('user_id', $user_id)->update(['goods_number' => $goods_number]);
        if ($is_update) {
            $result['goods_number'] = $goods_number;
            return $this->success($result, '数量修改成功');
        } else {
            return $this->error(null, '修改失败！');
        }
    }

    /**
     * 删除购物车的商品
     * @param \Illuminate\Http\Request $request 
     */
    public function getDelete(Request $request) {
        $rec_id = $request->get('rec_id'); //购物车id
        $delete = DB::table('ecs_cart')->where('rec_id', $rec_id)->delete();
        if ($delete) {
            return $this->success(null, '删除成功!');
        } else {
            return $this->error(null, '删除失败!');
        }
    }

    /**
     * 添加商品到购物车
     * @param \Illuminate\Http\Request $request
     */
    public function postAdd(Request $request) {
        $user_id = $request->get('user_id'); //用户ID
        $goods_number = $request->get('number', 1); //购买数量
        $goods_id = $request->get('goods_id'); //商品id
        $shopLibrary = new ShopLibrary();
        $goods_info = $shopLibrary->goodCheck($goods_id, $goods_number, $user_id, 'add');
        if ($goods_info['status']) {//状态
            return $this->error(null, $goods_info['info']);
        }
        $goods = $goods_info['goods'];
        //3.检查购物车是否有  
        $cart = DB::table('ecs_cart')->where('user_id', $user_id)->where('goods_id', $goods_id)->first();
        if (!empty($cart)) {//有了就加数量
            $newNumber = $cart->goods_number + $goods_number;
            DB::table('ecs_cart')->where('user_id', $user_id)->where('goods_id', $goods_id)->update(['goods_number' => $newNumber]);
        } else {//没有就添加到购物车
            $add_goods = [
                'user_id' => $user_id,
                'session_id' => '',
                'goods_id' => $goods_id,
                'goods_sn' => $goods->goods_sn,
                'product_id' => 0,
                'goods_name' => $goods->goods_name,
                'market_price' => $goods->market_price,
                'goods_price' => $goods->shop_price,
                'goods_number' => $goods_number,
                'goods_attr' => '',
                'goods_attr_id' => '',
                'is_real' => $goods->is_real,
                'extension_code' => $goods->extension_code,
                'is_gift' => 0,
                'is_shipping' => $goods->is_shipping,
                'add_time' => time(),
                'rec_type' => 0
            ];
            DB::table('ecs_cart')->insert($add_goods);
        }
        return $this->success(null, ' 购物车添加成功!');
    }
    /**
     * 修改选中与否接口
     * @param \Illuminate\Http\Request $request
     */
    public function select(Request $request) {
          $user_id = $request->get('user_id'); //用户ID
          $select = $request->get('select'); //
          $rec_id = $request->get('rec_id',null); //购物车id
          if($rec_id){//修改单品
             $is_update = DB::table('ecs_cart')->where('rec_id', $rec_id)->where('user_id', $user_id)->update(['is_select'=>$select]); 
               if ($is_update) {
                   return $this->success(null, '修改成功！');
               }else{
                   return $this->error(null, '修改失败！');
               }
          }else{
              $is_update = DB::table('ecs_cart')->where('user_id', $user_id)->update(['is_select'=>$select]); 
              if ($is_update) {
                   return $this->success(null, '修改成功！');
               }else{
                   return $this->error(null, '修改失败！');
              }
          }
        
    }
    

}
