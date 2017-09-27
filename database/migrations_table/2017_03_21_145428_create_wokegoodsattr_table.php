<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWokegoodsattrTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('woke_goods_attr', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('goods_id')->unsigned()->index();
            $table->string('attr_name',50)->comment('属性名');
            $table->string('attr_value',50)->comment('属性值');
            $table->decimal('market_price',10,2)->comment('原价价格')->default(0.0);
            $table->decimal('shop_price',10,2)->comment('本店价格')->default(0.0);
            $table->integer('goods_number')->unsigned();
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
        Schema::drop('woke_goods_attr');
    }
}
