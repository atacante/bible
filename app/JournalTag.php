<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JournalTag extends BaseModel
{
    public $timestamps  = false;
    protected $table = 'journal_tags';
    protected $fillable = ['id', 'journal_id','tag_id'];
}
