<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('woke_group_open', function (Blueprint $table) {
            $table->increments('group_id');
            $table->integer('user_id');
            $table->integer('goods_id');
            $table->integer('supplier_id');
            $table->integer('have');
            $table->integer('start_time');
            $table->integer('group_status');
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
        Schema::drop('open_group');
    }
}
