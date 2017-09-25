<?php

namespace App\Http\Controllers\manage;

use App\Goods;
use App\GoodsAttr;
use App\GoodsGallery;
use App\GoodsItem;
use App\Http\Controllers\ManageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Supplier;

class GoodsController extends ManageController
{
    /*
     * 商品列表显示
     */

    public function index(Request $request)
    {
        $keyword = $request->get('keyword', null);
        $is_on_sale = $request->get('is_on_sale', null);
        $supplier_id = session('supplier_id');
        if ($keyword) {
            $goods_list = DB::table('woke_goods')->where('supplier_id', $supplier_id)->where('is_delete', 0)->where('goods_sn', $keyword)->orwhere('goods_name', 'like', "%$keyword%")->where('supplier_id', $supplier_id)->where('is_delete', 0)->orderBy('goods_id', 'desc')->paginate(10);
        } else if ($is_on_sale == 1) {
            $goods_list = DB::table('woke_goods')->where('is_on_sale', 1)->where('supplier_id', $supplier_id)->orderBy('goods_id', 'desc')->paginate(10);
        } else if ($is_on_sale == 2) {
            $goods_list = DB::table('woke_goods')->where('is_on_sale', 0)->where('supplier_id', $supplier_id)->orderBy('goods_id', 'desc')->paginate(10);
        } else {
            $goods_list = DB::table('woke_goods')->where('supplier_id', $supplier_id)->where('is_delete', 0)->orderBy('goods_id', 'desc')->paginate(10);
        }
        return view('manage.goods.index')->with(['goods_list' => $goods_list, 'keyword' => $keyword, 'is_on_sale' => $is_on_sale, 'shop_brand' => session('manage_role'),]);
    }

    /**
     * 即点即改商品信息
     * @return int
     */
    public function updateNumber(Request $request)
    {
        $goods_id = $request->get('goods_id');
        $my_id = $request->get('my');
        $father = $request->get('father');
        if ($my_id == 3) {
            DB::table('woke_goods')->where('goods_id', $goods_id)->update(array('shop_price' => $father));
            return 3;
        } else if ($my_id == 4) {
            DB::table('woke_goods')->where('goods_id', $goods_id)->update(array('sort_order' => $father));
            return 4;
        } else {
            DB::table('woke_goods')->where('goods_id', $goods_id)->update(array('goods_number' => $father));
            return 5;
        }
    }

    /*
     * 商品添加
     * @param \Illuminate\Http\Request $request
     */
    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $data['goods_name'] = $request->get('goods_name');
        $data['goods_name_style'] = $request->get('goods_name_style');
        $godos_sn = $request->get('goods_sn');
        $goods = Goods::where('goods_sn', $godos_sn)->first();
        if (!empty($goods))
            $godos_sn = chr(rand(65, 90)) . date("YmdHis", time());
        $data['goods_sn'] = $godos_sn;
        $data['shop_price'] = $request->get('shop_price');
        $data['market_price'] = $request->get('market_price');
        $data['goods_number'] = $request->get('goods_number');
        $data['cat_id'] = $request->get('cat_id');
        $data['goods_desc'] = $request->get('goods_desc');
        $data['is_real'] = $request->get('is_real');
        $data['integral'] = $request->get('integral', 0);
        $gallery = $request->get('desc');
        if (!empty($gallery)) {
            $data['goods_img'] = $gallery[0];
            $data['goods_thumb'] = $gallery[0];
        } else {
            $data['goods_img'] = '';
            $data['goods_thumb'] = '';
        }
        if (Session('supplier_id') == 0) {
            $data['is_best'] = 0;
            $data['is_new'] = 0;
            $data['is_hot'] = 0;
            $data['is_on_sale'] = 1;
        } else {
            $data['is_best'] = 0;
            $data['is_new'] = 0;
            $data['is_hot'] = 0;
            $data['is_on_sale'] = 2;
        }
        $data['is_delete'] = 0;
        $data['add_time'] = time();
        $data['promote_start_date'] = 0;
        $data['promote_end_date'] = 0;
        $data['goods_style'] = '';
        //$data['supplier_id'] = $request->get('supplier_id');
        $data['supplier_id'] = Session('supplier_id');
        $good = Goods::create($data);
        //2.ADD gallery
        if (!empty($gallery)) {
            foreach ($gallery as $k => $v) {
                GoodsGallery::create(['goods_id' => $good->goods_id, 'img_desc' => $v, 'img_url' => $v, 'img_sort' => $k]);
            }
        }
        //3.add item
        $keyname = $request->get('keyname');
        $keyvalue = $request->get('keyvalue');
        $stor = $request->get('stor');
        if (!empty($keyname)) {
            foreach ($keyname as $k => $v) {
                GoodsItem::create(['goods_id' => $good->goods_id, 'key' => $v, 'value' => $keyvalue[$k], 'stor' => $stor[$k]]);
            }
        }
        //4.add attr
        $attr_name = $request->get('attr_name');
        $attr_value = $request->get('attr_value');
        $attr_number = $request->get('attr_number');
        $attr_market = $request->get('attr_market');
        $attr_price = $request->get('attr_price');
        if (!empty($attr_name)) {
            foreach ($attr_name as $key => $value) {
                if (!empty($value)) {
                    foreach ($attr_value[$key] as $k => $v) {
                        if (!empty($v))
                            GoodsAttr::create(['goods_id' => $good->goods_id, 'attr_name' => $value, 'attr_value' => $v, 'shop_price' => $attr_price[$key][$k], 'goods_number' => $attr_number[$key][$k], 'market_price' => $attr_market[$key][$k]]);
                    }
                }
            }
        }
        return redirect("manage/goods");
    }

    /*
    *商品编辑保存
    * @param \Illuminate\Http\Request $request
    */
    public function save(Request $request)
    {
        $id = $request->get('id');
        $data['goods_name'] = $request->get('goods_name');
        $data['goods_name_style'] = $request->get('goods_name_style');
        $godos_sn = $request->get('goods_sn');
        if (empty($godos_sn))
            $godos_sn = chr(rand(65, 90)) . date("YmdHis", time());
        $data['goods_sn'] = $godos_sn;
        $data['shop_price'] = $request->get('shop_price');
        $data['market_price'] = $request->get('market_price');
        $data['goods_number'] = $request->get('goods_number');
        $data['cat_id'] = $request->get('cat_id');
        $data['is_real'] = $request->get('is_real');
        $data['integral'] = $request->get('integral', 0);
        $data['goods_desc'] = $request->get('goods_desc');
        $gallery = $request->get('desc');
        if (!empty($gallery)) {
            $data['goods_img'] = $gallery[0];
            $data['goods_thumb'] = $gallery[0];
        } else {
            $data['goods_img'] = '';
            $data['goods_thumb'] = '';
        }
        if (Session('supplier_id') == 0) {
            $data['is_best'] = 0;
            $data['is_new'] = 0;
            $data['is_hot'] = 0;
        } else {
            $data['is_best'] = 0;
            $data['is_new'] = 0;
            $data['is_hot'] = 0;
            $data['is_on_sale'] = 2;
        }
        $data['is_delete'] = 0;
        $data['add_time'] = time();
        $data['promote_start_date'] = 0;
        $data['promote_end_date'] = 0;
        $data['goods_style'] = '';
        $good = Goods::where('goods_id', $id)->update($data);

        //2.ADD gallery
        if (!empty($gallery)) {
            GoodsGallery::where('goods_id', $id)->delete();
            foreach ($gallery as $k => $v) {
                GoodsGallery::create(['goods_id' => $id, 'img_desc' => $v, 'img_url' => $v, 'img_sort' => $k]);
            }
        }
        //3.add item
        $keyname = $request->get('keyname');
        $keyvalue = $request->get('keyvalue');
        $stor = $request->get('stor');
        if (!empty($keyname)) {
            GoodsItem::where('goods_id', $id)->delete();
            foreach ($keyname as $k => $v) {
                GoodsItem::create(['goods_id' => $id, 'key' => $v, 'value' => $keyvalue[$k], 'stor' => $stor[$k]]);
            }
        }
        //4.add attr
        $attr_name = $request->get('attr_name');
        $attr_value = $request->get('attr_value');
        $attr_number = $request->get('attr_number');
        $attr_market = $request->get('attr_market');
        $attr_price = $request->get('attr_price');

        if (!empty($attr_name)) {
            $attids = [];
            foreach ($attr_name as $key => $value) {
                if (!empty($value)) {
                    foreach ($attr_value[$key] as $k => $v) {
                        if (!empty($v)) {
                            $attr = GoodsAttr::where('goods_id', $id)->where('attr_name', $value)->where('attr_value', $v)->first();
                            if (!empty($attr)) {
                                GoodsAttr::where('goods_id', $id)->where('attr_name', $value)->where('attr_value', $v)->update(['attr_value' => $v, 'shop_price' => $attr_price[$key][$k], 'goods_number' => $attr_number[$key][$k], 'market_price' => $attr_market[$key][$k]]);
                            } else {
                                $attr = GoodsAttr::create(['goods_id' => $id, 'attr_name' => $value, 'attr_value' => $v, 'shop_price' => $attr_price[$key][$k], 'goods_number' => $attr_number[$key][$k], 'market_price' => $attr_market[$key][$k]]);
                            }
                            $attids[] = $attr->id;
                        }
                    }
                }
            }
            GoodsAttr::where('goods_id', $id)->whereNotIn('id', $attids)->delete();
        }
        return redirect("manage/goods");
    }


    /**
     * 单个商品放入回收站
     * @param  int $id
     */
    public function edit($id)
    {
        //$supplier = DB::table('woke_supplier')->select('supplier_id', 'supplier_name')->where('supplier_id', session('supplier_id'))->first();
        $cat = DB::table('woke_category')->select('cat_id', 'cat_name', 'parent_id')->get();
        $goods = Goods::with('attr', 'item', 'gallery')->where('goods_id', $id)->first();
        $lists = [];
        if (isset($goods->attr)) {
            foreach ($goods->attr as $k => $v) {
                $lists[$v->attr_name][] = $v;
            }
        }
        $goods->attr = $lists;

        return view('manage.goods.create', ['cat' => $cat, 'goods' => $goods, 'id' => $id]);
    }

    public function create(Request $request)
    {
        //$supplier = DB::table('woke_supplier')->select('supplier_id', 'supplier_name')->where('supplier_id', session('supplier_id'))->get();
        $cat = DB::table('woke_category')->select('cat_id', 'cat_name', 'parent_id')->get();
        return view('manage.goods.create', ['cat' => $cat]);
    }

    /*
     *商品页面状态操作
     * @param \Illuminate\Http\Request $request
     */
    public function updateStatus(Request $request)
    {
        $godos_id = $request->get('goods_id');
        $now = $request->get('now');
        $my = $request->get('my');
        if ($now != 1)
            $now = 1;
        else
            $now = 0;
        if ($my == 1) {
            DB::table('woke_goods')->where('goods_id', $godos_id)->update(array('is_best' => $now));
        } else if ($my == 2) {
            DB::table('woke_goods')->where('goods_id', $godos_id)->update(array('is_new' => $now));
        } else if ($my == 3) {
            DB::table('woke_goods')->where('goods_id', $godos_id)->update(array('is_hot' => $now));
        } else if ($my == 4) {
            DB::table('woke_goods')->where('goods_id', $godos_id)->update(array('is_on_sale' => $now));
        } else if ($my == 5) {
            if (DB::table('woke_goods')->where('goods_id', $godos_id)->update(array('is_delete' => $now))) {
                echo 1;
            } else {
                echo 2;
            }
        }
    }

    /*
       *商品回收站显示
       * @param \Illuminate\Http\Request $request
       */
    public function recycle(Request $request)
    {
        $supplier_id = session('supplier_id');
        $keyword = $request->get('keyword', null);
        if ($keyword)
            $goods_list = DB::table('woke_goods')->where('supplier_id', $supplier_id)->where('is_delete', 1)->where('goods_sn', "$keyword")->orWhere('goods_name', 'like', "%$keyword%")->orderBy('goods_id', 'desc')->paginate(10);
        else
            $goods_list = DB::table('woke_goods')->where('supplier_id', $supplier_id)->where('is_delete', 1)->orderBy('goods_id', 'desc')->paginate(10);
        return view('manage.goods.recycle_bin')->with(['goods_list' => $goods_list, 'keyword' => $keyword]);
    }

    /**
     *
     * 审核首页&&第三方商品驳回&&商品查询
     * @return $this
     */
    public function istrator(Request $request)
    {
        $keyword = $request->get('keyword', null);
        $is_on_sale = $request->get('is_on_sale', null);
        $lier = $request->get('supplier', null);
        $goods_id = $request->get('goods_id');
        $value = $request->get('value');
        $supplier = Supplier::where('supplier_id', '!=', 0)->get();
        if ($lier && $is_on_sale == 1) {
            $goods_list = Goods::whereSupplier_id($lier)->where('is_on_sale', 1)->where('supplier_id', '!=', 0)->orderBy('goods_id', 'desc')->paginate(10);
        } else if ($lier && $is_on_sale == 2) {
            $goods_list = Goods::whereSupplier_id($lier)->where('is_on_sale', 0)->where('supplier_id', '!=', 0)->orderBy('goods_id', 'desc')->paginate(10);
        } else if ($lier) {
            $goods_list = Goods::whereSupplier_id($lier)->where('supplier_id', '!=', 0)->orderBy('goods_id', 'desc')->paginate(10);
        } else if ($keyword) {
            $goods_list = Goods::where('goods_name', 'like', "%$keyword%")->where('supplier_id', '!=', 0)->orderBy('goods_id', 'desc')->paginate(10);
        } else if ($is_on_sale == 1) {
            $goods_list = Goods::where('is_on_sale', 1)->where('supplier_id', '!=', 0)->orderBy('goods_id', 'desc')->paginate(10);
        } else if ($is_on_sale == 2) {
            $goods_list = Goods::where('is_on_sale', 0)->where('supplier_id', '!=', 0)->orderBy('goods_id', 'desc')->paginate(10);
        } else {
            $goods_list = Goods::where('supplier_id', '!=', 0)->orderBy('goods_id', 'desc')->paginate(10);
        }
        if (!empty($goods_id)) {
            Goods::where('goods_id', $goods_id)->update(['is_on_sale' => 3, 'rec_content'=>$value]); //审核未通过,驳回
        }
        return view('manage.goods.istrator')->with(['goods_list' => $goods_list, 'supplier'=>$supplier, 'lier'=>$lier, 'keyword' => $keyword, 'is_on_sale' => $is_on_sale]);
    }

    public function look($id)
    {
        $cat = DB::table('woke_category')->select('cat_id', 'cat_name', 'parent_id')->get();
        $goods = Goods::with('attr', 'item', 'gallery')->where('goods_id', $id)->first();
        $lists = [];
        if (isset($goods->attr)) {
            foreach ($goods->attr as $k => $v) {
                $lists[$v->attr_name][] = $v;
            }
        }
        $goods->attr = $lists;
        return view('manage.goods.look', ['cat' => $cat, 'goods' => $goods, 'id' => $id]);
    }


}
