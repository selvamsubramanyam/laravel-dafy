<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class CreateDriverUserDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('driver_user_devices', function (Blueprint $table) {
            $table->Increments('id');
            $table->integer('deliveryboy_id')->unsigned()->nullable();
            $table->integer('device_type')->nullable()->comment('1=>Android , 2=> IOS');
            $table->string('device_id')->nullable();
            $table->timestamp('login_time')->useCurrent();
            $table->timestamp('logout_time')->nullable();
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
        Schema::dropIfExists('driver_user_devices');
    }
}
