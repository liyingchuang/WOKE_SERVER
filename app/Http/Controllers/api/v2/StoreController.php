<?php

namespace App\Http\Controllers\api\v2;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\api\v1\StoreController as StoreBaseController;

class StoreController extends StoreBaseController {

    /**
     * 店铺分享
     * @param \Illuminate\Http\Request $request
     */
    public function getShare(Request $request) {
        $supplier_id = $request->get('supplier_id', 0);
        $json_data = $this->getInfo($request)->getContent();
        $aray_data = json_decode($json_data, true);
        if ($aray_data && $aray_data['code']) {
            return $this->error(null, $aray_data['info']);
        }
        $goods = DB::table('ecs_goods')
                ->select('ecs_goods.goods_id', 'ecs_goods.goods_name', 'ecs_goods.click_count', 'ecs_goods.shop_price', 'ecs_goods.add_time', 'ecs_goods.goods_sn', 'ecs_goods.shop_price', 'ecs_goods.market_price', 'ecs_goods.goods_thumb', 'ecs_goods.goods_number', 'ecs_goods.goods_type as goods_sales')
                ->where('ecs_goods.is_on_sale', 1)->where('ecs_goods.is_delete', 0)->where('ecs_goods.supplier_id', $supplier_id)
                ->take(4)->orderBy('add_time','desc')->get();
        $return=$aray_data['data'];
        $return['goods']=$goods;
        return $this->success($return);
    }

    public function getShow(Request $request) {
        $cat_id = $request->get('cat_id', null);
        $field = $request->get('field', 'add_time');
        $order = $request->get('order', 'desc');
        $user_id = $request->get('user_id', 0);
        $supplier_id = $request->get('supplier_id', 0);
        $result = [];
        if ($cat_id) {
            $supplier = DB::table('ecs_supplier')
                            ->leftJoin('ecs_supplier_info', 'ecs_supplier.supplier_id', '=', 'ecs_supplier_info.supplier_id')
                            ->select('ecs_supplier.supplier_id','ecs_supplier.user_id', 'ecs_supplier.supplier_name', 'ecs_supplier.province', 'ecs_supplier.city', 'ecs_supplier.district', 'ecs_supplier.address', 'ecs_supplier.tel', 'ecs_supplier.add_time', 'ecs_supplier_info.banner_file', 'ecs_supplier_info.logo_file','ecs_supplier_info.keyword')
                            ->where('ecs_supplier.status', 1)->where('ecs_supplier.supplier_id', $supplier_id)->first();
            if (empty($supplier)) {
                return $this->error($result, '该店铺盘点中，先看看其他店铺吧!');
            }
            $supplier->banner_file = $this->_url($supplier->banner_file);
            $supplier->logo_file = $this->_url($supplier->logo_file);
            $result = $supplier;
            if ($user_id) {
                $result->is_collection = $this->eheckCollection($supplier_id, $user_id);
            } else {
                $result->is_collection = 0;
            }
            $goods = [];
            if ($field == 'sales_number') {//销量
                $goods = DB::table('ecs_goods')
                                ->join('ecs_supplier_goods_cat', 'ecs_supplier_goods_cat.goods_id', '=', 'ecs_goods.goods_id')
                                ->leftJoin('ecs_order_goods', 'ecs_goods.goods_id', '=', 'ecs_order_goods.goods_id')
                                ->select('ecs_goods.goods_id', 'ecs_goods.goods_name', 'ecs_goods.goods_number', 'ecs_goods.goods_sn', 'ecs_goods.click_count', 'ecs_goods.shop_price', 'ecs_goods.add_time', 'ecs_goods.shop_price', 'ecs_goods.market_price', 'ecs_goods.goods_thumb', DB::raw('SUM(ecs_order_goods.goods_number) as goods_sales'))
                                ->where('ecs_goods.is_on_sale', 1)->where('ecs_goods.is_delete', 0)->where('ecs_goods.supplier_id', $supplier_id)
                                ->where('ecs_supplier_goods_cat.cat_id', $cat_id)
                                ->groupBy('ecs_goods.goods_id')
                                ->orderBy('goods_sales', $order)
                                ->paginate(10)->toArray();
            } else {
                $goods = DB::table('ecs_goods')
                                ->leftJoin('ecs_supplier_goods_cat', 'ecs_supplier_goods_cat.goods_id', '=', 'ecs_goods.goods_id')
                                ->select('ecs_goods.goods_id', 'ecs_goods.goods_name', 'ecs_goods.click_count', 'ecs_goods.shop_price', 'ecs_goods.add_time', 'ecs_goods.goods_sn', 'ecs_goods.shop_price', 'ecs_goods.market_price', 'ecs_goods.goods_thumb', 'ecs_goods.goods_number', 'ecs_goods.goods_type as goods_sales')
                                ->where('ecs_goods.is_on_sale', 1)->where('ecs_goods.is_delete', 0)->where('ecs_goods.supplier_id', $supplier_id)
                                ->where('ecs_supplier_goods_cat.cat_id', $cat_id)
                                ->orderBy('ecs_goods.' . $field, $order)
                                ->paginate(10)->toArray();
            }
            foreach ($goods['data'] as $k => $v) {
                $goods['data'][$k] = $v;
                $goods['data'][$k]->add_time = date('Y-m-d H:i', $v->add_time);
                if (isset($v->goods_sales) && $v->goods_sales) {
                    $goods['data'][$k]->goods_sales = strval($v->goods_sales);
                } else {
                    $goods['data'][$k]->goods_sales = '0';
                }
            }
            if (!empty($goods)) {
                $result->goods_list = $goods['data'];
            } else {
                $result->goods_list = '';
            }
            $result->users= User::select('user_id', 'user_name', 'user_rank', 'headimg')->where('user_id', $supplier->user_id)->first();
            return $this->success($result, '');
        } else {
            return parent::getShow($request)->getContent();
        }
    }

    private function _url($url) {

        $in = strstr($url, 'clouddn.com/');
        if ($in || empty($url)) {
            return $url;
        }
        $ins = strstr($url, '/');
        if ($ins) {
            return url($url);
        }
        $QINIU_HOST = $_ENV['QINIU_HOST'];
        return $QINIU_HOST . '/' . $url;
    }

}
