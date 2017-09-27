<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('woke_order_total', function (Blueprint $table) {
            $table->increments('order_total_id');
            $table->string('order_id',40)->comment('子订单id');
            $table->integer('user_id')->comment('用户id');
            $table->string('order_total_sn', 20)->comment('总订单号');
            $table->decimal('order_total_price',10,2)->comment('订单总金额');
            $table->decimal('bonus',10,2)->nullable()->comment('计算参与分成');
            $table->decimal('integral_money',10,2)->comment('使用酒币');
            $table->decimal('integral',10,2)->comment('返还酒币');
            $table->integer('pay_status')->default(0)->comment('支付状态');
            $table->integer('pay_time')->comment('支付时间');
            $table->integer('add_time')->comment('订单生成时间');
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
        Schema::drop('woke_order_total');
    }
}
