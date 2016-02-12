<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVersesAmericanStandardEngTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('verses_american_standard_eng', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('book_id');
			$table->integer('chapter_num');
			$table->integer('verse_num');
			$table->text('verse_text')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('verses_american_standard_eng');
	}

}
