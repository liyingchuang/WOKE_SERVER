<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $this->command->info('开始导入数据!');
        $this->call(377123_User_namesTableSeeder::class);
        $this->command->info('姓名数据导入完!');

        Model::reguard();
    }
}
