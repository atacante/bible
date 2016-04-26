<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    public $timestamps  = true;

    protected $table = 'notes';
    protected $fillable = ['id','user_id','verse_id','lexicon_id','highlighted_text','note_text'];

    public function rules()
    {
        return  [
            'note_text' => 'required',
        ];
    }

    public static $columns = [
        "Note Text"=>"note_text",
        "Verse"=>"verse_id",
        "Created"=>"created_at"
    ];

    public function verse() {
        return $this->belongsTo(\App\VersesKingJamesEn::class, 'verse_id', 'id');
    }
}
