<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;

class Login extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:login';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $salt ='wokecn';
        $n = 18816888831;
        do {
            $data['parent_id'] = 7871;
            $data['user_name'] = rand();
            $data['password'] = md5(md5(123456).$salt);
            $data['ec_salt'] = $salt;
            $data['mobile_phone'] = $n;
            $data['reg_time'] = time();
            User::create($data);
            $n++;
        } while ($n<=18816888869);
    }
}
