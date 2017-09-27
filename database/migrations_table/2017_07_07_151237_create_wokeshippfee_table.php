<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWokeshippfeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('woke_shipp_fee', function (Blueprint $table) {
            $table->increments('shipp_fee_id');
            $table->string('shipp_fee_name', 40)->nullable()->comment('模板名称');
            $table->string('shipp_name', 40)->nullable()->comment('快递名称');
            $table->integer('supplier_id');
            $table->integer('is_default')->default(0);
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
        Schema::drop('woke_shipp_fee');
    }
}
