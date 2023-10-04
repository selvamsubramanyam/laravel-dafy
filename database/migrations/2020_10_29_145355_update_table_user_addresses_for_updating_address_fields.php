<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTableUserAddressesForUpdatingAddressFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_addresses', function (Blueprint $table) {
            $table->string('name')->after('address_type')->nullable();
            $table->tinyInteger('is_save')->after('address_for')->default('0');
            $table->tinyInteger('type')->after('is_save')->default('0')->comment('0 => Home, 1 => Office, 2 => Other');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
