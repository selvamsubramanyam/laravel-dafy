<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableForEachSellerOrderData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seller_order_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('seller_id')->nullable();
            $table->tinyInteger('type')->default('0')->comments('0 => order, 1 => buy, 2 => deliver');
            $table->integer('order_id')->nullable();
            $table->float('amount')->nullable();
            $table->float('commission')->nullable();
            $table->tinyInteger('status')->default('0')->comments('0 => pending, 1 => paid');
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
        Schema::dropIfExists('seller_order_data');
    }
}
