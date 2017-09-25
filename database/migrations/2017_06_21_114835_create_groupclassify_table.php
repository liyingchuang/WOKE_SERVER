<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupclassifyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('woke_group_classify', function (Blueprint $table) {
            $table->increments('ify_id');
            $table->string('ify_name', 40)->comment('分类名')->nullable();
            $table->integer('parent_id')->comment('上级分类')->nullable();
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
        Schema::drop('woke_group_classify');
    }
}
