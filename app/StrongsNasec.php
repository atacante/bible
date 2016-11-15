<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StrongsNasec extends BaseModel
{
    public $timestamps  = false;

    const DICTIONARY_HEBREW = 'hebrew';
    const DICTIONARY_GREEK = 'greek';

    protected $table = 'strongs_nasec';
    protected $fillable = ['id','strong_num','strong_num_suffix','original_word','definition','nasb_translation'];

    public function views()
    {
        return $this->morphToMany('App\User', 'item', 'users_views')->withTimestamps();
    }
}
