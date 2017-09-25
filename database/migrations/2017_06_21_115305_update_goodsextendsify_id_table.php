<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateGoodsextendsifyIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('woke_group_goods_extends', function (Blueprint $table) {
            $table->integer('ify_id')->comment('分类ID')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('woke_group_goods_extends', function (Blueprint $table) {
            //
        });
    }
}
