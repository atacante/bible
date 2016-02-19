<?php namespace App;

class VersesAmericanStandardEn extends BaseModel {

    /**
     * Generated
     */

    public $timestamps  = false;

    protected $table = 'verses_american_standard_en';
    protected $fillable = ['id', 'book_id', 'chapter_num', 'verse_num', 'verse_text'];


    public function booksListEn() {
        return $this->belongsTo(\App\BooksListEn::class, 'book_id', 'id');
    }


}
