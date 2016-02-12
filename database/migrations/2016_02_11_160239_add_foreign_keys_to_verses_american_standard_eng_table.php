<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToVersesAmericanStandardEngTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('verses_american_standard_eng', function(Blueprint $table)
		{
			$table->foreign('book_id', 'verses_american_standard_eng_fk')->references('id')->on('books_list_eng')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('verses_american_standard_eng', function(Blueprint $table)
		{
			$table->dropForeign('verses_american_standard_eng_fk');
		});
	}

}
