<?php namespace App;

class VersesAmericanKingJamesEn extends BaseModel {

    /**
     * Generated
     */
    public $timestamps  = false;

    protected $table = 'verses_american_king_james_en';
    protected $fillable = ['id', 'book_id', 'chapter_num', 'verse_num', 'verse_text'];

    public function booksListEn() {
        return $this->belongsTo(BooksListEn::class, 'book_id', 'id');
    }

    public function locations() {
        return $this->belongsToMany(Location::class, 'location_verse', 'verse_id', 'location_id');
    }
}
