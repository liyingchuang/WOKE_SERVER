<?php

namespace App\Http\Controllers\client\Response\V2;

use App\Category;
use App\CollectGoods;
use App\Http\Controllers\client\Response\BaseResponse;
use App\Http\Controllers\client\Response\InterfaceResponse;
use Illuminate\Http\Request;
use App\Goods;
use App\Http\Requests;


class GoodController extends BaseResponse implements InterfaceResponse
{
    public function __construct()
    {
        $this->except = ['index','category','search','android'];
    }

    /**
     *  商品详情
     *
     * @return \Illuminate\Http\Response
     */
    public function android(Request $request)
    {

        $user_id = $request->get('user_id');
        $goods_id = $request->get('goods_id');
        $info = Goods::with('attr', 'item', 'gallery','store')->select('goods_id','goods_sn',"goods_name",'is_real','integral',"goods_name_style","click_count","goods_number","goods_weight","goods_texture","goods_norms","goods_author","goods_area", "goods_style","market_price", "shop_price","goods_desc","goods_thumb","original_img")->where('goods_id', $goods_id)->where('is_delete', 0)
            ->where('is_on_sale', 1)->where('goods_number','>',0)->first();
        if(empty($info)){
            return $this->error(null,'商品不存在!或已经下架');
        }
/*
        if(!empty($info->attr)){
            foreach ($info->attr as $k=>$v){
                $list["$v->attr_name"][]=$v;
            }
            unset($info->attr);
            $info->attr=$list;
        }else{
            $info->attr=[];
        }
*/
        if(!empty($user_id)){
            $collectGoods=CollectGoods::where('user_id',$user_id)->where('goods_id',$goods_id)->first();
            if(!empty($collectGoods)&&$collectGoods->is_attention==1){
                $info->collect=1;
            }else{
                $info->collect=0;
            }
        }else{
            $info->collect=0;
        }

        $info->tel="4000191818";
        return $this->success($info,'');
    }
    /**
     *  商品详情
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user_id = $request->get('user_id');
        $goods_id = $request->get('goods_id');
        $info = Goods::with('attr', 'item', 'gallery','store')->select('goods_id','goods_sn',"goods_name",'is_real','integral',"goods_name_style","click_count","goods_number","goods_weight","goods_texture","goods_norms","goods_author","goods_area", "goods_style","market_price", "shop_price","goods_desc","goods_thumb","original_img")->where('goods_id', $goods_id)->where('is_delete', 0)
            ->where('is_on_sale', 1)->where('goods_number','>',0)->first();
        if(empty($info)){
            return $this->error(null,'商品不存在!或已经下架');
        }
        if(!empty($info->attr)){
            $list=[];
            foreach ($info->attr as $k=>$v){
              $list["$v->attr_name"][]=$v;
            }
            unset($info->attr);
            if(empty($list)){
                $info->attr=(object)null;
            }else{
                $info->attr=$list;
            }
        }else{
            $info->attr=(object)null;
        }
        if(!empty($user_id)){
            $collectGoods=CollectGoods::where('user_id',$user_id)->where('goods_id',$goods_id)->first();
            if(!empty($collectGoods)&&$collectGoods->is_attention==1){
                $info->collect=1;
            }else{
                $info->collect=0;
            }
        }else{
            $info->collect=0;
        }

        $info->tel="4000191818";
        return $this->success($info,'');
    }

    /**
     * 商品分类
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function category(Request $request){
       $cat_id = $request->get('cat_id');
       $cat=Category::where('cat_id',$cat_id)->first();
        if(empty($cat)){
            $list['list']= Goods::select('goods_id','goods_sn',"goods_name","goods_name_style",'is_real','integral',"click_count","goods_number","market_price", "shop_price","goods_thumb")->where('is_delete', 0)
                ->where('is_on_sale', 1)->orderBy('sort_order', 'asc')->orderBy('goods_id', 'desc')
                ->paginate(10)->toArray()['data'];
            $cat['cat_name']="全部商品";
            $list['category']=$cat;
        }else{
            $list['list']= Goods::select('goods_id','goods_sn',"goods_name","goods_name_style","click_count",'is_real','integral',"goods_number","market_price", "shop_price","goods_thumb")->where('cat_id', $cat_id)->where('is_delete', 0)
                ->where('is_on_sale', 1)->orderBy('sort_order', 'asc')->orderBy('goods_id', 'desc')
                ->paginate(10)->toArray()['data'];
            $list['category']=$cat;
        }
        return $this->success($list,'');
    }
    /**
     * 商品搜索
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request){
        $keyword = $request->get('keyword');
        $list = Goods::select('goods_id','goods_sn',"goods_name","goods_name_style","click_count","goods_number",'is_real','integral',"market_price", "shop_price","goods_thumb")->where('goods_name', 'like', "%$keyword%")->where('is_delete', 0)
            ->where('is_on_sale', 1)->orderBy('sort_order', 'asc')->orderBy('goods_id', 'desc')
            ->paginate(10)->toArray()['data'];
        return $this->success($list,'');
    }

}
