<?php

namespace App;

use App\Helpers\ModelHelper;
use Illuminate\Database\Eloquent\Model;

class LexiconBase extends Model
{
    public $timestamps  = false;

    protected $table = 'lexicon_base';
    protected $fillable = ['id', 'book_id','chapter_num','verse_num','strong_num','transliteration','verse_part_el','verse_part_he'];

}
