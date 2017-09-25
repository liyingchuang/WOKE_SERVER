<?php

namespace App\Http\Controllers\manage;

use App\Http\Controllers\ManageController;
use App\User;
use Illuminate\Support\Facades\DB;
use App\Goods;

class HomeController extends ManageController {

    /**
     * 系统首页
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $showdata = [];
        $sale = Goods::where('is_on_sale', 2)->first();
        if (session('manage_role') == 'manage') {
           /* $date = date("Y-m-d", strtotime("-15 day"));
           // $list = DB::table('ecs_show')->select(DB::raw("DATE_FORMAT(created_at,'%Y%m%d') as created_at"), DB::raw('count(*) as shows'), DB::raw('sum(tag_size_count) as likes'))->where(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), '>', $date)->groupBy(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"))->orderBy(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), 'asc')->get();
            $show = "[";
            $likes = "[";
            foreach ($list as $key => $value) {
                $show.='[' . $value->created_at . ',' . $value->shows . '],';
                $likes.='[' . $value->created_at . ',' . $value->likes . '],';
            }
            $show = rtrim($show, ",");
            $likes = rtrim($likes, ",");
            $show.="]";
            $likes.="]";
            $showdata['show'] = $show;
            $showdata['likes'] = $likes;*/
        }
        $e=date('Y年m月d日H点i分');
        $a = strtotime('-1 day 17:30:00', time());
        $s=date('Y年m月d日H点i分',$a);
        $p=User::sum('user_money');
        $time1 = strtotime(date('Y-m-d').'17:30:00');
        $x=DB::Table('woke_order_info')->where('pay_status',2)->where('add_time','>=',$a)->where('add_time','<',$time1)->sum('integral_money');
        $msg= '重要的事说三现在向你报告截止'.$e.'用户酒币总数为'.$p.'。'.$s.'到'. $e.'期间共有'. $x.'酒币购买商品。报告完毕';
        return view('manage/home/index')->with(['msg' =>$msg, 'sale' =>$sale]);
    }

}
