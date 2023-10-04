<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDriverUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('driver_users', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('country_code')->nullable();
            $table->string('mobile')->nullable();
            $table->string('alt_country_code')->nullable();
            $table->string('alt_mobile')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('image')->nullable();
            $table->string('location')->nullable();
            $table->string('area')->nullable();
            $table->string('build_name')->nullable();
            $table->string('landmark')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('city')->nullable();
            $table->longtext('address')->nullable();
            $table->string('password')->nullable();
            $table->integer('is_busy')->nullable();
            $table->integer('is_active')->nullable();
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
        Schema::dropIfExists('driver_users');
    }
}
