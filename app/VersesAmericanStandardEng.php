<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class VersesAmericanStandardEng extends Model {

    /**
     * Generated
     */

    public $timestamps  = false;

    protected $table = 'verses_american_standard_eng';
    protected $fillable = ['id', 'book_id', 'chapter_num', 'verse_num', 'verse_text'];


    public function booksListEng() {
        return $this->belongsTo(\App\BooksListEng::class, 'book_id', 'id');
    }


}
