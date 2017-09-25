<?php

namespace App\Http\Controllers\client\Response\V1;

use App\Advertisement;
use App\Goods;
use App\Http\Controllers\client\Response\BaseResponse;
use App\Http\Controllers\client\Response\InterfaceResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;


class HomeController extends BaseResponse implements InterfaceResponse
{
    public function __construct()
    {
        $this->except = ['index','version','hot'];
    }

    /**
     * 商城首页
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $result = [];
        $time = time();
        //banner
        $banner = Advertisement::select('id', 'ad_name', 'ad_link', 'ad_file')->where('advertisement_category_id', 1)->where('start_time', '<=', $time)->where('end_time', '>=', $time)->where('enabled', 1)->orderBy('updated_at', 'desc')->get();
        $result['banners'] = $banner;
        //news
   /*     $result['news'] = DB::table('woke_goods')->select('goods_id', 'goods_name', 'shop_price', 'promote_price', 'market_price', 'goods_thumb')
            ->where('is_new', 1)
            ->where('is_delete', 0)
            ->where('is_on_sale', 1)->orderBy('add_time', 'desc')->take(4)->skip(0)->get();*/
        //f0
        $result['f0']['ads'] = Advertisement::select('id', 'ad_name', 'ad_link', 'ad_file')->where('advertisement_category_id', 8)->where('start_time', '<=', $time)->where('end_time', '>=', $time)->where('enabled', 1)->first();
        $result['f0']['images'] = Advertisement::select('id', 'ad_name', 'ad_link', 'ad_file')->where('advertisement_category_id', 9)->where('start_time', '<=', $time)->where('end_time', '>=', $time)->where('enabled', 1)->first();
        $result['f0']['goods'] =Goods::select('goods_id', 'goods_name', 'shop_price', 'promote_price', 'market_price', 'goods_thumb' ,'is_real','integral')
            ->whereIn('cat_id', [1,3])//
            ->where('is_delete', 0)
            ->where('is_best', 1)
            ->where('is_on_sale', 1)->orderBy('add_time', 'desc')->take(4)->skip(0)->get();
        //f1
        $result['f1']['ads'] = Advertisement::select('id', 'ad_name', 'ad_link', 'ad_file')->where('advertisement_category_id', 2)->where('start_time', '<=', $time)->where('end_time', '>=', $time)->where('enabled', 1)->first();
        $result['f1']['images'] = Advertisement::select('id', 'ad_name', 'ad_link', 'ad_file')->where('advertisement_category_id', 6)->where('start_time', '<=', $time)->where('end_time', '>=', $time)->where('enabled', 1)->first();
        $result['f1']['goods'] =Goods::select('goods_id', 'goods_name', 'shop_price', 'promote_price', 'market_price', 'goods_thumb' ,'is_real','integral')
            ->where('cat_id', 6)//
            ->where('is_delete', 0)
            ->where('is_best', 1)
            ->where('is_on_sale', 1)->orderBy('add_time', 'desc')->take(4)->skip(0)->get();

/*        $result['f1']['goods2'] = DB::table('woke_goods')->select('goods_id', 'goods_name', 'shop_price', 'promote_price', 'market_price', 'goods_thumb')
            ->where('cat_id', 3)//白酒id
            ->where('is_delete', 0)
            ->where('is_on_sale', 1)->orderBy('add_time', 'desc')->take(3)->skip(0)->get();*/
        //f2
        $result['f2']['ads'] = Advertisement::select('id', 'ad_name', 'ad_link', 'ad_file')->where('advertisement_category_id', 3)->where('start_time', '<=', $time)->where('end_time', '>=', $time)->where('enabled', 1)->first();
        $result['f2']['images'] = Advertisement::select('id', 'ad_name', 'ad_link', 'ad_file')->where('advertisement_category_id', 5)->where('start_time', '<=', $time)->where('end_time', '>=', $time)->where('enabled', 1)->first();
        $result['f2']['goods'] =Goods::select('goods_id', 'goods_name', 'shop_price', 'promote_price', 'market_price', 'goods_thumb' ,'is_real','integral')
            ->where('cat_id', 4)//蔬菜id
            ->where('is_delete', 0)
            ->where('is_best', 1)
            ->where('is_on_sale', 1)->orderBy('add_time', 'desc')->take(4)->skip(0)->get();
        //f3
        $result['f3']['ads'] = Advertisement::select('id', 'ad_name', 'ad_link', 'ad_file')->where('advertisement_category_id', 4)->where('start_time', '<=', $time)->where('end_time', '>=', $time)->where('enabled', 1)->first();
     /*   $result['f3']['goods'] = DB::table('woke_goods')->select('goods_id', 'goods_name', 'shop_price', 'promote_price', 'market_price', 'goods_thumb')
            ->whereNotIn('cat_id', [1, 2, 3])//其他id
            ->where('is_delete', 0)
            ->where('is_on_sale', 1)->orderBy('add_time', 'desc')->take(4)->skip(0)->get();*/
        return $this->success($result, '');
    }

    /**
     * 检查版本
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function version(Request $request) {
        $type = $request->get('type');
        if ($type==='ios') {
            // compeller
            $ios = DB::table('version')->select('version_number')->where('system', 1)->first();
            $data=['forceVmsg' => 'other', 'V' => $ios->version_number,'msg'=>''];
            return $this->success($data, '查询成功');
        }
        if ($type==='android') {
            $android = DB::table('version')->where('system', 2)->first();
            $data=['VersionCode' => $android->version_number,'update'=>$android->updates, 'VersionName' => $android->version_name , 'ApkUrl' => $android->appurl, 'UpdateMessage' => "修复若干BUG\n* 若安装失败联系客服4000191818"];
            return $this->success($data, '查询成功');
        }
        return $this->error(null, '非法请求！');
    }

    /**
     * 推荐商品
     * @param Request $request
     */
    public function hot(Request $request){
               $list = Goods::select('goods_id','goods_sn',"goods_name","goods_name_style","click_count","goods_number",'is_real','integral',"market_price", "shop_price","goods_thumb")->where('is_delete', 0)
                   ->where('is_on_sale', 1)->where('is_hot', 1)->orderBy('sort_order', 'asc')->orderBy('goods_id', 'desc')
                   ->paginate(3)->toArray()['data'];
        return $this->success($list,'');
    }
}
