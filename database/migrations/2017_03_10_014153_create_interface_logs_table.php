<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInterfaceLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('woke_interface_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('api_name',30);
            $table->text('reauest_body')->comment('请求参数');
            $table->string('reauest_header')->comment('请求头');
            $table->text('response')->comment('返回数据');
            $table->string('input_time',15)->comment('开始时间');
            $table->string('run_time',16)->comment('执行时间');
            $table->string('output_time',15)->comment('输出时间');
            $table->string('ip',15)->default('0.0.0.0')->comment('操作ip');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('woke_interface_logs');
    }
}
