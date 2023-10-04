<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableHomeContents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('home_contents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug')->nullable();
            $table->string('section')->nullable();
            $table->longText('description')->nullable();
            $table->string('sub_heading1')->nullable();
            $table->longText('sub_decription1')->nullable();
            $table->string('sub_heading2')->nullable();
            $table->longText('sub_decription2')->nullable();
            $table->tinyInteger('is_active')->default('1');
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
        Schema::dropIfExists('table_home_contents');
    }
}
