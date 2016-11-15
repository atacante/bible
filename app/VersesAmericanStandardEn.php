<?php namespace App;

class VersesAmericanStandardEn extends BaseModel
{

    /**
     * Generated
     */

    public $timestamps  = false;

    protected $table = 'verses_american_standard_en';
    protected $fillable = ['id', 'book_id', 'chapter_num', 'verse_num', 'verse_text'];


    public function booksListEn()
    {
        return $this->belongsTo(\App\BooksListEn::class, 'book_id', 'id');
    }

    public function locations()
    {
        return $this->belongsToMany(Location::class, 'location_verse', 'verse_id', 'location_id');
    }

    public function peoples()
    {
        return $this->belongsToMany(People::class, 'people_verse', 'verse_id', 'people_id');
    }

    public function views()
    {
        return $this->morphToMany('App\User', 'item', 'users_views')->withTimestamps();
    }

    public function bookmarks()
    {
        return $this->morphToMany('App\User', 'item', 'bookmarks')->orderBy('bookmarks.created_at', 'desc');
    }
}
