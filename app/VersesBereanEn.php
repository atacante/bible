<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VersesBereanEn extends Model
{
    public $timestamps  = false;

    protected $table = 'verses_berean_en';
    protected $fillable = ['id', 'book_id', 'chapter_num', 'verse_num', 'verse_text'];


    public function booksListEn() {
        return $this->belongsTo(\App\BooksListEn::class, 'book_id', 'id');
    }

    public function locations() {
        return $this->belongsToMany(Location::class, 'location_verse', 'verse_id', 'location_id');
    }
}
