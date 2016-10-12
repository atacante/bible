<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VersesNasbEn extends BaseModel
{
    public $timestamps  = false;

    protected $table = 'verses_nasb_en';
    protected $fillable = ['id', 'book_id', 'chapter_num', 'verse_num', 'verse_text'];


    public function booksListEn() {
        return $this->belongsTo(\App\BooksListEn::class, 'book_id', 'id');
    }

    public function locations() {
        return $this->belongsToMany(Location::class, 'location_verse', 'verse_id', 'location_id');
    }

    public function lexicon() {
        return LexiconNasb::query()
            ->where('book_id',$this->book_id)
            ->where('chapter_num',$this->chapter_num)
            ->where('verse_num',$this->verse_num)
            ->orderBy('id')
            ->get();
    }

    public function symbolism() {
        return LexiconNasb::query()
            ->where('book_id',$this->book_id)
            ->where('chapter_num',$this->chapter_num)
            ->where('verse_num',$this->verse_num)
            ->whereNotNull('symbolism')
            ->orderBy('id')
            ->get();
    }

    public function peoples() {
        return $this->belongsToMany(People::class, 'people_verse', 'verse_id', 'people_id');
    }

    public function views()
    {
        return $this->morphToMany('App\User','item','users_views')->withTimestamps();
    }

    public function bookmarks()
    {
        return $this->morphToMany('App\User','item','bookmarks')->orderBy('bookmarks.created_at','desc');
    }
}
