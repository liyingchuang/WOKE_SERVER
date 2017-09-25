<?php

namespace App\Http\Controllers\client\Response\V1;

use App\Http\Controllers\client\Response\BaseResponse;
use App\Http\Controllers\client\Response\InterfaceResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddressController extends BaseResponse implements InterfaceResponse
{
    public function __construct()
    {
        $this->except = ['express'];
    }
    public function express(Request $request){
       $type= $request->get('type');
       $postid=$request->get('postid');
       $http='http://www.kuaidi100.com/query?type='.$type.'&postid='.$postid;
       $content=file_get_contents($http);
        return $this->success($content, '地址列表');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user_id = $request->get('user_id');
        $address_list = DB::table('woke_user_address')->where('user_id', $user_id)->orderBy('is_default', 'desc')->get();
        return $this->success($address_list, '地址列表');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request)
    {
        $consignee = $request->get('consignee');
        $mobile = $request->get('mobile');
        $user_id = $request->get('user_id');
        $province = $request->get('province');
        $city = $request->get('city');
        $district = $request->get('district');
        $address = $request->get('address');
        $is_default = $request->get('is_default', 0);
        $users = DB::table('woke_user_address')->where('user_id', $user_id)->get();
        if (empty($users)) {//如果不是默认
            $is_default = 1;
        } else {
            if ($is_default) {
                DB::table('woke_user_address')->where('user_id', $user_id)->update(['is_default' => 0]); //全部取消默认
            }
        }
        $data = [
            'user_id' => $user_id,
            'country' => 1,
            'province' => $province,
            'city' => $city,
            'district' =>$district,
            'address' => $address,
            'consignee' => $consignee,
            'email' => '',
            'mobile' => $mobile,
            'best_time' => '',
            'sign_building' => '',
            'zipcode' => '',
            'is_default' => $is_default,
        ];
        $is_edit = DB::table('woke_user_address')->insert($data);
        if ($is_edit) {
            return $this->success(null, '添加成功');
        } else {
            return $this->error(null, '添加失败');
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $consignee = $request->get('consignee');
        $mobile = $request->get('mobile');
        $user_id = $request->get('user_id');
      //  $province = $request->get('province');
       // $city = $request->get('city');
       // $district = $request->get('district');
        $address = $request->get('address');
        $is_default = $request->get('is_default', 0);
        $users = DB::table('woke_user_address')->where('user_id', $user_id)->get();
        if (empty($users)) {//如果不是默认
            $is_default = 1;
        } else {
            if ($is_default) {
                DB::table('woke_user_address')->where('user_id', $user_id)->update(['is_default' => 0]); //全部取消默认
            }
        }
        $data = [
            'user_id' => $user_id,
            'country' => 1,
            'province' => 1,
            'city' => 1,
            'district' => 1,
            'address' => $address,
            'consignee' => $consignee,
            'email' => '',
            'mobile' => $mobile,
            'best_time' => '',
            'sign_building' => '',
            'zipcode' => '',
            'is_default' => $is_default,
        ];
        $is_edit = DB::table('woke_user_address')->insert($data);
        if ($is_edit) {
            return $this->success(null, '添加成功');
        } else {
            return $this->error(null, '添加失败');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $user_id = $request->get('user_id');
        $address_id = $request->get('address_id');
        $data = DB::table('woke_user_address')->where('address_id', $address_id)->where('user_id', $user_id)->first();
        $is_dels=DB::table('woke_user_address')->where('user_id', $user_id)->where('address_id', $address_id)->delete();
        if(!empty($data)&&$data->is_default){//删除的是默认地址 吧用户的另外一个地址设置为默认
            $user_data = DB::table('woke_user_address')->where('user_id', $user_id)->first();
            if(!empty($user_data)){
                DB::table('woke_user_address')->where('address_id', $user_data->address_id)->update(['is_default' => 1]); //指定的设置为默认地址
            }
        }
        if ($is_dels) {
            return $this->success(null, '删除成功！');
        } else {
            return $this->success(null, '失败！');
        }
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $user_id = $request->get('user_id');
        $address_id = $request->get('address_id');
        $is_edit = DB::table('woke_user_address')->where('user_id', $user_id)->update(['is_default' => 0]); //全部取消默认
        $is_default = DB::table('woke_user_address')->where('address_id', $address_id)->update(['is_default' => 1]); //指定的设置为默认地址
        if ($is_default && $is_edit) {
            return $this->success(null, '默认地址设置成功！');
        } else {
            return $this->success(null, '默认地址设置失败！');
        }
    }


}
