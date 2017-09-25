<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGoodsattrTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('woke_goods_attr', function (Blueprint $table) {
            $table->decimal('group_price',10,2)->default(0,0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('woke_goods_attr', function (Blueprint $table) {
            //
        });
    }
}
