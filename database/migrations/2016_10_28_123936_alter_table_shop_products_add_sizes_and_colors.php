<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableShopProductsAddSizesAndColors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shop_products', function ($table) {
            $table->string('colors')->nullable();
            $table->string('sizes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shop_products', function ($table) {
            $table->dropColumn('colors');
            $table->dropColumn('sizes');
        });
    }
}
