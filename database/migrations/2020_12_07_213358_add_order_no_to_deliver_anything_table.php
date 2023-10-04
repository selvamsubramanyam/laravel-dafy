<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderNoToDeliverAnythingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('deliver_anything', function (Blueprint $table) {
            $table->string('order_no')->after('user_id')->nullable();
            $table->integer('order_status')->unsigned()->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('deliver_anything', function (Blueprint $table) {
            $table->dropColumn('order_no');
            $table->dropColumn('order_status');
        });
    }
}
