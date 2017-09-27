<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExtendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('woke_group_goods_extends', function (Blueprint $table) {
            $table->integer('goods_id');
            $table->integer('ex_number');
            $table->integer('ex_have')->nullable()->default(0);
            $table->decimal('group_price', 10, 2);
            $table->integer('start_time')->nullable();
            $table->integer('end_time')->nullable();
            $table->integer('supplier_id')->nullable();
            $table->integer('examine_status')->nullable()->default(1);
            $table->text('describe')->nullable();
            $table->integer('recommend')->nullable()->default(0);
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
        Schema::drop('goods_extends');
    }
}
