<?php

namespace App\Console\Commands;

use App\Events\SendMessageEvent;
use App\GroupGoods;
use App\GroupInfo;
use App\GroupOpen;
use App\OrderInfo;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class Refund extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:refund';

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
        $endtime = time()-86400;
        // 定时成团
        $id = DB::table('woke_group_open')->select('group_id','goods_id')->where('start_time','<',time()-7200)->where('have','>',0)->where('group_status',0)->get();
        foreach ($id as $vid){
            $this->info('ok');
            //1.是团长免单跳过自动成团
            $groupgoods=GroupGoods::where('goods_id',$vid->goods_id)->where('head_free',1)->first();
            if($groupgoods){
              continue;
            }
            $info = DB::table('woke_group_info')->where('group_id', $vid->group_id)->where('pay_status', 2)->count();
            if($info){
                OrderInfo::where('parent_id', $vid->group_id)->where('pay_status', 2)->update(['extension_code'=>'group_success']);
                GroupOpen::where('group_id', $vid->group_id)->update(['group_status'=>1]);
               // $list=GroupInfo::where('group_id', $vid->group_id)->get();
                $list=GroupInfo::where('group_id', $vid->group_id)->where('tel','<>','188888888888')->get();
                foreach ($list as $k=>$v){
                    event(new SendMessageEvent($v->user_id,'订单提醒', '亲爱的用户您好，您已参与的团购已经成团',1));
                }
            }
        }

        //定时创建团
        $g=date('G');
        if($g>7&&$g<23){
            $list= GroupGoods::whereHas('goods', function ($query) {
                $query->where('is_on_sale', 1)->where('is_delete', 0);
            })->where('start_time', '<=', time())->where('end_time', '>=', time())->where('examine_status', 4)->where('head_free', 0)->get();
            foreach ($list as $K=>$v){
                $isopen=GroupOpen::where('goods_id', $v->goods_id)->where('group_status',0)->count();
                if(!$isopen){
                    $order_sn = 'S' . date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
                    GroupGoods::where('goods_id', $v->goods_id)->increment('ex_have');//add 1 hoe
                  //  $user_id=rand(100,10000);
                    $user= User::where('headimg','<>','')->orderBy(\DB::raw('RAND()'))->take(1)->get();
                    if($user){
                        $user_id= $user[0]->user_id;
                    }else{
                        $user_id=rand(100,10000);
                    }
                    $group = GroupOpen::create(['goods_id' => $v->goods_id, 'user_id' =>$user_id, 'supplier_id' => 0, 'start_time' => time(), 'have' => 1, 'group_status' => 0]);
                    //生成扩展订单
                    GroupInfo::create(['goods_id' =>  $v->goods_id, 'user_id' => $user_id, 'group_id' => $group->group_id, 'order_sn' => $order_sn, 'pay_status' => 2, 'supplier_id' => 0, 'consignee' => '系统', 'address' =>'系统自动创建团长', 'tel' => 188888888888, 'buy_number' => 1, 'pay_name' => 'WOKE', 'pay_time' => time(), 'order_amount' =>0.00, 'integral_amount' => 0.00, 'vat_inv_taxpayer_id' => 1]);
                }
            }
        }

        //删除昨天系统生成的没人参团的团长及订单

        //定时退款
     /*
        $id = DB::table('woke_group_open')->select('group_id')->where('start_time','<',$endtime)->where('have','>',0)->where('group_status',0)->get();
        foreach ($id as $vid){
           // DB::table('woke_group_open')->where('group_id', $vid->group_id)->update(['group_status'=>0]);
            $info = DB::table('woke_group_info')->where('group_id', $vid->group_id)->where('pay_status', 2)->get();
            foreach($info as $val){
                $debut = env('APP_DEBUG');
                if ($debut) {
                    $path = storage_path('pingxx/develop.pem');
                } else {
                    $path = storage_path('pingxx/release.pem');
                }
                \Pingpp\Pingpp::setApiKey(env('PINGXX_API_KEY'));
                \Pingpp\Pingpp::setPrivateKeyPath($path);
                $ch = \Pingpp\Charge::retrieve($val->vat_inv_company_name);//order_sn 是已付款的订单号
                $ch->refunds->create(
                    array(
                        'amount' => $val->order_amount*100,
                        'description' => $val->order_sn.'拼团失败退款'
                    )
                );
            }
        }
*/

    }
}
