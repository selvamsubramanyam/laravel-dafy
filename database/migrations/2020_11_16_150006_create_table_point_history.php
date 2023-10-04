<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePointHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('point_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->nullable();
            $table->integer('from_id')->nullable();
            $table->tinyInteger('is_credit')->default('0');
            $table->double('points')->nullable();
            $table->string('slug')->nullable();
            $table->integer('order_id')->nullable();
            $table->longText('description')->nullable();
            $table->tinyInteger('is_valid')->default('1');
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
        Schema::dropIfExists('table_point_history');
    }
}
