<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGrouprefundTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('woke_group_refund', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->decimal('refund_price',10,2);
            $table->integer('refund_time');
            $table->string('reason', 40);
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
        Schema::drop('woke_group_refund');
    }
}
