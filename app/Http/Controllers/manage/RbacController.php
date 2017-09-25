<?php

namespace App\Http\Controllers\manage;

use App\Rbac;
use Illuminate\Http\Request;
use App\Http\Controllers\ManageController;
use Illuminate\Support\Facades\DB;

/**
 *
 * RBAC权限管理
 */
class RbacController extends ManageController {

    /*
     * 角色列表显示
     */
    public function index() {
        $role = DB::table('ecs_role')->get();
        return view('manage.rbac.index')->with(['role' => $role]);
    }

    /*
     * 角色分配权限列表
     */
    public function show(Request $request) {
        $role_id = $request->get('role_id', 0);
        $role_resource=DB::table('ecs_role')->where('role_id', $role_id)->first();
        $role_array=DB::table('ecs_role')
            ->select("ecs_role_resource.resource_id")
            ->leftJoin('ecs_role_resource', 'ecs_role.role_id', '=', 'ecs_role_resource.role_id')
            ->where('ecs_role.role_id', $role_id)->get();
        $father = DB::table('ecs_resource')
            ->select('resource_name')
            ->where('resource_type', 0)->get();
        $role = DB::table('ecs_resource')
            ->select('resource_id','resource_name')
            ->where('resource_type', 0)->get();
        foreach($role as $value){
            $son = DB::table('ecs_resource')->where('resource_type', $value->resource_id)->get();
            $data[$value->resource_name]=$son;
        }
        foreach ($role_array as $k => $v) {
            $array[$k]['resource_id'] = $v->resource_id;
        }
        $role_array = array_column($array, 'resource_id');
        return view('manage.rbac.role',[
            'role_resource' => $role_resource,
            'role_array' => $role_array,
            'father' => $father,
            'data' => $data,
        ]);
    }

    /*
     * 保存角色权限
     */
    public function create() {
        $role_id=$_GET["role_id"];
        DB::table('ecs_role_resource')->where('role_id', $role_id)->delete();
        foreach($_GET["chk_role"] as $value){
            DB::insert('insert into ecs_role_resource (role_id, resource_id) values ("'.$role_id.'", "'.$value.'")');
        }
        return redirect("manage/rbac/role?role_id=$role_id");
    }

    /*
     * 查看角色下的用户
     */
    public function users($role_id)
    {
        if ($role_id == 5) {
                return redirect("manage/store");
        } else {
            $main = DB::table('ecs_role')->where('role_id', $role_id)->first();
            $admin = DB::table('ecs_role')
                ->select("woke_admin_user.user_name", "woke_admin_user.user_id", "woke_admin_user.email", "woke_admin_user.add_time")
                ->Join('woke_admin_role', 'ecs_role.role_id', '=', 'woke_admin_role.role_id')
                ->Join('woke_admin_user', 'woke_admin_role.admin_id', '=', 'woke_admin_user.user_id')
                ->where('ecs_role.role_id', $role_id)
				->get();
            foreach ($admin as $k => $v) {
                $admin[$k]->add_time = date('Y-m-d H:i', $v->add_time);
            }
            return view('manage.rbac.admin', [
                'main' => $main,
                'admin' => $admin,
            ]);
        }
    }

    /*
    * 角色列表显示
    */
    public function admin_users() {
        $role = DB::table('ecs_role')->get();
        return view('manage.rbac.admin');
    }

    /*
     * 超级用户邮箱验证
    */
    public function store() {
        $email = DB::table('ecs_users')->select("email")->where('email', $_POST['email'])->get();
        if($email){
            return 0;
            exit;
        }else{
            return 1;
            exit;
        }
    }

    /*
   * 超级用户添加
   */
    public function addadmin(Request $request) {
        $mathematical = rand(1000,9999);
        $role_id = $request->get('role_id');
        $admin_data['user_name']=$request->get('user_name');
        $admin_data['email']=$request->get('user_email');
        $admin_data['password']=md5(md5($request->get('user_password')).$mathematical);
        $admin_data['ec_salt']=$mathematical;
        $admin_data['add_time']=strtotime(date("Y-m-d H:i:s",time()));
        $admin_data['action_list']= "all";
        $id = DB::table('woke_admin_user')->insertGetId($admin_data);
        DB::table('woke_admin_role')->insert(['admin_id'=>$id,'role_id'=>$role_id]);
        return redirect("manage/rbac/users/$role_id");
    }

    /*
    * 角色列表显示
    */
    public function user_users() {
        $role = DB::table('ecs_role')->get();
        return view('manage.rbac.user');
    }

    /*
  * 第三方用户添加
  */
    public function addusers(Request $request) {
        $role_id = $request->get('role_id');
        $user_phone =$request->get('user_iphone');
        if($user_phone == "") {
            return redirect("manage/rbac/users/$role_id");
        }
        $user_info=DB::table('ecs_users')->select("user_id")->where('mobile_phone', $user_phone)->first();
        if(empty($user_info)) {
            return redirect("manage/rbac/users/$role_id");
        }
        $user_id = $user_info->user_id;
        DB::table('ecs_user_role')->where('user_id', '=', $user_id)->delete();
        DB::insert('insert into ecs_user_role (user_id, role_id) values ("'.$user_id.'", "'.$role_id.'")');
        return redirect("manage/rbac/users/$role_id");
    }

    /*
     * 第三方用户删除.
     */
    public function usersdel($id)
    {
        DB::table('ecs_users')->where('user_id', '=', $id)->delete();
        DB::table('ecs_user_role')->where('user_id', $id)->delete();
        return redirect("manage/rbac/users/5");

    }

    /*
     * 超级用户删除
     */
    public function admindel($id,$role_id)
    {
        DB::table('ecs_admin_user')->where('user_id', '=', $id)->delete();
        DB::table('ecs_admin_role')->where('admin_id', $id)->delete();
        return redirect("manage/rbac/users/$role_id");
    }

}
