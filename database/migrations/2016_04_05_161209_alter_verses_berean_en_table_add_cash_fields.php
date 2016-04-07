<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterVersesBereanEnTableAddCashFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(function () {
            Schema::table('verses_berean_en', function (Blueprint $table) {
                if (!Schema::hasColumn('verses_berean_en', 'verse_text_with_lexicon')) {
                    $table->text('verse_text_with_lexicon')->nullable();
                }
                if (!Schema::hasColumn('verses_berean_en', 'verse_text_with_symbolism')) {
                    $table->text('verse_text_with_symbolism')->nullable();
                }
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::transaction(function () {
            Schema::table('verses_berean_en', function (Blueprint $table) {
                $table->dropColumn('verse_text_with_lexicon');
                $table->dropColumn('verse_text_with_symbolism');
            });
        });
    }
}
