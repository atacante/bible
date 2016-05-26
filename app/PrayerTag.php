<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrayerTag extends Model
{
    public $timestamps  = false;
    protected $table = 'prayers_tags';
    protected $fillable = ['id', 'prayer_id','tag_id'];
}
