<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTableBuyAnythingWithShopDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buy_anything', function (Blueprint $table) {
            $table->string('shop_name')->after('mobile')->nullable();
            $table->string('shop_location')->after('shop_name')->nullable();
            $table->string('shop_latitude')->after('shop_location')->nullable();
            $table->string('shop_longitude')->after('shop_latitude')->nullable();
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
