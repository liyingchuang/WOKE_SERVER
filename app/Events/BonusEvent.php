<?php

namespace App\Events;

use App\Events\Event;
use App\OrderInfo;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BonusEvent extends Event
{
    use SerializesModels;
    public $order;
    /**
     * 返酒币.
     *
     * @return void
     */
    public function __construct(OrderInfo $order)
    {
            $this->order=$order;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [1,2];

    }
}
