<?php
namespace App;

class LexiconKjv extends BaseModel {

    /**
     * Generated
     */

    public $timestamps  = false;

    protected $table = 'lexicon_kjv';
    protected $fillable = ['id', 'book_id','chapter_num','verse_num','verse_part','strong_num','strong_1_word_def','transliteration'];

}
