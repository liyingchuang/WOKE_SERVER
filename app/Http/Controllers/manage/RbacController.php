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
    public function getIndex() {
        $role = DB::table('roles')->get();
        return view('manage.rbac.index')->with(['role' => $role]);
    }

    /*
     * 角色分配权限列表
     */
    public function getShow(Request $request) {
        $role_id = $request->get('role_id', 0);
        $array = array();
        $role = DB::table('roles') ->select('id', 'display_name') -> where('id', $role_id) ->first();
        $permission = DB::table('permissions') -> get();
        $role_array = DB::table('permission_role') ->select('permission_id') -> where('role_id', $role_id) -> get();
        foreach ($role_array as $k => $v) {
            $array[$k]['permission_id'] = $v->permission_id;
        }
        $role_array = array_column($array, 'permission_id');
        return view('manage.rbac.role', ['role'=>$role, 'role_array'=>$role_array, 'permission'=>$permission]);
    }

    /*
     * 保存角色权限
     */
    public function getCreate() {
        $role_id=$_GET["role_id"];
        DB::table('permission_role')->where('role_id', $role_id)->delete();
        foreach($_GET['chk_role'] as $value){
            DB::insert('insert into permission_role (role_id, permission_id) values ("'.$role_id.'", "'.$value.'")');
        }
        return redirect("manage/rbac/show?role_id=$role_id");
    }

    /*
     * 查看角色下的用户
     */
    public function getUsers($role_id)
    {
        //if ($role_id == 5) {
        //        return redirect("manage/store");
        //} else {
            $main = DB::table('roles') -> where('id', $role_id) -> first();
            $admin = DB::table('woke_admin_user')->select("user_id", "user_name", "email", "add_time")->where('role_id', $role_id)->get();
            foreach ($admin as $k => $v) {
                $admin[$k]->add_time = date('Y-m-d H:i', $v->add_time);
            }
            return view('manage.rbac.admin', ['main'=>$main, 'admin' => $admin,]);
        //}
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
    public function postAddadmin(Request $request) {
        $mathematical = rand(1000,9999);
        $role_id = $request->get('role_id');
        $admin_data['user_name']=$request->get('user_name');
        $admin_data['email']=$request->get('user_email');
        $admin_data['password']=md5(md5($request->get('user_password')).$mathematical);
        $admin_data['ec_salt']=$mathematical;
        $admin_data['add_time']=strtotime(date("Y-m-d H:i:s",time()));
        $admin_data['action_list']= "all";
        $admin_data['role_id']= $role_id;
        $id = DB::table('woke_admin_user')->insertGetId($admin_data);
        DB::table('role_user')->insert(['user_id'=>$id,'role_id'=>$role_id]);
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
    public function getAdmindel(Request $request)
    {
        $user_id = $request->get('user_id');
        $id = $request->get('id');
        DB::table('woke_admin_user')->where('user_id', $user_id)->delete();
        DB::table('role_user')->where('user_id', $user_id)->delete();
        return redirect("manage/rbac/users/$id");
    }

}
