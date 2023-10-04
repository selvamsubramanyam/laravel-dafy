<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductUntrackedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_untracked', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('seller_id');
            $table->string('sku')->nullable();
            $table->string('brand')->nullable();
            $table->string('name')->nullable();
            $table->float('vendor_price', 8, 2)->nullable();  
            $table->float('price', 8, 2)->nullable();  
            $table->text('description')->nullable();
            $table->integer('stock')->nullable();
            $table->string('thump_image')->nullable();
            $table->string('unit_measurement')->nullable();
            $table->string('measurement_value')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('seller_id')->references('id')->on('business_shops')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_untracked');
    }
}
