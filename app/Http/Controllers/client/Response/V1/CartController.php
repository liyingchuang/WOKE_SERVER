<?php
namespace App\Http\Controllers\client\Response\V1;

use App\Cart;
use App\Goods;
use App\Http\Controllers\client\Response\BaseResponse;
use App\Http\Controllers\client\Response\InterfaceResponse;
use App\OrderInfo;
use App\User;
use App\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends BaseResponse implements InterfaceResponse
{
    public function __construct()
    {
        $this->except = ['index','select','add'];
    }

    /**
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function select(Request $request)
    {
        $user_id = $request->get('user_id'); //用户ID
        $select = $request->get('select'); //
        $rec_id = $request->get('rec_id', null); //购物车id
        if(is_array($rec_id)){
            $is_update = Cart::whereIn('rec_id', $rec_id)->where('user_id', $user_id)->update(['is_select' => $select]);
            if ($is_update) {
                return $this->success(null, '修改成功！');
            } else {
                return $this->error(null, '修改失败！');
            }
        }
        if ($rec_id) {//修改单品
            $is_update = Cart::where('rec_id', $rec_id)->where('user_id', $user_id)->update(['is_select' => $select]);
            if ($is_update) {
                return $this->success(null, '修改成功！');
            } else {
                return $this->error(null, '修改失败！');
            }
        } else {
            $is_update = Cart::where('user_id', $user_id)->update(['is_select' => $select]);
            if ($is_update) {
                return $this->success(null, '修改成功！');
            } else {
                return $this->error(null, '修改失败！');
            }
        }

    }
    /*public function selectx(Request $request){
        $rec_id = $request->get('rec_id', null); //购物车id
        $user_id = $request->get('user_id'); //用户ID
        foreach ($rec_id as $k=>$v){

            Cart::where('rec_id', $v)->where('user_id', $user_id)->update(['is_select' => 1]);
        }
        return $this->success(null, '成功！');
    }*/
    public function delete(Request $request)
    {
        $rec_id = $request->get('rec_id'); //购物车id
        $user_id = $request->get('user_id'); //用户ID
        $delete = Cart::where('rec_id', $rec_id)->where('user_id', $user_id)->delete();
        if ($delete) {
            return $this->success(null, '删除成功!');
        } else {
            return $this->error(null, '删除失败!');
        }
    }
    /**
     * 修改购物车单个商品数量
     *
     * @param \Illuminate\Http\Request $request
     * @return type  json
     */
    public function update(Request $request)
    {
        $rec_id = $request->get('rec_id'); //购物车id
        $goods_number = $request->get('number'); //购买数量
        $goods_id = $request->get('goods_id'); //商品id
        $user_id = $request->get('user_id'); //用户ID
        $cart = Cart::find($rec_id);
        if (empty($cart)) {
            return $this->error(null, '购物车无此商品');
        }
        // 限购一份
//         if($goods_id==101||$goods_id==98||$goods_id==99||$goods_id==100||$goods_id==102) {
//            if ($goods_number > 1) {
//                return $this->error(null, '限购商品只能够买一份！');
//            }
//        }
        //每天限购一个
        if($goods_id==166||$goods_id==167||$goods_id==168||$goods_id==169||$goods_id==170||$goods_id==171){
            if($goods_number > 1){
                return $this->error(0, "限购商品每日只能购买一份！");
            }
            $start_time=date("Y-m-d H:i:s",mktime(0,0,0,date('m'),date('d'),date('Y')));
            $end_time=date("Y-m-d H:i:s",mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1);
            $order = DB::table('woke_order_info')->select('order_id')->where('user_id', $user_id)->where('pay_status', 2)->where('pay_time', '>=', strtotime($start_time))->where('pay_time', '<=', strtotime($end_time))->get();
            foreach($order as $key=>$value) {
                $goods = DB::table('woke_order_goods')->select('goods_id')->where('order_id', $value->order_id)->get();
                foreach( $goods as $val ) {
                    if($val->goods_id==166||$val->goods_id==167||$val->goods_id==168||$val->goods_id==169||$val->goods_id==170||$val->goods_id==171) {
                      return $this->error(['goods_number'=>$goods_number], "限购商品每日只能购买一份！");
                    }
                }
            }
        }
        $goods_info = $this->goodCheck($goods_id, $user_id,$goods_number, $cart->goods_attr_id, 'update');
        if ($goods_info['status']) {//状态
            return $this->error(null, $goods_info['info']);
        }
        //修改购物车数量
        $cart->goods_number = $goods_number;
        $cart->save();
        $result['goods_number'] = $goods_number;
        return $this->success($result, '数量修改成功');
    }


    /**
     * 添加商品到购物车
     * @param Request $request
     */
    public function add(Request $request)
    {
        $user_id = $request->get('user_id'); //用户ID
        $goods_number = $request->get('number', 1); //购买数量
        $goods_id = $request->get('goods_id'); //商品id
        $goods_attr_id = $request->get('goods_attr_id', 0); //商品id
        if($goods_id==166||$goods_id==167||$goods_id==168||$goods_id==169||$goods_id==170||$goods_id==171){
            $start_time=date("Y-m-d H:i:s",mktime(0,0,0,date('m'),date('d'),date('Y')));
            $end_time=date("Y-m-d H:i:s",mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1);
            $order = DB::table('woke_order_info')->select('order_id')->where('user_id', $user_id)->where('pay_status', 2)->where('pay_time', '>=', strtotime($start_time))->where('pay_time', '<=', strtotime($end_time))->get();
            foreach($order as $key=>$value) {
                $goods = DB::table('woke_order_goods')->select('goods_id')->where('order_id', $value->order_id)->get();
                foreach( $goods as $val ) {
                    if($val->goods_id==166||$val->goods_id==167||$val->goods_id==168||$val->goods_id==169||$val->goods_id==170||$val->goods_id==171) {
                            return $this->error(null, '限购商品每日只能购买一份!');
                    }
                }
            }
        }
        if($goods_id==166||$goods_id==167||$goods_id==168||$goods_id==169||$goods_id==170||$goods_id==171){
            $cart = Cart::where('user_id',$user_id)->where('goods_id',$goods_id)->first();
            if(!empty($cart)&&$cart->goods_number==1){
                return $this->error(null, '限购商品每日只能购买一份!');
            }
        }
        //营销活动
         if($goods_id==101||$goods_id==98||$goods_id==99||$goods_id==100||$goods_id==102){
             //查购物车是否已经有了
             $cart = Cart::where('user_id',$user_id)->where('goods_id',$goods_id)->first();
             if(!empty($cart)&&$cart->goods_number==1){
                 return $this->error(null, '商品已放入购物车!');
             }
            if($goods_number>1){
                return $this->error(null, '会员专享商品只能够买一份！');
            }
            $u = User::select('user_rank','user_id','parent_id')->where('user_id',$user_id)->first();
            if($goods_id==101&&$u->user_rank!=1){
                return $this->error(null, '会员专享商品只有专员级用户能购买且只能够买一份！');
            }
            if($goods_id==98&&$u->user_rank!=2){
                return $this->error(null, '会员专享商品只有高专级用户能购买且只能够买一份！');
            }
            if($goods_id==99&&$u->user_rank!=3){
                return $this->error(null, '会员专享商品只有经理级用户能购买且只能够买一份！');
            }
            if($goods_id==100&&$u->user_rank<4){
                return $this->error(null, '会员专享商品只有总监级用户能购买且只能够买一份！');
            }
           // $order = OrderInfo::select('order_id')->where('pay_status',2)->where('user_id', $user_id)->get();
            //查询一下这个人历史购买
        }
        $goods_info = $this->goodCheck($goods_id, $user_id, $goods_number, $goods_attr_id);
        if (!empty($goods_info['status'])) {//状态
            return $this->error(null, $goods_info['info']);
        }
        //3.检查购物车是否有
        $query = Cart::where('user_id', $user_id)->where('goods_id', $goods_id);
        if (!empty($goods_attr_id)) {
            $query->where('goods_attr_id', $goods_attr_id);
        }
        $cart = $query->first();
        if (!empty($cart)) {//有了就加数量
            $newNumber = $cart->goods_number + $goods_number;
            $cart->goods_number = $newNumber;
            $cart->is_select=1;
            $cart->save();
        } else {//没有就添加到购物车
            if (!empty($goods_attr_id)) {
                $attr = DB::table('woke_goods_attr')->select('attr_name', 'attr_value')->where('id', $goods_attr_id)->first();
                $goods_attr = $attr->attr_name . '[' . $attr->attr_value . ']';
               // $goods_attr_id = 1;
            } else {
                $goods_attr = '';
                $goods_attr_id = 0;
            }
            $add_goods = [
                'user_id' => $user_id,
                'session_id' => '',
                'goods_id' => $goods_id,
                'goods_sn' => $goods_info->goods_sn,
                'product_id' => 0,
                'goods_name' => $goods_info->goods_name,
                'market_price' => $goods_info->market_price,
                'goods_price' => $goods_info->shop_price,
                'goods_number' => $goods_number,
                'goods_attr' => $goods_attr,
                'goods_attr_id' => $goods_attr_id,
                'is_real' => $goods_info->is_real,
                'extension_code' => $goods_info->extension_code,
                'is_gift' => 0,
                'is_shipping' => $goods_info->is_shipping,
                'add_time' => time(),
                'rec_type' => 0,
                'is_select'=>1
            ];
            DB::table('woke_cart')->insert($add_goods);
        }
        return $this->success(null, '添加成功');
    }

    /**
     * 检查商品状态
     */
    protected function goodCheck($goods_id, $user_id, $goods_number, $goods_attr_id, $type = 'add')
    {
        $goods = Goods::where('goods_id', $goods_id)->first();
        //0.检查商品是否存在
        if (empty($goods)) {
            return ['status' => 1, 'info' => '您选择的商品不存在/下架！'];
        }
        //2.检查是否在卖／删除
        if ($goods->is_delete || $goods->is_on_sale == 0) {
            return ['status' => 1, 'info' => '您选择的' . $goods->goods_name . '物品已经下架，请重新选择购买'];
        }
        //1.检查库存
        if (!empty($goods_attr_id)) {//检查规格商品库存
            $attr = DB::table('woke_goods_attr')->where('id', $goods_attr_id)->first();
            if (empty($attr)) {
                return ['status' => 1, 'info' => '您选择的' . $goods->goods_name . '物品规格出错！请从新选择'];
            }
            if (!empty($attr) && $goods_number > $attr->goods_number) {
                return ['status' => 1, 'info' => '您选择的' . $goods->goods_name . '物品库存不足，请重新选择购买'];
            }
        } else {//检查普通商品库存
            if (!empty($goods) && $goods_number > $goods->goods_number) {
                return ['status' => 1, 'info' => '您选择的' . $goods->goods_name . '物品库存不足，请重新选择购买'];
            }
        }
        //1.1检查购物里的库存＋现在购买数大于库存 只在添加购物车时判断
        if ($type == 'add') {//添加购物车
            $query = DB::table('woke_cart')->where('user_id', $user_id)->where('goods_id', $goods_id);
            if (!empty($goods_attr_id)) {
                $query->where('goods_attr_id', $goods_attr_id);
            }
            $cart_data = $query->first();
            if (!empty($cart_data)) {
                $number = $goods_number + $cart_data->goods_number;
                if ($number > $goods->goods_number) {
                    return ['status' => 1, 'info' => '您选择的' . $goods->goods_name . '物品库存不足了，请重新选择购买'];
                }
            }
        }
        return $goods;
    }

    /**
     * 执行接口
     * @return array
     */
    public function index(Request $request)
    {
        $user_id = $request->get('user_id', null);
        // 查询购物车信息
        $cart_data = Cart::with('goods')->where('user_id', $user_id)->orderBy('rec_id', 'asc')->get();
        $result['market_price']=0.00;
        $result['goods_price']=0.00;
        $result['total_count']=0.00;
        foreach ($cart_data as $k=>$vv) {
            if(!empty($vv->goods)){
                //每次看购物车都更新商品为最新价格
                $v=$vv->goods;
                if($vv->goods_attr_id){
                    // 更新Sku价格
                    $attr=DB::table('woke_goods_attr')->where('id', $vv->goods_attr_id)->first();
                    $goods_number=$attr->goods_number;
                    $good_price=$attr->shop_price;
                    $market_price=$attr->shop_price;;
                }else{
                    // 更新商品价格
                    $goods_number=$v->goods_number;
                    $good_price=$v->shop_price;
                    $market_price=$v->shop_price;;
                }
                $result['list'][$k]['rec_id']=$vv->rec_id;
                $result['list'][$k]['goods_thumb']=$v->goods_thumb;
                $result['list'][$k]['market_price']=$market_price;
                $result['list'][$k]['shop_price']=$good_price;
                $result['list'][$k]['goods_name']=$v->goods_name;
                $result['list'][$k]['goods_sn']=$v->goods_sn;
                $result['list'][$k]['goods_id']=$v->goods_id;
                $result['list'][$k]['is_real']=$v->is_real;
                $result['list'][$k]['is_delete']=$v->is_delete;
                $result['list'][$k]['is_on_sale']=$v->is_on_sale;
                $result['list'][$k]['goods_attr']=$vv->goods_attr;
                $result['list'][$k]['is_select']=$vv->is_select;
                $result['list'][$k]['goods_number']=$vv->goods_number;
                Cart::where('rec_id', $vv->rec_id)->update(['goods_price' => $good_price, 'market_price' => $market_price,'goods_name'=>$v->goods_name]);
                //删除  下架  购物车数量超过库存
                if ($v->is_delete || $v->is_on_sale == 0 || $goods_number < $vv->goods_number) {
                    $result['list'][$k]['status']=1;
                } else {
                    //1.计算选中的价格
                    if($vv->is_select==1){
                        $result['market_price']= $result['market_price']+$market_price*$vv->goods_number;
                        $result['goods_price']=$result['goods_price']+$good_price*$vv->goods_number;
                        $result['total_count']= $result['total_count']+1;
                    }
                    $result['list'][$k]['status']=0;
                }
            }
        }
        if (!count($result)) {
            return $this->error($cart_data, '购物车无商品');
        } else {
             $result['market_price']=$this->getFormatPrice($result['goods_price']);
             $result['goods_price']=$this->getFormatPrice($result['goods_price']);
            return $this->success($result, '成功');
        }

    }

}
