<?php

namespace App\Http\Controllers\manage;

use Illuminate\Http\Request;
use App\Http\Controllers\ManageController;
use Illuminate\Support\Facades\DB;
use App\User;

class StoreController extends ManageController {

    /**
     * 店铺管理
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex(Request $request) {
        $list = DB::table('woke_supplier')->where('status', '>=', 0)->orderBy('supplier_id', 'desc')->paginate(15);
        $province = DB::table('woke_region')->where('region_type', 1)->get();
        return view('store.index')->with(['list' => $list, 'province' => $province]);
    }

    /**
     * 添加店铺
     * @param \Illuminate\Http\Request $request
     */
    public function postAddstore(Request $request) {
        //修改排序
        $pk = $request->get('pk');
        if (!empty($pk)) {
            $supple_order = $request->get('value');
            if (is_numeric($supple_order)) {
                DB::table('woke_supplier')->where('supplier_id', $pk)->update(['supple_sort_order' => $supple_order]);
                return response()->json(['status' => 'ok','msg'=>'ok']);
            }
            return response()->json(['status' => 'error','msg'=>'排序数格式不对']);
        }
        //end修改排序
        $mobil = $request->get('mobil');
        $company_name = $request->get('company_name');
        $province = $request->get('province');
        $city = $request->get('city');
        $district = $request->get('district','');
        $address = $request->get('address');
        $tel = $request->get('tel');
        $contacts_phone = $request->get('contacts_phone');
        $contacts_name = $request->get('bank_account_name');
        $bank_account_number = $request->get('bank_account_number');
        $bank_account_name = $request->get('bank_account_name');
        $bank_name = $request->get('bank_name');
        //
        $handheld_idcard = $request->get('handheld_idcard');
        $idcard_reverse = $request->get('idcard_reverse');
        $idcard_front = $request->get('idcard_front');
        $id_card_no = $request->get('id_card_no');
        $email = $request->get("email");
        $content = $request->get("content");
        $supplier_img = $request->get('supplier');

        $user = User::where('mobile_phone', $mobil)->first();
        if (!empty($user)) {
            $supplier_data['user_id'] = $user->user_id;
            $supplier_data['supplier_name'] = $company_name;
            $supplier_data['company_name'] = $company_name;
            $supplier_data['rank_id'] = 3;
            $supplier_data['type_id'] = 1;
            $supplier_data['country'] = 1;
            $supplier_data['province'] =1;
            $supplier_data['city'] = 1;
            $supplier_data['district'] = $district;
            $supplier_data['address'] = $address;
            $supplier_data['enabled'] = 0;
            $supplier_data['status'] = 1;
            $supplier_data['add_time'] = time();
            $supplier_data['contacts_name'] = $contacts_name;
            $supplier_data['tel'] = $tel;
            $supplier_data['contacts_phone'] = $contacts_phone;
            $supplier_data['bank_account_name'] = $bank_account_name;
            $supplier_data['bank_account_number'] = $bank_account_number;
            $supplier_data['bank_name'] = $bank_name;
            $supplier_data['email'] = $email;
            $supplier_data['handheld_idcard'] = $handheld_idcard;
            $supplier_data['idcard_front'] = $idcard_front;
            $supplier_data['idcard_reverse'] = $idcard_reverse;
            $supplier_data['id_card_no'] = $id_card_no;
            $supplier_data['supplier_img'] = $supplier_img;
            $supplier_data['content'] = $content;
            $id = DB::table('woke_supplier')->insertGetId($supplier_data);
             $adming_user_data['add_time']=time();
           //   $adming_user_data['uid']=$user->user_id;
              $adming_user_data['user_name']=$user->user_name;
              $adming_user_data['email']=!empty($user->mobile_phone)?$user->mobile_phone:$user->email;
              $adming_user_data['password']=$user->password;
              $adming_user_data['ec_salt']=$user->ec_salt;
              $adming_user_data['action_list']='all';
              $adming_user_data['supplier_id']=$id;
              $adming_user_data['role_id']=5;
              DB::table('woke_admin_user')->insert($adming_user_data);
              $user_id = DB::table('woke_admin_user')->select('user_id')->where('supplier_id', $id)->first();
              $role['user_id'] = $user_id->user_id;
              $role['role_id'] = 5;
              DB::table('role_user')->insert($role);
        }
        return redirect('manage/store/index');
    }

    /**
     * 店铺设置
     * @param \Illuminate\Http\Request $request
     */
    public function getShow(Request $request) {
        $store_id = $request->get('supplier_id', session('supplier_id')); //如果传入id 就直接进去 没有就看登录
        $store = DB::table('woke_supplier')->where('supplier_id', $store_id)->first();
    //    $store_info = DB::table('woke_supplier_info')->where('supplier_id', $store_id)->first();
        $province = DB::table('woke_region')->where('region_type', 1)->get();
        if (empty($store)) {
            return redirect('manage/store/index');
        }
        $city = DB::table('woke_region')->select('region_name')->where('region_id', $store->city)->first();
        $district = DB::table('woke_region')->select('region_name')->where('region_id', $store->district)->first();
        if (!empty($store_info)) {
            $is_array = explode(",", $store_info->supplier_desc);
            $store_info->keyword = explode(" ", $store_info->keyword);
            if (is_array($is_array) && $is_array[0]) {
                $is_array = array_filter($is_array);
                $store_info->supplier_desc = $is_array;
            } else {
                $store_info->supplier_desc = [];
            }
        }
        return view('store.edit')->with(['store' => $store, 'list' => $province, 'city' => $city, 'district' => $district]);
    }

    /**
     * 编辑
     * @param \Illuminate\Http\Request $request
     */
    public function postStore(Request $request) {
        $supplier_id = $request->get('supplier_id', 0);
        $supplier_name = $request->get('supplier_name');
        $logo_file = $request->get('logo_file');
        $banner_file = $request->get('banner_file');
        $tel = $request->get('tel');
        $province = $request->get('province');
        $city = $request->get('city');
        $district = $request->get('district');
        $address = $request->get('address');
        $keyword = $request->get('keyword');
        $keywords = implode(" ", $keyword);
        $desc = $request->get('desc');
        $qr_code = $request->get('qr_code');
        if (session('manage_role') != 'supplier') {
            $handheld_idcard = $request->get('handheld_idcard');
            $idcard_reverse = $request->get('idcard_reverse');
            $idcard_front = $request->get('idcard_front');
            $id_card_no = $request->get('id_card_no');
            $contacts_phone = $request->get("contacts_phone");
            $bank_account_name = $request->get("bank_account_name");
            $bank_name = $request->get("bank_name");
            $bank_account_number = $request->get("bank_account_number");
            $contacts_name = $request->get('bank_account_name');
        }
        if ($supplier_id) {
            $data['supplier_name'] = $supplier_name;
            $data['tel'] = $tel;
            $data['country'] = 1;
            $data['province'] = $province;
            $data['city'] = $city;
            $data['district'] = $district;
            $data['address'] = $address;
            if (session('manage_role') != 'supplier') {
                $data['contacts_name'] = $contacts_name;
                $data['contacts_phone'] = $contacts_phone;
                $data['bank_account_name'] = $bank_account_name;
                $data['bank_account_number'] = $bank_account_number;
                $data['bank_name'] = $bank_name;
                $data['handheld_idcard'] = $handheld_idcard;
                $data['idcard_front'] = $idcard_front;
                $data['idcard_reverse'] = $idcard_reverse;
                $data['id_card_no'] = $id_card_no;
            }
            DB::table('woke_supplier')
                    ->where('supplier_id', $supplier_id)
                    ->update($data);
            $store_info = DB::table('woke_supplier_info')->where('supplier_id', $supplier_id)->first();
            if (!empty($desc)) {
                $supplier_desc = implode(",", $desc);
            } else {
                $supplier_desc = '';
            }
            $data_info['supplier_id'] = $supplier_id;
            $data_info['logo_file'] = $logo_file;
            $data_info['banner_file'] = $banner_file;
            $data_info['qr_code'] = $qr_code;
            $data_info['keyword'] = $keywords;
            $data_info['supplier_desc'] = $supplier_desc;
            if (empty($store_info)) {
                DB::table('woke_supplier_info')->insert($data_info);
            } else {
                DB::table('woke_supplier_info')
                        ->where('supplier_id', $supplier_id)
                        ->update($data_info);
            }
        }
        return redirect('manage/store/show?supplier_id=' . $supplier_id);
    }

    /**
     *  省城市联动
     * @return Response
     */
    public function getCity(Request $request) {
        $region_id = $request->get('region_id');
        if ($region_id) {
            $province = DB::table('woke_region')->where('parent_id', $region_id)->where('region_type', '>', 1)->get();
            $info = "<option value='0'>请选择</option>";
            foreach ($province as $v) {
                $info.="<option value='" . $v->region_id . "'  > $v->region_name </option>";
            }
            echo $info;
        } else {
            $info = "<option value=''>请选择</option>";
            echo $info;
        }
        exit;
    }

    /**
     * 编辑
     * @param \Illuminate\Http\Request $request
     */
    public function getEdit(Request $request) {
        $id = $request->get('id');
        $on = $request->get('on', null);
        $store = DB::table('woke_supplier')->where('supplier_id', $id)->first();
        if ($on == 'offon') {
            $status = 0;
            if (!empty($store) && $store->status) {
                $status = 0;
            } else {
                $status = 1;
            }
            DB::table('woke_supplier')
                    ->where('supplier_id', $id)
                    ->update(['status' => $status]);
            echo 'ok';
            exit;
        } else if ($on == 'yesno') {
            $enabled = 0;
            if (!empty($store) && $store->enabled) {
                $enabled = 0;
            } else {
                $enabled = 1;
            }
            DB::table('woke_supplier')
                    ->where('supplier_id', $id)
                    ->update(['enabled' => $enabled]);
            echo 'ok';
            exit;
        } else {
            
        }
    }

}
