<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LocationVerse extends Model
{
    protected $table = 'location_verse';
    protected $fillable = ['id', 'verse_id','location_id','book_id','chapter_num','verse_num'];
}
