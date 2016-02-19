<?php namespace App;

class VersesDarbyBibleTranslationEn extends BaseModel {

    /**
     * Generated
     */

    protected $table = 'verses_darby_bible_translation_en';
    protected $fillable = ['id', 'book_id', 'chapter_num', 'verse_num', 'verse_text'];


    public function booksListEn() {
        return $this->belongsTo(BooksListEn::class, 'book_id', 'id');
    }


}
