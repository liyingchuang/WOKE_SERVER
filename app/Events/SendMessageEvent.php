<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SendMessageEvent extends Event
{
    use SerializesModels;
    public $uid;
    public $title;
    public $msg;
    public $type;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($uid,$title,$msg,$type=0)
    {
        $this->uid=$uid;
        $this->title=$uid;
        $this->msg=$msg;
        $this->type=$type;
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
