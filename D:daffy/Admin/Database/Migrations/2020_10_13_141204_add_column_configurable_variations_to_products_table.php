<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnConfigurableVariationsToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {

            $table->text('configurable_variations')->nullable()->after('thump_image')->comment('json');
            $table->integer('parent_id')->unsigned()->nullable()->after('configurable_variations');
            $table->integer('cat_varient_id')->unsigned()->after('parent_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('configurable_variations');
            $table->dropColumn('parent_id');
            $table->dropColumn('cat_varient_id');
        });
    }
}
