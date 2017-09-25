<?php

namespace App\Http\Controllers\manage;

use Illuminate\Http\Request;
use App\Http\Controllers\ManageController;
use Illuminate\Support\Facades\DB;
use App\GroupGoods;
use App\User;
use App\Goods;

class GroupController extends ManageController {

    /**
     *
     * 首页
     * @param Request $request
     * @return $this
     */
    public function getIndex(Request $request) {
        $goods_id = $request->get('goods_id', null);
        $type = $request->get('type');
        $goods_name = $request->get('goods_name', null);
        if(!empty($goods_name)){
            $info = GroupGoods::join('woke_goods', 'woke_group_goods_extends.goods_id' , '=', 'woke_goods.goods_id')->join('woke_group_classify', 'woke_group_goods_extends.ify_id','=','woke_group_classify.ify_id')->select('woke_group_goods_extends.*','woke_goods.goods_name','woke_group_classify.ify_id','woke_group_classify.ify_name')->where('woke_goods.goods_name', 'like', "%$goods_name%")->where('is_on_sale', 1)->where('is_delete', 0)->orderBy('woke_group_goods_extends.created_at', 'desc')->paginate(15);
        }else{
            $info = GroupGoods::join('woke_goods', 'woke_group_goods_extends.goods_id' , '=', 'woke_goods.goods_id')->join('woke_group_classify', 'woke_group_goods_extends.ify_id','=','woke_group_classify.ify_id')->select('woke_group_goods_extends.*','woke_goods.goods_name','woke_group_classify.ify_id','woke_group_classify.ify_name')->where('is_on_sale', 1)->where('is_delete', 0)->orderBy('woke_group_goods_extends.created_at', 'desc')->paginate(15);
            //$info = GroupGoods::with('goods','ify')->orderBy('created_at', 'desc')->paginate(15);
        }
        if($type ==2){
            GroupGoods::where('goods_id', $goods_id)->update(['recommend' => 1]);
        }
        if($type ==1){
            GroupGoods::where('goods_id', $goods_id)->update(['recommend' => 0]);
        }
        return view('manage.group.index')->with(['info'=>$info, 'goods_name'=>$goods_name ]);
    }

    /**
     *
     * 上传团购商品
     * @param Request $request
     * @return bool
     */
    public function getLoad(Request $request) {
        $store_image = $request->get('store_image');
        $goods_id = $request->get('goods_id');
        $new_id = $request->get('new_id');
        $ify_id = $request->get('ify_id',1);
        $attr_name = $request->get('attr_name');
        $attr_value = $request->get('attr_value');
        $attr_price = $request->get('attr_price');
        $ex_number = $request->get('ex_number');
        $ex_have = $request->get('ex_have', 0);
        $group_price = $request->get('group_price');
        $describe = $request->get('describe');
        $start_time = $request->get('start_time', null);
        $end_time = $request->get('end_time', null);
        $supplier_id = $request->get('supplier_id', null);
        $head_free= $request->get('head_free', null);
        $created_time = date('Y-m-d H:i:s', time());
        $update_time = date('Y-m-d H:i:s', time());
        if(empty($store_image)){
            $store_image = GroupGoods::where('goods_id', $goods_id)->pluck('group_file');
        }
        if($supplier_id == 0){
            $data['examine_status'] = 4;
        }
        $data['goods_id'] = $goods_id?$goods_id:$new_id;
        $data['ify_id'] = $ify_id;
        $data['ex_number'] = $ex_number;
        $data['ex_have'] = $ex_have;
        $data['group_price'] = $group_price;
        $data['start_time'] = strtotime($start_time);
        $data['end_time'] = strtotime($end_time);
        $data['supplier_id'] = $supplier_id;
        $data['describe'] = $describe;
        $data['group_file'] = $store_image;
        $data['created_at'] = $created_time;
        $data['updated_at'] = $update_time;
        $data['head_free'] = $head_free;

        if($goods_id){
            DB::table('woke_group_goods_extends')->where('goods_id', $goods_id)->update($data);
        }
        if($new_id) {
            $id = GroupGoods::where('goods_id', $new_id)->first();
            if ($id) {
                return false;
            } else {
                if($attr_name && $attr_value){
                    $attr['group_price'] = $attr_price;
                    DB::table('woke_goods_attr')->where('attr_name', $attr_name)->where('attr_value', $attr_value)->where('goods_id', $new_id)->update($attr);
                }else if($attr_name){
                    $attr['group_price'] = $attr_price;
                    DB::table('woke_goods_attr')->where('attr_name', $attr_name)->where('goods_id', $new_id)->update($attr);
                }else if($attr_value){
                    $attr['group_price'] = $attr_price;
                    DB::table('woke_goods_attr')->where('attr_value', $attr_value)->where('goods_id', $new_id)->update($attr);
                }
                DB::table('woke_group_goods_extends')->insert($data);
            }
        }
    }

    /**
     *
     * 上传团购商品详情页
     * @param string $goods_id
     * @return $this
     */
    public function getUploade(Request $request, $goods_id="") {
        $gid = $request->get('goods_id');
        $type = $request->get('type');
        $attr_id = $request->get('attr_id');
        $attr_price = $request->get('attr_price');
        $supplier_id = session('supplier_id');
        $supplier = DB::table('woke_supplier')->select('supplier_id', 'supplier_name')->get();
        $goods = DB::table('woke_group_goods_extends')->join('woke_goods', 'woke_group_goods_extends.goods_id', '=', 'woke_goods.goods_id')->where('woke_goods.goods_id', $goods_id)->get();
        $attr = DB::table('woke_goods_attr')->where('goods_id', $goods_id)->get();
//        $info = DB::table('woke_goods')->where('is_on_sale', 1)->where('is_delete', 0)->where('supplier_id', $supplier_id)->get();
        $info = DB::table('woke_goods')->where('is_on_sale', 1)->where('is_delete', 0)->get();
        $class = DB::table('woke_group_classify')->get();
        if($type == 1){
            //上传商品动态显示Sku团购价格
              $atte = DB::table('woke_goods_attr')->where('goods_id', $gid)->get();
              return json_encode($atte);
        }
        if($type == 2){
            //修改商品Sku团购价格
            DB::table('woke_goods_attr')->where('id', $attr_id)->update(['group_price'=>$attr_price]);
        }
        return view('manage.group.uploade')->with(['goods'=>$goods ,'goods_id'=>$goods_id, 'info'=>$info, 'attr'=>$attr, 'class'=>$class, 'supplier'=>$supplier]);
    }

    /**
     *
     * 开团人信息
     * @param Request $request
     * @param string $group_id
     * @return $this
     */
    public function getGoods(Request $request, $group_id="") {
        $supplier_id = session('supplier_id');
        $goods_name = $request->get('goods_name', null);
        //搜索
        if($goods_name){
            $info = DB::table('woke_group_open')->join('woke_goods', 'woke_group_open.goods_id' , '=', 'woke_goods.goods_id')->join('woke_users', 'woke_group_open.user_id', '=', 'woke_users.user_id')->select('woke_group_open.*', 'woke_goods.goods_name', 'woke_users.user_name')->where('woke_goods.goods_name', 'like', "%$goods_name%")->where('woke_group_open.supplier_id', $supplier_id)->orderBy('start_time','desc')->paginate(15);
        }else{
            $info = DB::table('woke_group_open')->join('woke_goods', 'woke_group_open.goods_id' , '=', 'woke_goods.goods_id')->join('woke_users', 'woke_group_open.user_id', '=', 'woke_users.user_id')->select('woke_group_open.*', 'woke_goods.goods_name', 'woke_users.user_name')->where('woke_group_open.supplier_id', $supplier_id)->where('woke_group_open.have','>', 1)->orderBy('start_time','desc')->paginate(15);
        }
        if($group_id){
            //查看当前团的参与情况
            $info = DB::table('woke_group_info')->join('woke_goods', 'woke_group_info.goods_id' , '=', 'woke_goods.goods_id')->join('woke_users', 'woke_group_info.user_id', '=', 'woke_users.user_id')->select('woke_group_info.*', 'woke_goods.goods_name', 'woke_users.user_name')->where('woke_group_info.group_id', $group_id)->where('woke_group_info.supplier_id', $supplier_id)->paginate(15);
        }
        return view('manage.group.goods')->with(['info'=>$info, 'group_id'=>$group_id, 'goods_name'=>$goods_name]);
    }

    /**
     *
     * 参团详情
     * @param Request $request
     * @return $this
     */
    public function getGroupinfo(Request $request, $goods_id="") {
        $supplier_id = session('supplier_id');
        $goods_name = $request->get('goods_name', null);
        if($goods_name){
            $info = DB::table('woke_group_info')->join('woke_group_open', 'woke_group_info.group_id', '=', 'woke_group_open.group_id')->join('woke_goods', 'woke_group_info.goods_id' , '=', 'woke_goods.goods_id')->join('woke_users', 'woke_group_info.user_id', '=', 'woke_users.user_id')->select('woke_group_info.*', 'woke_group_open.group_id', 'woke_goods.goods_name', 'woke_users.user_name')->where('woke_goods.goods_name', 'like', "%$goods_name%")->where('woke_group_info.supplier_id', $supplier_id)->orderBy('created_at', 'desc')->paginate(15);
        }else{
            $info = DB::table('woke_group_info')->join('woke_goods', 'woke_group_info.goods_id' , '=', 'woke_goods.goods_id')->join('woke_users', 'woke_group_info.user_id', '=', 'woke_users.user_id')->join('woke_group_open','woke_group_info.group_id', '=', 'woke_group_open.group_id')->select('woke_group_info.*', 'woke_goods.goods_name', 'woke_users.user_name','woke_group_open.group_status')->where('woke_group_info.supplier_id', $supplier_id)->orderBy('created_at', 'desc')->paginate(15);
        }
        if($goods_id){
            $info = DB::table('woke_goods')->join('woke_group_goods_extends', 'woke_goods.goods_id','=','woke_group_goods_extends.goods_id')->select('woke_goods.goods_id','woke_goods.goods_name','woke_goods.cat_id','woke_goods.goods_sn','woke_goods.shop_price','woke_goods.goods_number','woke_group_goods_extends.*')->where('woke_goods.goods_id', $goods_id)->paginate(15);
        }
        return view('manage.group.groupinfo')->with(['info'=>$info, 'goods_id'=>$goods_id, 'goods_name'=>$goods_name]);
    }

    /**
     *
     * 团购商品审核
     * @param string $goods_id
     * @param string $type
     * @return $this
     */
    public function getExamine($goods_id="", $type="") {
        if($type){
            DB::table('woke_group_goods_extends')->where('goods_id', $goods_id)->update(['examine_status'=> 3]);
        }else{
            DB::table('woke_group_goods_extends')->where('goods_id', $goods_id)->update(['examine_status'=> 2]);
        }
        $info = DB::table('woke_group_goods_extends')->join('woke_goods', 'woke_group_goods_extends.goods_id', '=', 'woke_goods.goods_id')->join('woke_supplier', 'woke_group_goods_extends.supplier_id', '=', 'woke_supplier.supplier_id')->select('woke_group_goods_extends.*', 'woke_goods.goods_name', 'woke_supplier.supplier_name')->orderBy('created_at', 'desc')->paginate(15);
        return view('manage.group.examine')->with(['info'=>$info, 'goods_id'=>$goods_id]);
    }

    /**
     *
     * 开启关闭团购
     * @param Request $request
     */
    public function getSwitch(Request $request) {
        $goods_id = $request->get('goods_id');
        $type = $request->get('type');
        if($type == 'on'){
            DB::table('woke_group_goods_extends')->where('goods_id', $goods_id)->update(['examine_status' => 4]);
        }
        if($type == 'off'){
            DB::table('woke_group_goods_extends')->where('goods_id', $goods_id)->update(['examine_status' => 2]);
        }
    }

    /**
     *
     * 查看申请详细信息
     * @param string $goods_id
     * @return $this
     */
    public function getLookinfo($goods_id="") {
        $info = DB::table('woke_supplier')->join('woke_goods', 'woke_supplier.supplier_id', '=', 'woke_goods.supplier_id')->join('woke_users', 'woke_supplier.user_id', '=' , 'woke_users.user_id')->select('woke_supplier.*', 'woke_goods.*', 'woke_users.mobile_phone')->where('woke_goods.goods_id', $goods_id)->get();
        return view('manage.group.lookinfo')->with('info', $info);
    }

    public function postJs(Request $request){
        $goods_name = $request->get('goods_name');
        $goods = Goods::with('groupgoods')->where('goods_name', 'like', "%$goods_name%")->where('is_on_sale', 1)->where('is_delete', 0)->get();
        return $goods;
    }
}