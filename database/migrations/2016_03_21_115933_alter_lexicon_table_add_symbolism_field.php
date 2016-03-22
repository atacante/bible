<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterLexiconTableAddSymbolismField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lexicon_kjv', function($table)
        {
            $table->text('symbolism')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lexicon_kjv', function($table)
        {
            $table->dropColumn('symbolism');
        });
    }
}
