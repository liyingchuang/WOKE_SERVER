<?php

namespace App\Listeners;

use App\AccountLog;
use App\Events\Event;
use App\Events\IntegralEvent;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;

class IntegralEventListener extends Event
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
     * @param  IntegralEvent  $event
     * @return void
     */
    public function handle(IntegralEvent $event)
    {
     //DB::beginTransaction();
        DB::transaction(function () use ($event) {
            AccountLog::create(['user_id' => $event->uid, 'user_money' => $event->user_money, 'frozen_money' => 0.00, 'rank_points' => 0, 'pay_points' => 0, 'change_time' => time(), 'change_desc' => $event->msg, 'change_type' => $event->change_type]);
            // $user=User::select('user_money','frozen_money')->where('user_id',$event->uid)->first();
            //1.计算可用酒币
            $user_money = AccountLog::where('user_id', $event->uid)->sum('user_money');
            //  2.计算消费酒币
            $frozen_money = AccountLog::where('user_id', $event->uid)->where('user_money', '<', 0)->sum('user_money');
            User::where('user_id', $event->uid)->update(['user_money' => $user_money, 'frozen_money' => abs($frozen_money)]);
        });
       // DB::commit();
        $title=$event->user_money>0?'获得酒币':'消费酒币';

        $this->sendMessage($event->uid, [],$title,$event->msg.$event->user_money,0);
    }
}
