<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnConfigurableVariationsToProductUntrackedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_untracked', function (Blueprint $table) {
            $table->string('product_type')->after('measurement_value')->nullable();
            $table->string('configurable_variations')->after('product_type')->nullable();
            $table->string('additional_attributes')->after('configurable_variations')->nullable();
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
            $table->dropColumn('product_type');
            $table->dropColumn('configurable_variations');
            $table->dropColumn('additional_attributes');
        });
    }
}
