<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCommissionToProductUntrackedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_untracked', function (Blueprint $table) {
            $table->float('commission', 8, 2)->unsigned()->nullable()->after('additional_attributes')->comment('commission in %'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_untracked', function (Blueprint $table) {
            $table->dropColumn('commission');
        });
    }
}
