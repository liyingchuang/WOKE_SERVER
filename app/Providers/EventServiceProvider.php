<?php

namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\SendMessageEvent' => [
            'App\Listeners\SendMessageEventListener',
        ],
        'App\Events\IntegralEvent' => [
            'App\Listeners\IntegralEventListener',
        ],
        'App\Events\BonusEvent' => [
            'App\Listeners\BonusEventListener',
        ],
        'App\Events\LovelEvent' => [
            'App\Listeners\LovelEventListener',
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        //
    }
}
