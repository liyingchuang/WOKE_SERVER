<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWokeareaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('woke_area', function (Blueprint $table) {
            $table->increments('area_id');
            $table->string('area_name',40)->comment('地区划分表');
            $table->integer('sort')->comment('分类');
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
        Schema::drop('woke_area');
    }
}
