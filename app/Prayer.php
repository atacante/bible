<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prayer extends BaseModel
{
    public $timestamps = true;
    public $note_text;
    public $journal_text;

    protected $table = 'prayers';
    protected $fillable = ['id','user_id','note_id','journal_id','bible_version','verse_id','lexicon_id','highlighted_text','prayer_text'];

    public function rules()
    {
        return  [
            'prayer_text' => 'required',
        ];
    }

    public static $columns = [
        "Prayer Text"=>"prayer_text",
        "Verse"=>"verse_id",
        "Relations"=>false,
        "Created"=>"created_at"
    ];

    public function verse() {
        return $this->belongsTo(\App\VersesKingJamesEn::class, 'verse_id', 'id');
    }

    public function note() {
        return $this->belongsTo(\App\Note::class, 'id', 'prayer_id');
    }

    public function journal() {
        return $this->belongsTo(\App\Journal::class, 'journal_id', 'id');
    }
}
