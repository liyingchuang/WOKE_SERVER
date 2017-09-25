<?php

namespace App\Http\Controllers\manage;

use Illuminate\Http\Request;
use App\Http\Controllers\ManageController;
use Illuminate\Support\Facades\DB;
use App\User;
use App\UserBanned;
use App\UserShowMessage;
use App\UserReport;
use Maatwebsite\Excel\Excel;
use Illuminate\Support\Facades\Session;
use App\Events\IntegralEvent;

class UserController extends ManageController {

    /**
     * 用户管理
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $keyword = $request->get('keyword', null);
        $type = $request->get('type', 'all');
        if ($keyword) {
            switch ($type) {
                case 'artisan':
                    $list = DB::table('woke_users')->where('is_v', 1)->where('mobile_phone', 'like', "%$keyword%")->orWhere('user_id', 'like', "%$keyword%")->orWhere('user_name', 'like', "%$keyword%")->orderBy('user_id', 'desc')->paginate(15);
                    break;
                case 'official':
                    $list = DB::table('woke_users')->where('is_v', 2)->where('mobile_phone', 'like', "%$keyword%")->orWhere('user_id', 'like', "%$keyword%")->orWhere('user_name', 'like', "%$keyword%")->orderBy('user_id', 'desc')->paginate(15);
                    break;
                case 'zhu':
                    $list = DB::table('woke_users')->where('is_v', 3)->where('mobile_phone', 'like', "%$keyword%")->orWhere('user_id', 'like', "%$keyword%")->orWhere('user_name', 'like', "%$keyword%")->orderBy('user_id', 'desc')->paginate(15);
                    break;
                case 'shop':
                    $list = DB::table('woke_users')->where('is_v', 4)->where('mobile_phone', 'like', "%$keyword%")->orWhere('user_id', 'like', "%$keyword%")->orWhere('user_name', 'like', "%$keyword%")->orderBy('user_id', 'desc')->paginate(15);
                    break;
                default:
                    $list = DB::table('woke_users')->where('mobile_phone', 'like', "%$keyword%")->orWhere('user_id', 'like', "%$keyword%")->orWhere('user_name', 'like', "%$keyword%")->orderBy('user_id', 'desc')->paginate(15);
                    break;
            }
        } else {
            switch ($type) {
                case 'artisan':
                    $list = DB::table('woke_users')->where('is_v', 1)->orderBy('user_id', 'desc')->paginate(15);
                    break;
                case 'official':
                    $list = DB::table('woke_users')->where('is_v', 2)->orderBy('user_id', 'desc')->paginate(15);
                    break;
                case 'zhu':
                    $list = DB::table('woke_users')->where('is_v', 3)->orderBy('user_id', 'desc')->paginate(15);
                    break;
                case 'shop':
                    $list = DB::table('woke_users')->where('is_v', 4)->orderBy('user_id', 'desc')->paginate(15);
                    break;
                default:
                    $list = DB::table('woke_users')->orderBy('user_id', 'desc')->paginate(15);
                    break;
            }
        }
        foreach ($list as $k => $v) {
            $list[$k]->reg_time = date('Y-m-d H:i', $v->reg_time);
        }
        return view('manage/user/index')->with(['list' => $list, 'keyword' => $keyword, 'type' => $type]);
    }

    /**
     * 禁言
     * @param \Illuminate\Http\Request $request
     */
    public function postSave(Request $request) {
        $user_id = $request->get('user_id');
        $desc = $request->get('desc');
        $time = $request->get('time', 1);
        $data['user_id'] = $user_id;
        $data['desc'] = $desc;
        $data['start_time'] = time();
        $data['end_time'] = strtotime("$time days");
        $banned = UserBanned::where('user_id', $user_id)->first();
        if (empty($banned)) {
            UserBanned::create($data);
        } else {
            $banned->desc = $desc;
            $banned->start_time = time();
            $banned->end_time = strtotime("$time days");
            $banned->save();
        }
        $sdata['types'] = 6;
        $sdata['show_tag_id'] = 0;
        $sdata['from_user_id'] = 0;
        $sdata['user_id'] = $user_id;
        $sdata['show_id'] = 0;
        $sdata['message'] = '您由于' . $desc . '您被禁言' . $time . '天！暂时无法使用晒晒相关功能';
        $this->sendUserShowMessage($sdata);
        //发送消息
        return redirect('manage/user/banned');
    }

    /**
     * 发送禁言消息
     * @param type $data
     * @return boolean
     */
    private function sendUserShowMessage($data) {
        UserShowMessage::create($data);
        $result = strval(DB::table('ecs_users_show_message')->where('is_read', 0)->where('user_id', $data['user_id'])->count());
        $sys = strval(DB::table('ecs_users_show_message')->where('is_read', 0)->where('types', 6)->where('user_id', $data['user_id'])->count());
        $this->sendMessage($data['user_id'], ['type' => 'personal_tips', 'body' => $result, 'TYPE_PUSH_SYS_MSG' => $sys], '', 1);
        return true;
    }

    /**
     * 删除禁言
     * @param \Illuminate\Http\Request $request
     */
    public function destroy($id) {
        UserBanned::where('id', $id)->delete();
        echo 'ok';
        exit;
    }

    /**
     * 禁言用户列表
     */
    public function getBanned() {
        $list = UserBanned::with('user')->paginate(15);
        return view('manage/user/banned')->with(['list' => $list]);
    }

    /**
     * 编辑用户
     * @param type $id
     */
    public function edit($id) {
        $user = User::with('photos')->where('user_id', $id)->first();
        return view('manage/user/edit')->with(['info' => $user]);
    }

    /**
     * 用户积分明细
     * @param type $id
     */
    public function getIntegral($id) {
        $info_integral = DB::table('ecs_integral_user_detailed')->where('user_id', $id)->orderBy('id', 'desc')->paginate(15);
        $user = User::with('photos')->select('user_name','user_id')->where('user_id', $id)->first();
        $integral = $this->integral($id);
        $user->integral = $integral;
        foreach($info_integral as $key => $val){
            $info_integral[$key]->create_time = date("Y-m-d H:i:s",$val->create_time);
        }
        return view('manage/user/integral')->with(['info_integral' => $info_integral,'user' => $user]);
    }

    /**
     * 用户积分明细导出
     * @param type $id
     */
    public function postExcel(Excel $excel) {
        $user_id = $_POST["user_id"];
        $info_integral = DB::table('ecs_integral_user_detailed')->where('user_id', $user_id)->orderBy('id', 'desc')->get();
        $user = User::with('photos')->select('user_name')->where('user_id', $user_id)->first();
        $user -> integral =  $this->integral($user_id);
        foreach ($info_integral as $key => $value) {
            if($value->type == 0){
                $value->type = "减少";
            }else{
                $value->type = "增加";
            }
            $export[] = array(
                '编号' => $key+1,
                '状态' => $value->type,
                '积分' => $value->size,
                '介绍' => $value->desc,
                '时间' =>  date("Y-m-d H:i:s",$value->create_time),
            );
        }
        $tab_name = 'integral';
        $name = "$user->user_name 积分明细";
        $excel->create($name, function($excel) use ($export,$user) {
            $excel->sheet('export', function($sheet) use ($export,$user) {
                $sheet->setStyle(array(
                    'font' => array(
                        'name'      =>  'Calibri',
                        'size'      =>  12,
                        'bold'      =>  true
                    )
                ));
                $sheet->fromArray($export);
                $sheet->prependRow(array(
                    "$user->user_name 积分明细 总积分:$user->integral"
                ));
                $sheet->row(1, function($row) {
                    $row->setBackground('#FFFF00');
                });
                $sheet->mergeCells('A1:E1');
                $sheet->setAutoSize(true);
                $sheet->setSize(array(
                    'A1' => array(
                        'width'      =>  5,
                        'height'      =>  0
                    ),
                    'B1' => array(
                        'width'      =>  10,
                        'height'      =>  0
                    ),
                    'C1' => array(
                        'width'      =>  10,
                        'height'      =>  0
                    ),
                    'D1' => array(
                        'width'      =>  20,
                        'height'      =>  0
                    ),
                    'E1' => array(
                        'width'      =>  20,
                        'height'      =>  0
                    )
                ));
            });

        })->export('xls');
    }

    /**
     * 统计用户积分
     * @return $user_id
     */
    private function integral($user_id) {
        $integral_add = DB::table('ecs_integral_user_detailed')->where('user_id', $user_id)->where('type', 1)->groupBy('user_id')->sum('size');
        $integral_consumption = DB::table('ecs_integral_user_detailed')->where('user_id', $user_id)->where('type', 0)->groupBy('user_id')->sum('size');
        $integral = $integral_add - $integral_consumption;
        if($integral >= 0)
            return $integral;
        else
            return 0;
    }

    /**
     * 用户酒币明细
     * @param $user_id
     * @return $this
     */
    public function thewine(Request $request, $user_id) {
        Session()->put('user_id', $user_id);
        $increase = $request->get('increase');
        $reduce = $request->get('reduce');
        $reason = $request->get('reason');
        $type = $request->get('type');
        if($type == 1){
            event(new IntegralEvent(Session('user_id'), $increase, '用户您好，'.$reason.'，共计'.$increase.'元', 1));
            DB::table('woke_account_log')->where('user_id', Session('user_id'))->update(['operator'=>Session('manage_user_name')]);
        }
        if($type == 2){
            event(new IntegralEvent(Session('user_id'), - $reduce, '用户您好，'.$reason.'，共计'.-$reduce.'元', 1));
            DB::table('woke_account_log')->where('user_id', Session('user_id'))->update(['operator'=>Session('manage_user_name')]);
        }
        $account_info = DB::table('woke_account_log')->orderBy('log_id', 'desc')->where('user_id', $user_id)->paginate(15);
        $user_money = DB::table('woke_account_log')->where('user_id', $user_id)->sum('user_money');
        return view('manage.user.thewine')->with(['account_info' => $account_info, 'user_money' => $user_money]);
    }

    /**
     * 下级用户
     * @param $user_id
     * @return $this
     */
    public function subor($user_id){
        $parent = User::where('parent_id', $user_id)->get();
        return view('manage.user.subor')->with('parent', $parent);
    }

    /**
     * 是否匠人
     * @param type $id
     */
    public function show($id, Request $request) {
        $t = $request->get('t');
        if (!empty($t)) {//认证
            $store = DB::table('woke_users')->where('user_id', $id)->first();
            DB::table('woke_users')
                    ->where('user_id', $id)
                    ->update(['is_v' => $t]);
        } else {//是否在特殊位置显示
            $store = DB::table('woke_users')->where('user_id', $id)->first();
            $enabled = 0;
            if (!empty($store) && $store->status) {
                $enabled = 0;
            } else {
                $enabled = 1;
            }
            DB::table('woke_users')
                    ->where('user_id', $id)
                    ->update(['status' => $enabled]);
        }
    }

    /**
     *  编辑用户
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //修改用户名
        $pk = $request->get('pk');
        $name = $request->get('name');
        if ($name == 'user_name') {
            $v = $request->get('value'); //
            $user = User::where('user_name', $v)->first();
            if (empty($user)) {
                User::where('user_id', $pk)->update(['user_name' => $v]);
                $result = ['status' => "success", 'msg' => '修改成功'];
                return response()->json($result);
            }
            $result = ['status' => "error", 'msg' => '修改失败！因为用户名已经存在'];
            return response()->json($result);
        }else if($name == 'search_sort_order'){
            $v = $request->get('value'); //
            User::where('user_id', $pk)->update(['search_sort_order' => $v]);
            $result = ['status' => "success", 'msg' => '修改成功'];
            return response()->json($result);
        }
        //编辑用户其它信息
        $user_id = $request->get('user_id');
        $about = $request->get('about');
        $files = $request->get('file_name');
        $user = User::with('photos')->where('user_id', $user_id)->first();
        if (!empty($user)) {
            User::where('user_id', $user_id)->update(['about' => $about, 'image' => $files]);
        }
        return redirect('/manage/user');
    }

    /**
     * ajax 检查用户是否存在
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        $tel = $request->get('tel');
        $user = User::where('mobile_phone', $tel)->orwhere('email', $tel)->first();
        if (!empty($user)) {
            return response()->json(['valid' => TRUE]);
        } else {
            return response()->json(['valid' => false]);
        }
    }
    public function postReport(Request $request){
       $list= UserReport::with('user','from')->orderBy('id', 'desc')->paginate(15);
       return view('manage/user/report')->with(['list' => $list]);
    }

}
