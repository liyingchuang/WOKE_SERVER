<?php

namespace App\Console\Commands;

use App\Events\SendMessageEvent;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FinanceConsole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:finance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $e=date('Y年m月d日H点i分');
        $a = strtotime('-1 day 17:30:00', time());
        $s=date('Y年m月d日H点i分',$a);
        $p=User::sum('user_money');
        $time1 = strtotime(date('Y-m-d').'17:30:00');
        $x=DB::Table('woke_order_info')->where('pay_status',2)->where('add_time','>=',$a)->where('add_time','<',$time1)->sum('integral_money');;
        $statistics = DB::Table('woke_statistics')->insert(['now'=>time(), 'yesterday'=>strtotime(date('Y-m-d H:i', $a)), 'sum_money'=>$p, 'integral_money'=>$x]);
        event(new SendMessageEvent(10079,'露露李你的秘书提醒你','现在向你报告截止'.$e.'用户酒币总数为'.$p.'。'.$s.'到'. $e.'期间共有'. $x.'酒币购买商品。报告完毕',1));
        event(new SendMessageEvent(10079,'露露李你的秘书提醒你','现在向你报告截止'.$e.'用户酒币总数为'.$p.'。'.$s.'到'. $e.'期间共有'. $x.'酒币购买商品。报告完毕',1));
        event(new SendMessageEvent(10079,'露露李你的秘书提醒你','重要的事说三现在向你报告截止'.$e.'用户酒币总数为'.$p.'。'.$s.'到'. $e.'期间共有'. $x.'酒币购买商品。报告完毕',1));
        $this->info('重要的事说三现在向你报告截止'.$e.'用户酒币总数为'.$p.'。'.$s.'到'. $e.'期间共有'. $x.'酒币购买商品。报告完毕');

    }
}
