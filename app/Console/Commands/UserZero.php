<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UserZero extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
   protected $signature = 'users:day_view_number';
    //php artisan users:day_view_number

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'The number of users page browsing time set 0';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $day_view_number = DB::table('ecs_users')->update(array('day_view_number' => '0'));
        if($day_view_number)
            $this->info('ok');
        else
            $this->info('no');
    }
}
