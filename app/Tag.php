<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Tag extends BaseModel
{
    const TYPE_SYSTEM = 'system';
    const TYPE_USER = 'user';

    public $timestamps  = true;

    protected $table = 'tags';
    protected $fillable = ['id','user_id','type','tag_name'];

    public function rules()
    {
        return  [
            'tag_name' => 'required',
        ];
    }

    public function notes()
    {
        return $this->belongsToMany(Note::class, 'notes_tags', 'tag_id', 'note_id');
    }

    public function journal()
    {
        return $this->belongsToMany(Journal::class, 'journal_tags', 'tag_id', 'journal_id');
    }

    public function prayers()
    {
        return $this->belongsToMany(Prayer::class, 'prayers_tags', 'tag_id', 'prayer_id');
    }

    public static function availableTags()
    {
        return self::where('type', self::TYPE_SYSTEM)->orWhere('user_id', Auth::user()->id)->pluck('tag_name', 'id')->toArray();
    }
}
