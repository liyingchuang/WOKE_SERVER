<?php

namespace App\Listeners;

use App\Events\BonusEvent;
use App\Events\LovelEvent;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class BonusEventListener
{
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
     * @param  BonusEvent $event
     * @return void
     */
    public function handle(BonusEvent $event)
    {
        $bonus = $event->order->bonus;//去除不参与返现的订单总价
        $user_id = $event->order->user_id;
        $order_id = $event->order->order_id;
        $order_sn = $event->order->order_sn;
        // 1.计算自己级别
        $user = User::select('user_rank','user_id','parent_id')->where('user_id', $user_id)->first();
        event(new LovelEvent($user));
        // 1.1 查询我的级别 b
        $b = User::select('user_rank','user_id','parent_id')->where('user_id', $user_id)->first();
        if($user->user_rank<>$b->user_rank){//我的级别变化了去算我上级的级别
            // 2.计算我的上级的级别
            $parent=User::select('user_rank','user_id','parent_id')->where('user_id',$user->parent_id)->first();
            $this->childern($parent);
        }
     // 3,计算本订单提成人及提成金额


    }

    /**
     * 计算我以上级别的的级别
     * @param $user
     */
    private function childern($user){
        event(new LovelEvent($user));
        $u=User::select('user_rank','user_id','parent_id')->where('user_id',$user->user_id)->first();
      //  if($user->parent_id==0||($user->user_rank>=3&&$user->user_rank==$u->user_rank)){
        if($user->parent_id==0||($user->user_rank==$u->user_rank)){
            return true;
        }
        $parent=User::select('user_rank','user_id','parent_id')->where('user_id', $user->parent_id)->first();
        $this->childern($parent);
    }
}
