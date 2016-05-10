<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Journal extends BaseModel
{
    public $timestamps  = true;

    protected $table = 'journal';
    protected $fillable = ['id','user_id','bible_version','verse_id','lexicon_id','highlighted_text','journal_text'];

    public function rules()
    {
        return  [
            'journal_text' => 'required',
        ];
    }

    public static $columns = [
        "Journal Text"=>"journal_text",
        "Verse"=>"verse_id",
        "Created"=>"created_at"
    ];

    public function verse() {
        return $this->belongsTo(\App\VersesKingJamesEn::class, 'verse_id', 'id');
    }
}
