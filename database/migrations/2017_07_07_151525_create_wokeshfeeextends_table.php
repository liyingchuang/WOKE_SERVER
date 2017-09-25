<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWokeshfeeextendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('woke_shipp_fee_extends', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shipp_fee_id');
            $table->integer('supplier_id');
            $table->string('province', 4000)->nullable();
            $table->string('city', 8000)->nullable();
            $table->integer('number')->comment('首重');
            $table->decimal('price', 10,2)->comment('首重价格');
            $table->integer('xnumber')->nullable()->comment('续重');
            $table->decimal('xprice', 10,2)->nullable()->comment('续重价格');
            $table->decimal('free')->nullable()->comment('包邮门槛');
            $table->integer('is_default')->nullable()->comment('是否默认模板');
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
        Schema::drop('woke_shipp_fee_extends');
    }
}
