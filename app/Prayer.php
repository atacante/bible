<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Prayer extends BaseModel
{
    const ACCESS_PRIVATE = 'private';
    const ACCESS_PUBLIC_ALL = 'public_for_all';
    const ACCESS_PUBLIC_GROUPS = 'public_for_groups';

    public $timestamps = true;
    public $note_text;
    public $journal_text;

    protected $table = 'prayers';
    protected $fillable = ['id','user_id','note_id','journal_id','bible_version','verse_id','lexicon_id','highlighted_text','prayer_text','access_level','rel_code'];

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
        "Tags"=>false,
        "Accessibility"=>false,
        "Created"=>"created_at"
    ];

    public function verse() {
        return $this->belongsTo(\App\VersesKingJamesEn::class, 'verse_id', 'id');
    }

    public function note() {
        return $this->belongsTo(\App\Note::class, 'id', 'prayer_id');
    }

    public function notes() {
        return $this->belongsToMany(Note::class, 'notes_journal_prayers', 'prayer_id', 'note_id');
    }

    public function journal() {
        return $this->belongsTo(\App\Journal::class, 'journal_id', 'id');
    }

    public function journals() {
        return $this->belongsToMany(Journal::class, 'notes_journal_prayers', 'prayer_id', 'journal_id');
    }

    public function tags() {
        return $this->belongsToMany(Tag::class, 'prayers_tags', 'prayer_id', 'tag_id');
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
