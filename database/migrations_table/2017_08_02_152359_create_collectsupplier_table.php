<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollectsupplierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('woke_collect_supplier', function (Blueprint $table) {
            $table->increments('rec_id');
            $table->integer('user_id');
            $table->integer('supplier_id');
            $table->integer('add_time');
            $table->integer('is_attention');
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
        Schema::drop('woke_collect_supplier');
    }
}
