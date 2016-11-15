<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterShopProductsAddNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shop_products', function ($table) {
            $table->string('photo')->nullable()->change();
            $table->string('external_link')->nullable()->change();
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
            $table->string('photo')->nullable(false)->change();
            $table->string('external_link')->nullable(false)->change();
        });
    }
}
