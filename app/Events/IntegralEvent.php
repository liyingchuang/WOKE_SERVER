<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class IntegralEvent extends Event
{
    use SerializesModels;
    public $uid;
    public $user_money;
    public $change_type;
    /**
     * 用户酒币变动接口
     * @return void
     */
    public function __construct($uid, $user_money,$msg, $change_type=1)
    {
        $this->uid=$uid;
        $this->user_money=$user_money;
        $this->change_type=$change_type;
        $this->msg=$msg;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
