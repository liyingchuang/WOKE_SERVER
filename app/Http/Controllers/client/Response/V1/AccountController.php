<?php

namespace App\Http\Controllers\client\Response\V1;

use App\Cart;
use App\Http\Controllers\client\Response\BaseResponse;
use App\Http\Controllers\client\Response\InterfaceResponse;
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user_id = $request->get('user_id', null); //用户id 必须填写
        $address_id = $request->get('address_id', null); //用户地址id
        //1.查询地址
        if ($address_id) {
            $address = DB::table('woke_user_address')->where('user_id', $user_id)->where('address_id', $address_id)->first();
        } else {
            $address = DB::table('woke_user_address')->where('user_id', $user_id)->where('is_default', 1)->first();
        }
        if (empty($address)) {
            return $this->success(['is_address'=>0], '请选择收货地址');
        }else{
            $result['is_address'] = 1;
        }
        //2.查询现在的酒币数量
        $user=User::select('user_money','frozen_money')->where('user_id',$user_id)->first();
        $result['user'] = $user;
        $result['address'] = $address;
        $result['total_market_price'] = 0.00;
        $result['total_goods_price'] = 0.00;
        $result['total_price'] = 0.00;//计算参与返成总额
        $result['total_goods_number'] = 0;
        $result['total_integral'] = 0;
        $result['total_order_amount'] = 0;
        $result['cate_gray'] = 0;
        $cart_data = Cart::with('goods')->where('user_id', $user_id)->where('is_select',1)->orderBy('rec_id', 'asc')->get();
        foreach ($cart_data as $k=>$vv){
            $v=$vv->goods;
            //每次看购物车都更新商品为最新价格
            if($vv->goods_attr_id){
                $attr=DB::table('woke_goods_attr')->where('id', $vv->goods_attr_id)->first();
                $goods_number=$attr->goods_number;
                $good_price=$attr->shop_price;
                $market_price=$attr->shop_price;;
            }else{
                $goods_number=$v->goods_number;
                $good_price=$v->shop_price;
                $market_price=$v->shop_price;;
            }
            Cart::where('rec_id', $vv->rec_id)->update(['goods_price' => $good_price, 'market_price' => $market_price,'goods_name'=>$v->goods_name]);
            //删除  下架  购物车数量超过库存
            if ($v->is_delete || $v->is_on_sale == 0 || $goods_number < $vv->goods_number) {
                return $this->error($v, '['.$v->goods_name.']已下架或库存不够');
                exit;
            } else {
                //1.计算选中的价格
                $result['list'][$k]['rec_id']=$vv->rec_id;
                $result['list'][$k]['goods_thumb']=$v->goods_thumb;
                $result['list'][$k]['market_price']=$market_price;
                $result['list'][$k]['shop_price']=$good_price;
                $result['list'][$k]['goods_name']=$v->goods_name;
                $result['list'][$k]['goods_sn']=$v->goods_sn;
                $result['list'][$k]['cat_id']=$v->cat_id;
                $result['list'][$k]['is_real']=$v->is_real;
                $result['list'][$k]['goods_id']=$v->goods_id;
                $result['list'][$k]['is_delete']=$v->is_delete;
                $result['list'][$k]['is_on_sale']=$v->is_on_sale;
                $result['list'][$k]['goods_attr']=$vv->goods_attr;
                $result['list'][$k]['goods_attr_id']=$vv->goods_attr_id;
                $result['list'][$k]['goods_number']=$vv->goods_number;
                $result['total_market_price']= $result['total_market_price']+$market_price*$vv->goods_number;
                $result['total_goods_price']= $result['total_goods_price']+$good_price*$vv->goods_number;
                $result['total_goods_number']= $result['total_goods_number']+1;
                if($v->is_real){
                    $result['total_price'] =$result['total_price']+$good_price*$vv->goods_number;//计算参与返成总额
                }
                if($v->integral>0){
                    $result['total_integral'] =$result['total_integral']+$v->integral*$vv->goods_number;
                }
                if($v->cat_id==999){
                    $result['cate_gray']= $result['cate_gray']+$good_price*$vv->goods_number;
                }
            }
            if($result['total_goods_price']>=58.00){
                $result['shipping_fee']=$this->getFormatPrice(0);
            }else{
                $result['shipping_fee']=$this->getFormatPrice(5.00);
            }
            $result['total_goods_price']=$this->getFormatPrice($result['total_goods_price']);
            $result['total_market_price']=$this->getFormatPrice($result['total_market_price']);
            $result['total_price']=$this->getFormatPrice($result['total_price']);
            $order_amount=$result['total_goods_price']+$result['shipping_fee'];
            $result['total_order_amount']=$this->getFormatPrice($order_amount);
        }
        if( $result['cate_gray'] >= 200 ){
            $result['total_order_amount']=$this->getFormatPrice( $result['total_order_amount']-40);
        }
        return $this->success($result, '');
    }




    /**
     * 返回接口名称
     * @return string
     */
    public function getMethod()
    {
        // TODO: Implement getMethod() method.
    }
}
