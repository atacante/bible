<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBereanVersionTable extends Migration
{
    private $version = 'berean';
    private $tableName;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->tableName = 'verses_' . $this->version . '_en';
        if (!Schema::hasTable($this->tableName))
        {
            Schema::create($this->tableName, function (Blueprint $table) {
                $table->increments('id');
                $table->integer('book_id');
                $table->integer('chapter_num');
                $table->integer('verse_num');
                $table->text('verse_text')->nullable();
            });
            Schema::table($this->tableName, function (Blueprint $table) {
                $table->foreign('book_id', 'verses_' . $this->version . '_en_fk')->references('id')->on('books_list_en')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            });
            DB::statement('ALTER TABLE ' .$this->tableName. ' ADD COLUMN searchtext TSVECTOR;');
            DB::statement('UPDATE ' .$this->tableName. ' SET searchtext = to_tsvector(\'english\', verse_text);');
            DB::statement('CREATE INDEX searchtext_'.$this->tableName.'_gin ON ' .$this->tableName. ' USING GIN(searchtext);');
            DB::statement('CREATE TRIGGER ts_searchtext BEFORE INSERT OR UPDATE ON ' .$this->tableName. ' FOR EACH ROW EXECUTE PROCEDURE tsvector_update_trigger(\'searchtext\', \'pg_catalog.english\', \'verse_text\')');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->tableName = 'verses_' . $this->version . '_en';
        Schema::drop($this->tableName);
    }
}
