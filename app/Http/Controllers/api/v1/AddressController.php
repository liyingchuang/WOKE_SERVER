<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

/**
 * 用户地址相关操作
 */
class AddressController extends Controller {

    /**
     * 添加新地址
     * @param \Illuminate\Http\Request $request
     */
    public function postAdd(Request $request) {
        $consignee = $request->get('consignee');
        $mobile = $request->get('mobile');
        $user_id = $request->get('user_id');
        $province = $request->get('province');
        $city = $request->get('city');
        $district = $request->get('district');
        $address = $request->get('address');
        $is_default = $request->get('is_default', 0);
        $users = DB::table('ecs_user_address')->where('user_id', $user_id)->get();
        if (empty($users)) {//如果不是默认
            $is_default = 1;
        } else {
            if ($is_default) {
                DB::table('ecs_user_address')->where('user_id', $user_id)->update(['is_default' => 0]); //全部取消默认 
            }
        }
        $data = array(
            'user_id' => $user_id,
            'country' => 1,
            'province' => $province,
            'city' => $city,
            'district' => $district,
            'address' => $address,
            'consignee' => $consignee,
            'email' => '',
            'mobile' => $mobile,
            'best_time' => '',
            'sign_building' => '',
            'zipcode' => '',
            'is_default' => $is_default,
        );
        $is_edit = DB::table('ecs_user_address')->insert($data);
        if ($is_edit) {
            return $this->success(null, '添加成功');
        } else {
            return $this->error(null, '添加失败');
        }
    }

    /**
     * 编辑地址
     * @param \Illuminate\Http\Request $request
     */
    public function postEdit(Request $request) {
        $address_id = $request->get('address_id');
        $consignee = $request->get('consignee');
        $mobile = $request->get('mobile');
        $user_id = $request->get('user_id');
        $province = $request->get('province');
        $city = $request->get('city');
        $district = $request->get('district');
        $address = $request->get('address');
        $is_default = $request->get('is_default');
        $data = DB::table('ecs_user_address')->where('address_id', $address_id)->first();
        if (!empty($data)) {
            $datas = array(
                'province' => $province,
                'city' => $city,
                'district' => $district,
                'address' => $address,
                'consignee' => $consignee,
                'mobile' => $mobile,
            );
            if ($data->is_default == 0 && $data->is_default != $is_default) {
                DB::table('ecs_user_address')->where('user_id', $user_id)->update(['is_default' => 0]); //全部取消默认 
                $datas['is_default'] = 1;
            }
            DB::table('ecs_user_address')->where('address_id', $address_id)->where('user_id', $user_id)->update($datas);
            return $this->success(null, '编辑成功');
        } else {
           return $this->success(null, '编辑成功');
        }
    }

    /**
     * 地址列表
     *
     * @return \Illuminate\Http\Response
     */
    public function getList(Request $request) {
        $user_id = $request->get('user_id');
        $address_list = DB::table('ecs_user_address')->where('user_id', $user_id)->orderBy('is_default', 'desc')->get();
        $list = [];
        foreach ($address_list as $k => $v) {
            $province = DB::table('ecs_region')->select('region_name')->where('region_id', $v->province)->first(); //省
            $city = DB::table('ecs_region')->select('region_name')->where('region_id', $v->city)->first(); //市
            $district = DB::table('ecs_region')->select('region_name')->where('region_id', $v->district)->first(); //区
            $addres = !empty($province) ? $province->region_name : '';
            $addres .=!empty($city) ? $city->region_name : '';
            $addres.=!empty($district) ? $district->region_name : '';
            $list[$k]['area'] = $addres;
            $list[$k]['address_id'] = $v->address_id;
            $list[$k]['province'] = $v->province;
            $list[$k]['city'] = $v->city;
            $list[$k]['district'] = $v->district;
            $list[$k]['consignee'] = $v->consignee;
            $list[$k]['address'] = $v->address;
            $list[$k]['mobile'] = $v->mobile;
            $list[$k]['is_default'] = $v->is_default;
        }
        return $this->success($list, '地址列表');
    }

    /**
     * 设置默认地址
     * @param \Illuminate\Http\Request $request
     */
    public function putUpdate(Request $request) {
        $user_id = $request->get('user_id');
        $address_id = $request->get('address_id');
        $is_edit = DB::table('ecs_user_address')->where('user_id', $user_id)->update(['is_default' => 0]); //全部取消默认
        $is_default = DB::table('ecs_user_address')->where('address_id', $address_id)->update(['is_default' => 1]); //指定的设置为默认地址
        if ($is_default && $is_edit) {
            return $this->success(null, '默认地址设置成功！');
        } else {
            return $this->success(null, '默认地址设置失败！');
        }
    }
	
	 /**
     * 微信设置默认地址
     * @param \Illuminate\Http\Request $request
     */
    public function getUpdate(Request $request) {
        $user_id = $request->get('user_id');
        $address_id = $request->get('address_id');
        $is_edit = DB::table('ecs_user_address')->where('user_id', $user_id)->update(['is_default' => 0]); //全部取消默认
        $is_default = DB::table('ecs_user_address')->where('address_id', $address_id)->update(['is_default' => 1]); //指定的设置为默认地址
        if ($is_default && $is_edit) {
            return $this->success(null, '默认地址设置成功！');
        } else {
            return $this->success(null, '默认地址设置失败！');
        }
    }

    /**
     * 删除地址
     * @param type $param
     */
    public function postDestroy(Request $request) {
        $user_id = $request->get('user_id');
        $address_id = $request->get('address_id');
        $data = DB::table('ecs_user_address')->where('address_id', $address_id)->where('user_id', $user_id)->first();
        $is_dels=DB::table('ecs_user_address')->where('user_id', $user_id)->where('address_id', $address_id)->delete();
        if(!empty($data)&&$data->is_default){//删除的是默认地址 吧用户的另外一个地址设置为默认
             $user_data = DB::table('ecs_user_address')->where('user_id', $user_id)->first();
             if(!empty($user_data)){
                 DB::table('ecs_user_address')->where('address_id', $user_data->address_id)->update(['is_default' => 1]); //指定的设置为默认地址 
             }
        }
        if ($is_dels) {
            return $this->success(null, '删除成功！');
        } else {
            return $this->success(null, '失败！');
        }
    }

}
