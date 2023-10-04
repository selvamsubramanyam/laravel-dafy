<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('order_no')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('coupon_id')->nullable();
            $table->float('coupon_price')->nullable();
            $table->string('payment_method')->nullable();
            $table->float('discount')->nullable();
            $table->integer('discount_percent')->nullable();
            $table->float('tax_amount')->nullable();
            $table->integer('points')->nullable();
            $table->integer('status_id')->nullable();
            $table->date('delivery_date')->nullable();
            $table->string('invoice')->nullable();
            $table->float('shipping_cost')->nullable();
            $table->float('amount')->nullable();
            $table->longText('comments')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_orders');
    }
}
