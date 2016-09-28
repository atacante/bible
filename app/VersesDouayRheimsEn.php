<?php namespace App;

class VersesDouayRheimsEn extends BaseModel {

    /**
     * Generated
     */

    public $timestamps  = false;

    protected $table = 'verses_douay_rheims_en';
    protected $fillable = ['id', 'book_id', 'chapter_num', 'verse_num', 'verse_text'];


    public function booksListEn() {
        return $this->belongsTo(BooksListEn::class, 'book_id', 'id');
    }

    public function locations() {
        return $this->belongsToMany(Location::class, 'location_verse', 'verse_id', 'location_id');
    }

    public function peoples() {
        return $this->belongsToMany(People::class, 'people_verse', 'verse_id', 'people_id');
    }

    public function views()
    {
        return $this->morphToMany('App\User','item','users_views')->withTimestamps();
    }
}
