<?php namespace App;

class VersesYoungsLiteralEn extends BaseModel {

    /**
     * Generated
     */

    protected $table = 'verses_youngs_literal_en';
    protected $fillable = ['id', 'book_id', 'chapter_num', 'verse_num', 'verse_text'];


    public function booksListEn() {
        return $this->belongsTo(BooksListEn::class, 'book_id', 'id');
    }


}
