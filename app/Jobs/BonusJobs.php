<?php

namespace App\Jobs;

use App\Events\IntegralEvent;
use App\Events\LovelEvent;
use App\Jobs\Job;
use App\OrderInfo;
use App\User;
use App\UserOrderAwardLog;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class BonusJobs extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    public $order;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(OrderInfo $order)
    {
         $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $bonus = $this->order->goods_amount;//去除不参与返现的订单总价
        $user_id = $this->order->user_id;
        $order_id = $this->order->order_id;
        $order_amount = $this->order->order_amount;
        //$order_sn = $this->order->order_sn;
        info('BonusJobs->handle'.$bonus);
        info('BonusJobs->handle'.$user_id);
        info('BonusJobs->handle'.$order_id);
        info('BonusJobs->handle'.$order_amount);
        // 1.计算自己级别
        $user = User::select('user_rank','user_id','parent_id')->where('user_id', $user_id)->first();
        event(new LovelEvent($user));
        // 1.1 查询我的级别 b
        $b = User::select('user_rank','user_id','parent_id')->where('user_id', $user_id)->first();
        //if($user->user_rank<>$b->user_rank){//我的级别变化了去算我上级的级别
            //$this->info('ok'.$b->user_rank);
            // 2.计算我的上级的级别
            $p=User::select('user_rank','user_id','parent_id')->where('user_id',$user->parent_id)->first();
            $this->childern($p);
       // }
        // 3,计算本订单提成人及提成金额
        $data=[];
        $data['order_id']=$order_id;
        $data['bonus']=$bonus;
        $data['user_id']=$user_id;
        // 3.1  推荐奖：直接上级
        $parent = User::select('user_rank','user_id','parent_id')->where('user_id', $b->parent_id)->first();
        if($parent->user_rank>0){ //推荐奖
            $data['recommendation_user_id']=$parent->user_id;
            $data['recommendation_price']=$bonus*0.05;
        }
        // 3.2 团队奖：高专奖、经理奖；购物人向上（含本人）的最近一个高专或经理（含）以上
        if ($bonus> 0) {//如果商品不参与分成就不返给各级别的酒币了
            $threeprize_parent = 0;
            if ($b->user_rank == 2) {//高专奖
                $data['academy_user_id'] = $b->user_id;
                $data['academy_price'] = $bonus * 0.03;
            }
            if ($b->user_rank == 3) {//经理奖
                $threeprize_parent = $b->parent_id;
                $data['manager_user_id'] = $b->user_id;
                $data['manager_price'] = $bonus * 0.03;
            }
            if (empty($data['academy_user_id'])) { //空
                $bb = $this->lately_lovel($parent, 2);
                if (!empty($bb)) {//高专奖
                    $data['academy_user_id'] = $bb->user_id;
                    $data['academy_price'] = $bonus * 0.03;
                }
            }
            if (empty($data['manager_user_id'])) {//
                $manager = $this->lately_lovel($b, 3);
                if (!empty($manager)) {//经理奖
                    $threeprize_parent = $manager->parent_id;
                    $data['manager_user_id'] = $manager->user_id;
                    $data['manager_price'] = $bonus * 0.03;
                } else {
                    $manager = $this->lately($b);
                    $threeprize_parent = $manager->parent_id;
                    $data['manager_user_id'] = $manager->user_id;
                    $data['manager_price'] = $bonus * 0.03;
                }
            }
            // 3.3育成奖：经理奖的直接上级，若是总监，则拿
            $three = User::select('user_rank', 'user_id', 'parent_id')->where('user_id', $threeprize_parent)->first();
            if (!empty($three) && $three->user_rank == 4) {
                $data['breeding_user_id'] = $three->user_id;
                $data['breeding_price'] = $bonus * 0.03;
            }
            if (!empty($three) && $three->user_rank == 5) {
                $data['breeding_user_id'] = $three->user_id;
                $data['breeding_price'] = $bonus * 0.04;
            }
            if (!empty($three) && $three->user_rank == 6) {
                $data['breeding_user_id'] = $three->user_id;
                $data['breeding_price'] = $bonus * 0.05;
            }
            $awardLog = UserOrderAwardLog::where('order_id', $order_id)->first();
            if (empty($awardLog)) {
                $awardLog = UserOrderAwardLog::create($data);
            }
            if (!empty($awardLog) && $awardLog->recommendation_user_id && $awardLog->recommendation_price) {
                event(new IntegralEvent($awardLog->recommendation_user_id, $awardLog->recommendation_price, '用户您好，您的下级消费金额'.$order_amount.'元，获得酒币', 1));
            }
            if (!empty($awardLog) && $awardLog->academy_user_id && $awardLog->academy_price) {
                event(new IntegralEvent($awardLog->academy_user_id, $awardLog->academy_price, '用户您好，您的下级消费金额'.$order_amount.'元，获得酒币', 1));
            }
            if (!empty($awardLog) && $awardLog->manager_user_id && $awardLog->manager_price) {
                event(new IntegralEvent($awardLog->manager_user_id, $awardLog->manager_price, '用户您好，您的下级消费金额'.$order_amount.'元，获得酒币', 1));
            }
            if (!empty($awardLog) && $awardLog->breeding_user_id && $awardLog->breeding_price) {
                event(new IntegralEvent($awardLog->breeding_user_id, $awardLog->breeding_price, '用户您好，您的下级消费金额'.$order_amount.'元，获得酒币', 1));
            }
        }
    }

    /**
     * 计算我以上级别的的级别
     * @param $user
     */
    private function childern($user){
        event(new LovelEvent($user));
        $u=User::select('user_rank','user_id','parent_id')->where('user_id',$user->user_id)->first();
        if($user->parent_id==0||($user->user_rank>=3&&$user->user_rank==$u->user_rank)){
       // if($user->parent_id==0||($user->user_rank==$u->user_rank)){//如果无上级 或者上级没变化就终止遍历
            return true;
        }else{
            $parent=User::select('user_rank','user_id','parent_id')->where('user_id', $user->parent_id)->first();
            return  $this->childern($parent);
        }

    }

    /**
     * 查最近的 高专或经理
     * @param $user
     * @param $love
     */
    private function lately_lovel($user,$love){
        if($user->parent_id==0){
            return 0;
        }
        if($user->user_rank==$love){
           return $user;
        }else{
           $u=User::select('user_rank','user_id','parent_id')->where('user_id',$user->parent_id)->first();
           return  $this->lately_lovel($u,$love);
        }
    }
    /**
     * 查最近的总监
     * @param $user
     * @param $love
     */
    private function lately($user){
        if($user->parent_id==0){
            return 0;
        }
        if($user->user_rank==4||$user->user_rank==5||$user->user_rank==6) {
            return $user;
        }else{
            $u=User::select('user_rank','user_id','parent_id')->where('user_id',$user->parent_id)->first();
            return  $this->lately($u);
        }
    }
}