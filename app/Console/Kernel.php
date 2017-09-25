<?php

namespace App\Console;

use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\Login::class,
        \App\Console\Commands\OrderAddress::class,
        \App\Console\Commands\Address::class,
        \App\Console\Commands\Refund::class,
        \App\Console\Commands\Inspire::class,
        Commands\Market::class,
        Commands\FinanceConsole::class,
        Commands\ShanShanConsole::class,
        Commands\import::class,
    ];

    /**
     * Define the application's command schedule.
     *http://www.tuicool.com/articles/r2UriiV
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('inspire')
                 ->daily();
    }
}
