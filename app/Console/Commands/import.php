<?php

namespace App\Console\Commands;

use App\Events\IntegralEvent;
use App\Jobs\BonusJobs;
use App\OrderInfo;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class import extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:import';

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
/*
        $olduser = DB::connection('mysql_beijing')->table('customers')->select
        ('referee_name', 'mobile', 'real_name', 'wxopenid', 'balance', 'headimgurl', 'password', 'address', 'province', 'city', 'create_time', 'level')->get();
        foreach ($olduser as $k => $v) {
            $rand = 'wokecn';
            $data['weixin_id'] = $v->wxopenid;
            $data['user_money'] = $v->balance/100;
            $data['user_name'] = $v->real_name;
            $data['real_name'] = $v->real_name;
            $data['headimg'] = $v->headimgurl ? $v->headimgurl : '';
            $data['password'] = MD5(MD5($v->password) . $rand);
            if(!empty($v->province)&&!empty($v->city)){
                $data['address'] = $v->province . '-' . $v->city . '-' . $v->address;
            }
            $data['reg_time'] = strtotime($v->create_time);
            $data['mobile_phone'] = $v->mobile;
            $level = 0;
            if ($v->level == 1) {
                $level = 1;
            }
            if ($v->level == -1) {
                $level = 2;
            }
            if ($v->level == 2) {
                $level = 3;
            }
            if ($v->level == 3) {
                $level = 4;
            }
            if ($v->level == 4) {
                $level = 5;
            }
            if ($v->level == 5) {
                $level = 6;
            }
            $data['user_rank'] = $level;
            $data['ec_salt'] = $rand;
            $newin = DB::table('woke_users')->insertGetId($data);
            if(!empty($v->province)&&!empty($v->city)) {
                $addres['user_id'] = $newin;
                $addres['consignee'] = $v->real_name;
                $addres['address'] = $v->province . '-' . $v->city . '-' . $v->address;
                $addres['mobile'] = $v->mobile;
                DB::table('woke_user_address')->insert($addres);
            }
            $log['user_id'] = $newin;
            $log['user_money'] = $v->balance/100;
            $log['change_time'] = time();
            $log['change_desc'] = date('Y-m-d H:i').'初始化酒币';
            DB::table('woke_account_log')->insert($log);
            $this->info('insert:' . $v->mobile);
        }
        $this->info('one over!');
        foreach ($olduser as $key => $value) {
            $parent = DB::table('woke_users')->where('mobile_phone', $value->referee_name)->first();
            if (empty($parent)) {
                $this->error('Error---------------------------------------------------------------------mobile:' . $value->mobile.'==parent_mobile:'.$value->referee_name);
                continue;
            }else{
                $this->error('mobile:' . $value->mobile);
                $credit_line=DB::connection('mysql_beijing')->table('buy_wine_log')->where('wxopenid',$value->wxopenid)->where('pay_flag','>',0)->sum('wine_sum');
                DB::table('woke_users')->where('mobile_phone', $value->mobile)->update(['parent_id' => $parent->user_id,'credit_line'=>$credit_line/100]);
            }

        }
        $this->info('two over!');

*/


     /*   $this->info('start');
        event(new IntegralEvent(11113,600.00,'亲爱的用户您好，您参与的第20170518期沪深300指，恭喜您竞猜成功，获得酒币奖励',5));
        $this->info('1');
        event(new IntegralEvent(10297,400.00,'亲爱的用户您好，您参与的第20170518期沪深300指，恭喜您竞猜成功，获得酒币奖励',5));
        $this->info('2');
        event(new IntegralEvent(11556,300.00,'亲爱的用户您好，您参与的第20170518期沪深300指，恭喜您竞猜成功，获得酒币奖励',5));
        $this->info('3');
        event(new IntegralEvent(10469,200.00,'亲爱的用户您好，您参与的第20170518期沪深300指，恭喜您竞猜成功，获得酒币奖励',5));
        $this->info('4');
        event(new IntegralEvent(7701,100.00,'亲爱的用户您好，您参与的第20170518期沪深300指，恭喜您竞猜成功，获得酒币奖励',5));
        $this->info('5');
        event(new IntegralEvent(10430,100.00,'亲爱的用户您好，您参与的第20170518期沪深300指，恭喜您竞猜成功，获得酒币奖励',5));
        $this->info('6');
        event(new IntegralEvent(7701,100.00,'亲爱的用户您好，您参与的第20170518期沪深300指，恭喜您竞猜成功，获得酒币奖励',5));
        $this->info('7');
        event(new IntegralEvent(11522,40.00,'亲爱的用户您好，您参与的第20170518期沪深300指，恭喜您竞猜成功，获得酒币奖励',5));
        $this->info('8');
        event(new IntegralEvent(2697,40.00,'亲爱的用户您好，您参与的第20170518期沪深300指，恭喜您竞猜成功，获得酒币奖励',5));
        $this->info('9');
        event(new IntegralEvent(10210,40.00,'亲爱的用户您好，您参与的第20170518期沪深300指，恭喜您竞猜成功，获得酒币奖励',5));
        $this->info('10');
        event(new IntegralEvent(1586,40.00,'亲爱的用户您好，您参与的第20170518期沪深300指，恭喜您竞猜成功，获得酒币奖励',5));
        $this->info('11');
        event(new IntegralEvent(12463,40.00,'亲爱的用户您好，您参与的第20170518期沪深300指，恭喜您竞猜成功，获得酒币奖励',5));
        $this->info('12');
        event(new IntegralEvent(10190,20.00,'亲爱的用户您好，您参与的第20170518期沪深300指，恭喜您竞猜成功，获得酒币奖励',5));
        $this->info('13');
        event(new IntegralEvent(8653,20.00,'亲爱的用户您好，您参与的第20170518期沪深300指，恭喜您竞猜成功，获得酒币奖励',5));
        $this->info('14');
        event(new IntegralEvent(11980,20.00,'亲爱的用户您好，您参与的第20170518期沪深300指，恭喜您竞猜成功，获得酒币奖励',5));
        $this->info('15');
        event(new IntegralEvent(11539,20.00,'亲爱的用户您好，您参与的第20170518期沪深300指，恭喜您竞猜成功，获得酒币奖励',5));
        $this->info('over');*/
     // event(new IntegralEvent(9991,0,'亲爱的用户您好，您在蜗壳商城购酒获得酒币奖励',3));
/*
        event(new IntegralEvent(12752,-50,'亲爱的用户您好，您在蜗壳商城购物退单获取',12));
        event(new IntegralEvent(9991,-50,'亲爱的用户您好，您在蜗壳商城购物退单获取',12));
        event(new IntegralEvent(9991,-50,'亲爱的用户您好，您在蜗壳商城购物退单获取',12));*/

    }
}
