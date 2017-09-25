<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMarketTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('woke_market', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->char('time', 10)->comment('期号')->index();
            $table->decimal('price',10,2)->comment('猜大盘价格');
            $table->decimal('profit',10,2)->default(0.0)->comment('收益');
            $table->tinyInteger('option')->comment('1 涨 2 区间 3跌');
            $table->tinyInteger('status')->default(1)->comment('1 待出结果 2已完成 ');
            $table->string('input_time', 15)->comment('结束日期');
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
        Schema::table('woke_market', function (Blueprint $table) {
            //
        });
    }
}
