<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SundryingStatistics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sundrying:statistics {statistics=no}';
    //php artisan sundrying:statistics

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sun drying statistics all!Date format:【20160502】';

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
    public function handle()
    {
        $statistics = $this->argument('statistics');
        if($statistics == 'no')
            $time = date("Ymd",strtotime("-1 day"));
        else{
            $is_date=strtotime($statistics)?strtotime($statistics):false;
            if($is_date===false){
                $this->info('日期格式【20160502】');exit;
            }else{
                $time = date("Ymd",strtotime($statistics));
            }
        }
        $detailed['count_show'] = DB::table('ecs_show')->where(DB::raw("DATE_FORMAT(created_at,'%Y%m%d')"), '=', $time)->count();
        $detailed['count_tag'] = DB::table('ecs_show_tag')->where(DB::raw("DATE_FORMAT(created_at,'%Y%m%d')"), '=', $time)->count();
        $detailed['count_like'] = DB::table('ecs_show_tag_like')->where(DB::raw("DATE_FORMAT(created_at,'%Y%m%d')"), '=', $time)->count();
        $detailed['show_user'] = DB::table('ecs_show')->where(DB::raw("DATE_FORMAT(created_at,'%Y%m%d')"), '=', $time)->distinct()->count('user_id');
        $detailed['tag_user'] = DB::table('ecs_show_tag')->where(DB::raw("DATE_FORMAT(created_at,'%Y%m%d')"), '=', $time)->distinct()->count('user_id');
        $detailed['like_user'] = DB::table('ecs_show_tag_like')->where(DB::raw("DATE_FORMAT(created_at,'%Y%m%d')"), '=', $time)->distinct()->count('user_id');
        $detailed['createtime'] = $time;
        $info_statistics = DB::table('ecs_show_statistics')->where('createtime',$time)->first();
        if(empty($info_statistics))
            $statistics = DB::table('ecs_show_statistics')->insert($detailed);
        else
            $statistics = DB::table('ecs_show_statistics')->where('createtime',$time)->update($detailed);
        if($statistics)
            $this->info('ok');
        else
            $this->info('no');
    }
}
