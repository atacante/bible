<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends BaseModel
{
    public $timestamps  = true;
    public $journal_text;
    public $prayer_text;

    protected $table = 'notes';
    protected $fillable = ['id','user_id','journal_id','prayer_id','verse_id','lexicon_id','highlighted_text','note_text','bible_version'];

    public function rules()
    {
        return  [
            'note_text' => 'required',
        ];
    }

    public static $columns = [
        "Note Text"=>"note_text",
        "Verse"=>"verse_id",
        "Relations"=>false,
        "Created"=>"created_at"
    ];

    public function verse() {
        return $this->belongsTo(\App\VersesKingJamesEn::class, 'verse_id', 'id');
    }

    public function journal() {
        return $this->belongsTo(\App\Journal::class, 'journal_id', 'id');
    }

    public function prayer() {
        return $this->belongsTo(\App\Prayer::class, 'prayer_id', 'id');
    }
}
