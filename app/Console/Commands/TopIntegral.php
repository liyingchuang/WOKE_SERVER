<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\UserShowMessage;
use App\UserBanned;
use App\User;

class TopIntegral extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'top:integral';
    //ִ执行操作：php artisan top:integral

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Yesterday is list points statistics';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Request $request)
    {
        $in = array_flatten(UserBanned::select('user_id')->where('end_time','>',time())->get()->toArray());
        $list = DB::table('ecs_users_show_message')->select('user_id', DB::raw('count(*) as today'))->where('types', '<', '3')->where('created_at', '>=', date('Y-m-d',strtotime("-1 day")))->where('created_at', '<', date('Y-m-d'))->whereNotIn('user_id',$in)->groupBy('user_id')->orderBy(DB::raw('count(*)'), 'desc')->take(50)->get();
        if(!empty($list)){
            foreach ($list as $key => $value) {
                $u = User::where('user_id', $value->user_id)->select('user_id', 'user_name', 'user_rank', 'headimg', 'flag', 'is_v')->first();
                $count = UserShowMessage::where('user_id', $value->user_id)->where('types', '<', '3')->count();
                $u->count = $count;
                $value->user = $u;
                $value->order = $key + 1;
                $result[$key] = $value;
            }
            foreach($result as $key => $val){
                //添加几分
                $integral = new ApiController();
                $integral->integral_add($val->user_id,'top',$request->getClientIp());
            }
        }

        $lists = DB::table('ecs_users_show_message')->select('from_user_id', DB::raw('count(*) as today'))->where('types', '<', '3')->where('created_at', '>=', date('Y-m-d',strtotime("-1 day")))->where('created_at', '<', date('Y-m-d'))->whereNotIn('from_user_id',$in)->where('from_user_id','<>',0)->groupBy('from_user_id')->orderBy(DB::raw('count(*)'), 'desc')->take(50)->get();
        if(!empty($lists)){
            foreach ($lists as $key => $value) {
                $u = User::where('user_id', $value->from_user_id)->select('user_id', 'user_name', 'user_rank', 'headimg', 'flag', 'is_v')->first();
                $count = UserShowMessage::where('from_user_id', $value->from_user_id)->where('types', '<', '3')->count();
                $u->count = $count;
                $value->user = $u;
                $value->order = $key + 1;
                $results[$key] = $value;
            }
            foreach($results as $keys => $vals){
                //添加几分
                $integral = new ApiController();
                $integral->integral_add($vals->from_user_id,'top',$request->getClientIp());
            }
        }

    }
}
