<?php

namespace App\Http\Controllers\manage;

use App\Http\Controllers\ManageController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\User;

class StatisticsController extends ManageController
{

    /**
     * 统计管理
     *
     */
    public function index(Request $request)
    {
        $start_time = $request->get('start_time', null);
        $end_time = $request->get('end_time', null);
        $type = $request->get('type', 'show');
        $stac = DB::table('woke_statistics')->get();
        if ($start_time && $end_time) {
            switch ($type) {
                case 'show':
                    $end_time = date("Ymd", strtotime("$end_time"));
                    $start_time = date("Ymd", strtotime("$start_time"));
                    $info = $this->statistics($end_time, $start_time);
                    $showdata = $info[0];
                    $detailed_info = $info[1];
                    return view('manage/statistics/index')->with(['type' => $type, 'showdata' => $showdata, 'stac' => $stac, 'detailed_info' => $detailed_info]);
                    break;
            }
        } else {
            switch ($type) {
                case 'show':
                    $info = $this->statistics(date("Ymd"),date("Ymd",strtotime('-7days')));
                    $showdata = $info[0];
                    $detailed_info = $info[1];
                    return view('manage/statistics/index')->with(['type' => $type, 'showdata' => $showdata, 'stac' => $stac, 'detailed_info' => $detailed_info]);
                    break;
                case 'playlist':
                    $created_at = $request->get('created_at', null);
                    if($created_at){
                        $date = $created_at;
                        $enddate = date("Y-m-d", strtotime("$created_at +1 days"));
                    }else{
                        $date = date('Y-m-d');
                        $enddate = date('Y-m-d',strtotime("+1 day"));
                    }
                    $show_message = DB::table('ecs_users_show_message')->select('user_id', DB::raw('count(*) as today'))->where('types', '<', '3')->where('created_at', '>=', $date)->where('created_at', '<', $enddate)->groupBy('user_id')->orderBy(DB::raw('count(*)'), 'desc')->paginate(50);
                    $result_message = [];
                    foreach ($show_message as $key => $value) {
                        $u = User::where('user_id', $value->user_id)->select('user_id', 'user_name', 'user_rank', 'headimg', 'flag', 'is_v')->first();
                        $count = DB::table('ecs_users_show_message')->where('user_id', $value->user_id)->where('types', '<', '3')->count();
                        $u->count = $count;
                        $value->user = $u;
                        $value->order = $key + 1;
                        $result_message[$key] = $value;
                    }
                    return view('manage/statistics/index')->with(['type' => $type, 'stac' => $stac, 'result_message' => $result_message]);
                    break;
            }
        }
    }

    private function statistics($end_time,$start_time)
    {
        $showdata = [];
        $info_show_statistics = DB::table('ecs_show_statistics')->where('createtime', '>=', $start_time)->where('createtime', '<=', $end_time)->get();
        $d=[];
        foreach($info_show_statistics as  $v){
            $key=date("Ymd", strtotime("$v->createtime"));
            $d[$key]=$v;
        }
        for ($i = $start_time; $i <= $end_time; $i = date("Ymd",strtotime("$i +1 days"))) {
            if(empty($d[$i])){
                $show[] = [$i, 0];
                $likes[] = [$i, 0];
                $tags[] = [$i, 0];
                $show_user[] = [$i, 0];
                $tag_user[] = [$i, 0];
                $like_user[] = [$i, 0];
                $detailed['time'] = date("Y年m月d日", strtotime("$i"));
                $detailed['count_show'] = 0;
                $detailed['count_tag'] = 0;
                $detailed['count_like'] = 0;
                $detailed['show_user'] = 0;
                $detailed['tag_user'] = 0;
                $detailed['like_user'] = 0;
                $detailed_info[] = (object)$detailed;
            }else{
                $show[] = [$i, $d[$i]->count_show];
                $likes[] = [$i, $d[$i]->count_tag];
                $tags[] = [$i, $d[$i]->count_like];
                $show_user[] = [$i, $d[$i]->show_user];
                $tag_user[] = [$i, $d[$i]->tag_user];
                $like_user[] = [$i, $d[$i]->like_user];
                $detailed['time'] = date("Y年m月d日", strtotime("$i"));
                $detailed['count_show'] = $d[$i]->count_show;
                $detailed['count_tag'] = $d[$i]->count_tag;
                $detailed['count_like'] = $d[$i]->count_like;
                $detailed['show_user'] = $d[$i]->show_user;
                $detailed['tag_user'] = $d[$i]->tag_user;
                $detailed['like_user'] = $d[$i]->like_user;
                $detailed_info[] = (object)$detailed;
            }
        }
            $showdata['show'] = str_replace('"', "", json_encode($show));
            $showdata['likes'] = str_replace('"', "", json_encode($likes));
            $showdata['tags'] = str_replace('"', "", json_encode($tags));
            $showdata['show_user'] = str_replace('"', "", json_encode($show_user));
            $showdata['tag_user'] = str_replace('"', "", json_encode($tag_user));
            $showdata['like_user'] = str_replace('"', "", json_encode($like_user));
            return [$showdata,$detailed_info];
        }

    }
