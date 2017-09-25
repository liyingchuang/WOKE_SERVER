<?php

namespace App\Listeners;

use App\Events\Event;
use App\Events\SendMessageEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMessageEventListener extends Event
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SendMessageEvent  $event
     * @return void
     */
    public function handle(SendMessageEvent $event )
    {
        $this->sendMessage($event->uid, [],$event->title,$event->msg,$event->type);
    }
}
