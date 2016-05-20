<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Journal extends BaseModel
{
    public $timestamps = true;
    public $note_text;
    public $prayer_text;

    protected $table = 'journal';
    protected $fillable = ['id','user_id','note_id','prayer_id','bible_version','verse_id','lexicon_id','highlighted_text','journal_text'];

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

    public function note() {
        return $this->belongsTo(\App\Note::class, 'id', 'journal_id');
    }

    public function prayer() {
        return $this->belongsTo(\App\Prayer::class, 'prayer_id', 'id');
    }
}
