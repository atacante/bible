<?php
namespace App;

class LexiconKjv extends BaseModel {

    /**
     * Generated
     */

    public $timestamps  = false;

    protected $table = 'lexicon_kjv';
    protected $fillable = ['id', 'book_id','chapter_num','verse_num','verse_part','strong_num','strong_1_word_def','transliteration','symbolism'];

    public function booksListEn() {
        return $this->belongsTo(BooksListEn::class, 'book_id', 'id');
    }

    public function cacheVerse(){
        $verses[] = VersesKingJamesEn::query()
            ->where('book_id',$this->book_id)
            ->where('chapter_num',$this->chapter_num)
            ->where('verse_num',$this->verse_num)
            ->first();
        VersesKingJamesEn::cacheLexicon($verses);
    }

    public function cacheSymbolismForBeginnerMode(){
        $verses[] = VersesKingJamesEn::query()
            ->where('book_id',$this->book_id)
            ->where('chapter_num',$this->chapter_num)
            ->where('verse_num',$this->verse_num)
            ->first();
        VersesKingJamesEn::cacheSymbolismForBeginnerMode($verses);
    }

    public function locations() {
        return $this->belongsToMany(Location::class, 'location_lexicon', 'lexicon_id', 'location_id');
    }
}
