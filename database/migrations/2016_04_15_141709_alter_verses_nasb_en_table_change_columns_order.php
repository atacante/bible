<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterVersesNasbEnTableChangeColumnsOrder extends Migration
{
    private $version = 'nasb';
    private $temp = 'nasb_temp';
    private $tableName;
    private $tempTableName;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->tableName = 'verses_' . $this->version . '_en';
        $this->tempTableName = 'verses_' . $this->temp . '_en';
        DB::transaction(function () {
            Schema::create($this->tempTableName, function (Blueprint $table) {
                $table->increments('id');
                $table->integer('book_id');
                $table->integer('chapter_num');
                $table->integer('verse_num');
                $table->text('verse_text')->nullable();
                $table->text('verse_text_with_lexicon')->nullable();
                $table->text('verse_text_with_symbolism')->nullable();
            });
            Schema::table($this->tempTableName, function (Blueprint $table) {
                $table->foreign('book_id', 'verses_' . $this->version . '_en_fk')->references('id')->on('books_list_en')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            });
            DB::statement('ALTER TABLE ' . $this->tempTableName . ' ADD COLUMN searchtext TSVECTOR;');
            DB::statement('UPDATE ' . $this->tempTableName . ' SET searchtext = to_tsvector(\'english\', verse_text);');
            DB::statement('CREATE INDEX searchtext_' . $this->tempTableName . '_gin ON ' . $this->tempTableName . ' USING GIN(searchtext);');
            DB::statement('CREATE TRIGGER ts_searchtext BEFORE INSERT OR UPDATE ON ' . $this->tempTableName . ' FOR EACH ROW EXECUTE PROCEDURE tsvector_update_trigger(\'searchtext\', \'pg_catalog.english\', \'verse_text\')');

            DB::statement("INSERT INTO public." . $this->tempTableName . " (id,book_id,chapter_num,verse_num,verse_text,searchtext,verse_text_with_lexicon,verse_text_with_symbolism) SELECT id,book_id,chapter_num,verse_num,verse_text,searchtext,verse_text_with_lexicon,verse_text_with_symbolism FROM ONLY public." . $this->tableName . ";");

            DB::statement("TRUNCATE TABLE ".$this->tableName);
            DB::statement("ALTER SEQUENCE ".$this->tableName."_id_seq RESTART WITH 1");

            Schema::table($this->tableName, function ($table) {
                $table->dropColumn('verse_text_with_lexicon');
                $table->dropColumn('verse_text_with_symbolism');
                $table->dropColumn('searchtext');
            });

            DB::statement('ALTER TABLE ' . $this->tableName . ' ADD COLUMN searchtext TSVECTOR;');
            DB::statement('UPDATE ' . $this->tableName . ' SET searchtext = to_tsvector(\'english\', verse_text);');
            DB::statement('CREATE INDEX searchtext_' . $this->tableName . '_gin ON ' . $this->tableName . ' USING GIN(searchtext);');

            Schema::table($this->tableName, function ($table) {
                $table->text('verse_text_with_lexicon')->nullable();
                $table->text('verse_text_with_symbolism')->nullable();
            });

            DB::statement("INSERT INTO public." . $this->tableName . " (id,book_id,chapter_num,verse_num,verse_text,searchtext,verse_text_with_lexicon,verse_text_with_symbolism) SELECT id,book_id,chapter_num,verse_num,verse_text,searchtext,verse_text_with_lexicon,verse_text_with_symbolism FROM ONLY public." . $this->tempTableName . ";");

            Schema::drop($this->tempTableName);
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
