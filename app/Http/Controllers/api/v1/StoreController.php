<?php

namespace App\Http\Controllers\api\v1;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class StoreController extends Controller {

    /**
     * 店铺详情接口
     * @param \Illuminate\Http\Request $request
     */
    public function getShow(Request $request) {
        $supplier_id = $request->get('supplier_id', 0);
        $user_id = $request->get('user_id', 0);
        $field = $request->get('field', 'add_time');
        $order = $request->get('order', 'desc');
        $result = [];
        $supplier = DB::table('ecs_supplier')
                        ->leftJoin('ecs_supplier_info', 'ecs_supplier.supplier_id', '=', 'ecs_supplier_info.supplier_id')
                        ->select('ecs_supplier.supplier_id','ecs_supplier.user_id',  'ecs_supplier.supplier_name', 'ecs_supplier.province', 'ecs_supplier.city', 'ecs_supplier.district', 'ecs_supplier.address', 'ecs_supplier.tel', 'ecs_supplier.add_time', 'ecs_supplier_info.banner_file', 'ecs_supplier_info.logo_file','ecs_supplier_info.keyword')
                        ->where('ecs_supplier.status', 1)->where('ecs_supplier.supplier_id', $supplier_id)->first();
        if (!empty($supplier)) {
            $supplier->banner_file = $this->_url($supplier->banner_file);
            $supplier->logo_file = $this->_url($supplier->logo_file);
            $result = $supplier;
            if ($user_id) {
                $result->is_collection = $this->eheckCollection($supplier_id, $user_id);
            } else {
                $result->is_collection = 0;
            }
            if ($field == 'sales_number') {//销量
                $goods = DB::table('ecs_goods')
                                ->leftJoin('ecs_order_goods', 'ecs_goods.goods_id', '=', 'ecs_order_goods.goods_id')
                                ->select('ecs_goods.goods_id','ecs_goods.click_count','ecs_goods.shop_price','ecs_goods.add_time', 'ecs_goods.goods_name', 'ecs_goods.goods_number', 'ecs_goods.goods_sn', 'ecs_goods.shop_price', 'ecs_goods.market_price', 'ecs_goods.goods_thumb', DB::raw('SUM(ecs_order_goods.goods_number) as goods_sales'))
                                ->where('ecs_goods.is_on_sale', 1)->where('ecs_goods.is_delete', 0)->where('ecs_goods.supplier_id', $supplier_id)
                                ->groupBy('ecs_goods.goods_id')
                                ->orderBy('goods_sales', $order)
                                ->paginate(10)->toArray();
            } else {
                $goods = DB::table('ecs_goods')->select('goods_id', 'goods_name','click_count','shop_price','add_time','goods_sn', 'shop_price', 'market_price', 'goods_thumb', 'goods_number' )
                                ->where('is_on_sale', 1)->where('is_delete', 0)->where('supplier_id', $supplier_id)
                                ->orderBy($field, $order)
                                ->paginate(10)->toArray();
            }
           foreach ($goods['data'] as $k => $v) {
                $goods['data'][$k] = $v;             
                $goods['data'][$k]->add_time= date('Y-m-d H:i', $v->add_time);  
                if (isset($v->goods_sales)&&$v->goods_sales) {
                    $goods['data'][$k]->goods_sales = strval($v->goods_sales);
                } else {
                    $goods['data'][$k]->goods_sales  = '0';
                }
            }
            if (!empty($goods)) {
                $result->goods_list = $goods['data'];
            } else {
                $result->goods_list = '';
            }
            $result->users= User::select('user_id', 'user_name', 'user_rank', 'headimg')->where('user_id', $supplier->user_id)->first();
            return $this->success($result, 'ok');
        }
        return $this->error($result, '该店铺盘点中，先看看其他店铺吧!');
    }

    /**
     * 店铺详情
     * @param \Illuminate\Http\Request $request
     */
    public function getInfo(Request $request) {
        $supplier_id = $request->get('supplier_id', 0);
        $user_id = $request->get('user_id', 0);
        $supplier = DB::table('ecs_supplier')
                        ->leftJoin('ecs_supplier_info', 'ecs_supplier.supplier_id', '=', 'ecs_supplier_info.supplier_id')
                        ->select('ecs_supplier.supplier_id', 'ecs_supplier.supplier_name', 'ecs_supplier.province', 'ecs_supplier.city', 'ecs_supplier.district', 'ecs_supplier.address', 'ecs_supplier.tel', 'ecs_supplier.add_time', 'ecs_supplier_info.banner_file', 'ecs_supplier_info.logo_file', 'ecs_supplier_info.supplier_desc','ecs_supplier_info.keyword','ecs_supplier_info.qr_code')
                        ->where('ecs_supplier.status', 1)->where('ecs_supplier.supplier_id', $supplier_id)->first();
        if (!empty($supplier)) {
            $supplier->banner_file = $this->_url($supplier->banner_file);
            $supplier->logo_file = $this->_url($supplier->logo_file);
            $supplier->qr_code = $this->_url($supplier->qr_code);
            $result = $supplier;
            $result->add_time = date('Y-m-d H:i', $supplier->add_time + 28800);
            $is_array = explode(",", $supplier->supplier_desc);
            if (is_array($is_array) && $is_array[0]) {
                $is_array = array_filter($is_array);
                foreach ($is_array as $k => $value) {
                    $is_array[$k] = $this->_url($value);
                }
                $result->supplier_desc = $is_array;
            } else {
                $result->supplier_desc = [];
            }
            $result->region = $this->getAddress($supplier->province, $supplier->city, $supplier->district);
            $result->address = $supplier->address;
            if ($user_id) {
                $result->is_collection = $this->eheckCollection($supplier_id, $user_id);
            } else {
                $result->is_collection = 0;
            }
            return $this->success($supplier, 'ok');
        }
        return $this->error(null, '该店铺盘点中，先看看其他店铺吧!');
    }

    /**
     * 用户收藏列表
     * @param \Illuminate\Http\Request $request
     */
    public function getCollection(Request $request) {
        $user_id = $request->get('user_id', 0);
        $in = DB::table('ecs_supplier_guanzhu')->select('supplierid')->where('userid', $user_id)->get();
        $whereIn = [];
        foreach ($in as $value) {
            $whereIn[] = $value->supplierid;
        }
        $list = DB::table('ecs_supplier')
                ->leftJoin('ecs_supplier_info', 'ecs_supplier.supplier_id', '=', 'ecs_supplier_info.supplier_id')
                ->select('ecs_supplier.supplier_id', 'ecs_supplier.supplier_name', 'ecs_supplier.tel', 'ecs_supplier_info.keyword', 'ecs_supplier_info.logo_file')
                ->where('ecs_supplier.status', 1)
                ->whereIn('ecs_supplier.supplier_id', $whereIn)
                ->paginate(5);
        $result = $this->get_list($list, $user_id);
        return $this->success($result, '列表 ok');
    }

    /**
     * 搜索和店铺列表
     *
     * @return \Illuminate\Http\Response
     */
    public function getSearch(Request $request) {
        $keyword = trim($request->get('keyword', null));
        $user_id = $request->get('user_id', 0);
        if ($keyword) {//搜索
            $list = DB::table('ecs_supplier')
                    ->leftJoin('ecs_supplier_info', 'ecs_supplier.supplier_id', '=', 'ecs_supplier_info.supplier_id')
                    ->select('ecs_supplier.supplier_id', 'ecs_supplier.supplier_name', 'ecs_supplier.tel', 'ecs_supplier_info.keyword', 'ecs_supplier_info.logo_file')
                    ->where('ecs_supplier.status', 1)
                   // ->where('ecs_supplier.enabled', 1)
                    ->where(function ($q) use($keyword){
                        $q->where('ecs_supplier.company_name', 'like', "%$keyword%")->orWhere('ecs_supplier_info.keyword', 'like', "%$keyword%");
                    })->orderBy('ecs_supplier.supple_sort_order', 'asc')
                    ->paginate(5);
            $result = $this->get_list($list, $user_id);
            return $this->success($result, $keyword . '｜关键词搜索 ok');
        } else {//列表
            $list = DB::table('ecs_supplier')
                    ->leftJoin('ecs_supplier_info', 'ecs_supplier.supplier_id', '=', 'ecs_supplier_info.supplier_id')
                    ->select('ecs_supplier.supplier_id', 'ecs_supplier.supple_sort_order', 'ecs_supplier.supplier_name', 'ecs_supplier.tel', 'ecs_supplier_info.keyword', 'ecs_supplier_info.logo_file')
                    ->where('ecs_supplier.status', 1)
                    ->where('ecs_supplier.enabled', 1)
                    ->orderBy('ecs_supplier.supple_sort_order', 'asc')
                    ->orderBy('ecs_supplier.supplier_id', 'desc')
                    ->paginate(5);
            $result = $this->get_list($list, $user_id);
            return $this->success($result, '列表 ok');
        }
    }

    /**
     * 店铺分类
     * @param \Illuminate\Http\Request $request
     */
    public function getCategory(Request $request) {
        $supplier_id = $request->get('supplier_id');
        $list = DB::table('ecs_supplier_category')->select('cat_id', 'cat_name')->where('is_show', 1)->where('supplier_id', $supplier_id)->orderBy('sort_order', 'asc')->orderBy('cat_id', 'asc')->get();
        return $this->success($list, '');
    }

    /**
     * 商品搜索
     * @param \Illuminate\Http\Request $request
     */
    public function postSearch(Request $request) {
        $supplier_id = $request->get('supplier_id');
        $keyword = trim($request->get('keyword', null));
        $result=[];
        $goods=DB::table('ecs_goods')
                        ->select('goods_id', 'goods_name', 'goods_sn', 'shop_price', 'market_price', 'goods_thumb')
                        ->where('supplier_id', $supplier_id)->where('goods_name', 'like', "%$keyword%")->orderBy('goods_id', 'desc')->paginate(5)->toArray();
         if (!empty($goods)) {
                $result= $goods['data'];
            } else {
                $result = '';
            }
        return $this->success($result, '');
    }

    /**
     * 根据商店列表获取商品
     * @param type $list
     * @param type $user_id
     */
    private function get_list($list = [], $user_id = 0) {
        $result = [];
        foreach ($list as $k => $value) {
            //$value->banner_file=url($value->banner_file);
            $value->logo_file = $this->_url($value->logo_file);
            $result[$k] = $value;
            if ($user_id) {//收藏检测
                $result[$k]->is_collection = $this->eheckCollection($value->supplier_id, $user_id);
            } else {
                $result[$k]->is_collection = 0;
            }
            $goods = DB::table('ecs_goods')->select('goods_id', 'goods_name', 'goods_sn', 'shop_price', 'market_price', 'goods_thumb')
                            ->where('supplier_id', $value->supplier_id)->take(3)->where('is_on_sale', 1)->where('is_delete', 0)->orderBy('goods_id', 'desc')->get();
            $result[$k]->goods_list = $goods;
        }
        return $result;
    }

    /**
     * 检查是否收藏
     * @param type $supplier_id
     * @param type $user_id
     */
    protected function eheckCollection($supplier_id, $user_id) {
        $flow = DB::table('ecs_supplier_guanzhu')->where('userid', $user_id)->where('supplierid', $supplier_id)->first();
        if (!empty($flow)) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * 根据 省市 县 得到地址
     * @param type $province_id
     * @param type $city_id
     * @param type $district_id
     * @return type
     */
    private function getAddress($province_id, $city_id, $district_id) {
        $province = DB::table('ecs_region')->select('region_name')->where('region_id', $province_id)->first(); //省
        $city = DB::table('ecs_region')->select('region_name')->where('region_id', $city_id)->first(); //市
        $district = DB::table('ecs_region')->select('region_name')->where('region_id', $district_id)->first(); //区
        $addres = !empty($province) ? $province->region_name . ' ' : '';
        $addres .=!empty($city) ? $city->region_name . ' ' : '';
        $addres.=!empty($district) ? $district->region_name . ' ' : '';
        return $addres;
    }
    private function _url($url) {
        
        $in = strstr($url, 'clouddn.com/');
        if ($in||empty($url)) {
            return $url;
        }
        $ins = strstr($url, '/');
        if ($ins) {
             return url($url);  
        }
        $QINIU_HOST=$_ENV['QINIU_HOST'];
         return $QINIU_HOST.'/'.$url;  
        
    }

}
