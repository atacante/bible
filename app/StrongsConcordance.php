<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StrongsConcordance extends BaseModel
{
    public $timestamps  = false;

    const DICTIONARY_HEBREW = 'hebrew';
    const DICTIONARY_GREEK = 'greek';

    protected $table = 'strongs_concordance';
    protected $fillable = ['id','strong_num','dictionary_type','strong_num_suffix','original_word','transliteration','definition_short','definition_full','exhaustive_concordance','part_of_speech','phonetic_spelling'];

    public function views()
    {
        return $this->morphToMany('App\User', 'item', 'users_views')->withTimestamps();
    }
}
