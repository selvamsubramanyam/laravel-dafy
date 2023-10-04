<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableOrderAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_address', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('order_id')->nullable();
            $table->string('name')->nullable();
            $table->string('build_name')->nullable();
            $table->string('area')->nullable();
            $table->string('location')->nullable();
            $table->string('landmark')->nullable();
            $table->string('mobile')->nullable();
            $table->string('pincode')->nullable();
            $table->tinyInteger('type')->default('0')->comment('0 => Home, 1 => Office, 2 => Other');
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
        Schema::dropIfExists('table_order_address');
    }
}
