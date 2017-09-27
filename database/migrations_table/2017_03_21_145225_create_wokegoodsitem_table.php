<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWokegoodsitemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('woke_goods_item', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('goods_id')->unsigned()->index();
            $table->integer('stor')->unsigned();
            $table->string('key',50)->comment('属性名');
            $table->string('value',50)->comment('属性值');
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
        Schema::drop('woke_goods_item');
    }
}
