<?php

namespace App\Listeners;

use App\Events\LovelEvent;
use App\OrderInfo;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LovelEventListener
{
    private $number=0;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  LovelEvent  $event
     * @return void
     */
    public function handle(LovelEvent $event)
    {
        $user=$event->user;
        $user_id=$user->user_id;
        $userleval=$event->user->user_rank;//当前级别
        if($userleval==0){// 普通升专员
            $bonus= OrderInfo::where('user_id',$user_id)->where('pay_status',2)->sum('order_amount');
            if($bonus>=350){
                $event->user->user_rank=1;
                $event->user->save();
            }
        }
        if($userleval==1){// 专员升高专
            $A= User::where('parent_id',$user_id)->where('user_rank',1)->count();// 直接有效专员户数
            $this->number=0;
            $ateam=$this->childern($user,1,7);// 不包含自己 团队专员数
            if($A>=3&&$ateam){
                $event->user->user_rank=2;
                $event->user->save();
            }
        }
        if($userleval==2){// 高专升经理
            $A= User::where('parent_id',$user_id)->where('user_rank','>',0)->count();// 直接有效增员
            $B= User::where('parent_id',$user_id)->where('user_rank',2)->count();// 直接有效高专户数
            $this->number=0;
            $ateam=$this->childern($user,2,3);// 不包含自己 团队高专数
            if($A>=29||($A>=5&&$B>=2&&$ateam)){
                $event->user->user_rank=3;
                $event->user->save();
            }
        }
        $A= User::where('parent_id',$user_id)->where('user_rank',3)->count();// 直接有效经理数
        if($userleval==3&&$A==1){// 经理升三级总监
                $event->user->user_rank =4;
                $event->user->save();
        }
        if($userleval==4&&$A==2){// 经理升三级总监
                $event->user->user_rank =5;
                $event->user->save();
        }
        if($userleval==5&&$A==2){// 经理升三级总监
            $event->user->user_rank =6;
            $event->user->save();
        }
      //  $this->info('LovelEventListener');
        return true;
    }

    /**
     * 计算团队某级别用户数
     * @param $user
     * @param $type
     * @param $num
     */
    private function childern($user,$type,$num){
       $b=User::where('parent_id',$user->user_id)->where('user_rank',$type)->get();// 直接有效高专户数
       $count=count($b);
        if(!$count){
            return true;
        }
        $this->number= $this->number+$count;
        if($num<=$this->number){
            return true;
        }else{
            foreach ($b as $K=>$v){
                 return     $this->childern($v,$type,$num);
            }
        }
    }
}
