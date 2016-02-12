<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBooksListEngTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('books_list_eng', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('book_name')->unique('books_list_eng_book_name_key');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('books_list_eng');
	}

}
