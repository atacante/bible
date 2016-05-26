<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NoteTag extends Model
{
    public $timestamps  = false;
    protected $table = 'notes_tags';
    protected $fillable = ['id', 'note_id','tag_id'];
}
