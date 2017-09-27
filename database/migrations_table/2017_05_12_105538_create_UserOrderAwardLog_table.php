<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserOrderAwardLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('woke_user_order_award_log', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->unsigned()->index();
            $table->integer('user_id')->unsigned()->comment('下单用户');
            $table->decimal('bonus',10,2)->comment('参与返现的订单总价');
            $table->integer('recommendation_user_id')->unsigned()->nullable();
            $table->decimal('recommendation_price',10,2)->comment('推荐奖')->default(0);
            $table->integer('academy_user_id')->unsigned()->nullable();
            $table->decimal('academy_price',10,2)->comment('高专奖')->default(0);
            $table->integer('manager_user_id')->unsigned()->nullable();
            $table->decimal('manager_price',10,2)->comment('经理奖')->default(0);
            $table->integer('breeding_user_id')->unsigned()->nullable();
            $table->decimal('breeding_price',10,2)->comment('育成奖')->default(0);
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
        Schema::drop('woke_user_order_award_log');
    }
}
