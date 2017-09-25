<?php

namespace App\Http\Controllers\client\Response\V2;

use App\Goods;
use App\GroupOpen;
use App\Http\Controllers\client\Response\BaseResponse;
use App\Http\Controllers\client\Response\InterfaceResponse;
use Illuminate\Http\Request;
use App\Supplier;
use App\GroupGoods;
use App\CollectSupplier;
use App\Category;

class SuppliersController extends BaseResponse implements InterfaceResponse
{
    public function __construct()
    {
        $this->except = ['index', 'create', 'category'];
    }

    /**
     *
     * 查看店铺
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request){
        $user_id = $request->get('user_id', null); //用户ID 可选
        $cat_id = $request->get('cat_id'); //分类ID 可选
        $supplier_id = $request->get('supplier_id'); //店铺ID 必须
        $list['collect'] = 0;
        $data = Supplier::with('goods')->select('supplier_id', 'supplier_name', 'tel', 'supplier_img', 'content')->where('supplier_id', $supplier_id)->first();
        $list['supplier_id'] = $data->supplier_id;
        $list['supplier_name'] = $data->supplier_name;
        $list['tel'] = $data->tel;
        $list['sale_number'] = is_null(GroupOpen::whereSupplier_id($supplier_id)->whereGroup_status(1)->sum('have'))?0:GroupOpen::whereSupplier_id($supplier_id)->whereGroup_status(1)->sum('have');
        $list['supplier_img'] = is_null($data->supplier_img)?"":$data->supplier_img;
        $list['content'] = $data->content;
        $list['shop_number'] = (string)Goods::where('supplier_id', $supplier_id)->where('is_on_sale', 1)->where('is_delete', 0)->count();
        //todo 检测是否收藏
        if(!empty($user_id)){
            $collect = CollectSupplier::whereUser_id($user_id)->whereSupplier_id($supplier_id)->first();
            if(!empty($collect)&&$collect->is_attention == 1){
                $list['collect'] = 1;
            }else{
                $list['collect'] = 0;
            }
        }
        // todo 店铺商品分页
        $cat= Category::where('cat_id',$cat_id)->first();
        if(empty($cat)){
            $list['list']= Goods::select('goods_id','goods_sn',"goods_name","goods_name_style",'is_real','integral',"click_count","goods_number","market_price", "shop_price","goods_thumb")->where('is_delete', 0)
                ->where('is_on_sale', 1)->orderBy('sort_order', 'asc')->where('supplier_id', $supplier_id)->orderBy('goods_id', 'desc')
                ->paginate(10)->toArray()['data'];
            $cat['cat_name']="全部商品";
            $list['category']=$cat;
        }else{
            $list['list']= Goods::select('goods_id','goods_sn',"goods_name","goods_name_style","click_count",'is_real','integral',"goods_number","market_price", "shop_price","goods_thumb")->where('cat_id', $cat_id)->where('supplier_id', $supplier_id)->where('is_delete', 0)
                ->where('is_on_sale', 1)->orderBy('sort_order', 'asc')->orderBy('goods_id', 'desc')
                ->paginate(10)->toArray()['data'];
            $list['category']=$cat;
        }
        // todo 商品销售数量
        foreach($list['list'] as $k=>$v){
            $list['list'][$k]['ex_have'] = GroupGoods::where('goods_id', $v['goods_id'])->where('supplier_id', $supplier_id)->pluck('ex_have');
            if($list['list'][$k]['ex_have'] == null){
                $list['list'][$k]['ex_have'] = 0;
            }
        }
        return $this->success($list,'店铺信息');
    }

    /**
     *
     * 收藏店铺
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request){
        $user_id = $request->get('user_id');  //用户ID 必须
        $supplier_id = $request->get('supplier_id'); //店铺ID 必须
        $collect = CollectSupplier::where('user_id', $user_id)->where('supplier_id', $supplier_id)->first();
        $is_collent = 0;
        if(!empty($collect)){
            if($collect->is_attention == 1){
                CollectSupplier::where('user_id', $user_id)->where('supplier_id', $supplier_id)->update(['is_attention'=>0]);
                $is_collent = 0;
            }else{
                CollectSupplier::where('user_id', $user_id)->where('supplier_id', $supplier_id)->update(['is_attention'=>1]);
                $is_collent = 1;
            }
        }else{
            CollectSupplier::create(['user_id'=>$user_id, 'supplier_id'=>$supplier_id, 'add_time'=>time(), 'is_attention'=>1]);
            $is_collent = 1;
        }
        return $this->success(['is_collent'=>$is_collent],'');
    }
}
