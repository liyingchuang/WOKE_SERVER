<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupinfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('woke_group_info', function (Blueprint $table) {
            $table->increments('info_id');
            $table->integer('group_id');
            $table->integer('user_id');
            $table->integer('goods_id');
            $table->string('order_sn', 20);
            $table->integer('pay_status');
            $table->integer('supplier_id');
            $table->string('consignee', 60);
            $table->string('address', 255);
            $table->string('tel', 60);
            $table->integer('buy_number');
            $table->string('pay_name', 120);
            $table->integer('pay_time');
            $table->decimal('order_amount', 10, 2);
            $table->decimal('integral_amount', 10, 2);
            $table->string('remarks', 255);
            $table->string('vat_inv_company_name', 60)->nullable();
            $table->string('vat_inv_taxpayer_id', 20)->nullable();
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
        Schema::drop('open_group_info');
    }
}
