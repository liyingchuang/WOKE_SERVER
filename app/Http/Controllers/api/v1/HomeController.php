<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AdvertisementCategory;
use Illuminate\Support\Facades\DB;
use App\Advertisement;

class HomeController extends Controller {

    /**
     * app 首页接口
     * @param \Illuminate\Http\Request $request
     */
    public function getIndex(Request $request) {
        $result = [];
        $time=time();
        //banner 图片 f1
        $b = 5;
        $banner=Advertisement::select('id','ad_name','ad_link','ad_file')->where('advertisement_category_id',$b)->where('start_time','<=',$time)->where('end_time','>=',$time)->where('enabled', 1)->orderBy('updated_at', 'desc')->take(6)->skip(0)->get();
        $result['banners'] =$banner;
        //广告区
        $a = 6;
        $result['ads'] =Advertisement::select('id','ad_name','ad_link','ad_file')->where('advertisement_category_id',$a)->where('start_time','<=',$time)->where('end_time','>=',$time)->where('enabled', 1)->orderBy('updated_at', 'desc')->take(3)->skip(0)->get();
        //热门店铺
        $s=8;
        $result['stores'] =Advertisement::select('id','ad_name','ad_link','ad_file')->where('advertisement_category_id',$s)->where('start_time','<=',$time)->where('end_time','>=',$time)->where('enabled', 1)->orderBy('updated_at', 'desc')->take(4)->skip(0)->get();
        //新品
        $new = DB::table('ecs_goods')
                        ->select('goods_id', 'goods_name', 'shop_price', 'promote_price', 'market_price', 'goods_thumb')
                        ->where('is_delete', 0)
                        ->where('is_on_sale', 1)
                        ->where('is_new', 1)
                        ->orderBy('sort_order', 'asc')
                        ->orderBy('last_update', 'desc')
                        ->take(10)->skip(0)->get();
        
        $result['news'] = $new;
        //更多商品上面广告
        $m=7;
        $result['adsbig']=Advertisement::select('id','ad_name','ad_link','ad_file')->where('advertisement_category_id',$m)->where('start_time','<=',$time)->where('end_time','>=',$time)->where('enabled', 1)->orderBy('updated_at', 'desc')->take(2)->skip(0)->get();
        //更多商品
        $goods = DB::table('ecs_goods')
                        ->select('goods_id', 'goods_name', 'shop_price', 'promote_price', 'market_price', 'goods_thumb')
                        ->where('is_delete', 0)
                        ->where('is_on_sale', 1)
                        ->where('sort_order', '>', 50)
                        ->where('sort_order', '<', 110)
                        ->orderBy('sort_order', 'asc')
                        ->orderBy('add_time', 'desc')
                        ->paginate(10)->toArray();
        $result['goods'] = $goods['data'];
        return $this->success($result, '');
    }

    /**
     * 启动接口
     * @param \Illuminate\Http\Request $request
     */
    public function getStart(Request $request) {
        $result=[];
        $time=time();
        $category_id = 1;
        $ads=Advertisement::select('id','ad_name','ad_link','ad_file')->where('advertisement_category_id',$category_id)->where('start_time','<=',$time)->where('end_time','>=',$time)->where('enabled', 1)->orderBy('updated_at', 'desc')->first();
        $result['show']=$ads;
        $list=Advertisement::select('id','ad_name','ad_link','ad_file')->where('advertisement_category_id',$category_id)->where('start_time','>',$time)->where('enabled', 1)->orderBy('start_time', 'asc')->orderBy('updated_at', 'desc')->take(5)->skip(0)->get();
        $list[]=$ads;
        $result['list']=$list;
        return $this->success($result, '成功');
    }

}
