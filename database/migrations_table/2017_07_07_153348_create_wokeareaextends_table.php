<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWokeareaextendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('woke_area_extends', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('area_id')->nullable();
            $table->string('name', 40);
            $table->integer('parent_id');
            $table->integer('sort');
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
        Schema::drop('woke_area_extends');
    }
}
