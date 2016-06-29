<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Note extends BaseModel
{
    const ACCESS_PRIVATE = 'private';
    const ACCESS_PUBLIC_ALL = 'public_for_all';
    const ACCESS_PUBLIC_GROUPS = 'public_for_groups';

    public $timestamps  = true;
    public $journal_text;
    public $prayer_text;

    protected $table = 'notes';
    protected $dates = ['created_at','updated_at','published_at'];
    protected $fillable = ['id','user_id','journal_id','prayer_id','verse_id','lexicon_id','highlighted_text','note_text','bible_version','access_level','rel_code'];

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
        "Tags"=>false,
        "Accessibility"=>false,
        "Created"=>"created_at"
    ];

    public function user() {
        return $this->belongsTo(\App\User::class, 'user_id', 'id');
    }

    public function verse() {
        return $this->belongsTo(\App\VersesKingJamesEn::class, 'verse_id', 'id');
    }

    public function journal() {
        return $this->belongsTo(\App\Journal::class, 'journal_id', 'id');
    }

    public function journals() {
        return $this->belongsToMany(Journal::class, 'notes_journal_prayers', 'note_id', 'journal_id');
    }

    public function prayer() {
        return $this->belongsTo(\App\Prayer::class, 'prayer_id', 'id');
    }

    public function prayers() {
        return $this->belongsToMany(Prayer::class, 'notes_journal_prayers', 'note_id', 'prayer_id');
    }

    public function tags() {
        return $this->belongsToMany(Tag::class, 'notes_tags', 'note_id', 'tag_id');
    }

    public function availableTags()
    {
        return Tag::where('type', Tag::TYPE_SYSTEM)->orWhere('user_id', Auth::user()->id)->lists('tag_name','id')->toArray();
    }

    public function syncTags($tags){
        $tagsToSync = [];
        if(count($tags)){
            foreach ($tags as $tag) {
                if(is_numeric($tag)){
                    $tagsToSync[] = $tag;
                }
                else{
                    $tagModel = new Tag();
                    $tagModel->user_id = Auth::user()->id;
                    $tagModel->type = $tagModel::TYPE_USER;
                    $tagModel->tag_name = $tag;
                    $tagModel->save();
                    $tagsToSync[] = $tagModel->id;
                }
            }
        }
        $this->tags()->sync($tagsToSync);
    }
}
