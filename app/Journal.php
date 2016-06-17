<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Journal extends BaseModel
{
    const ACCESS_PRIVATE = 'private';
    const ACCESS_PUBLIC_ALL = 'public_for_all';
    const ACCESS_PUBLIC_GROUPS = 'public_for_groups';

    public $timestamps = true;
    public $note_text;
    public $prayer_text;

    protected $table = 'journal';
    protected $dates = ['published_at'];
    protected $fillable = ['id','user_id','note_id','prayer_id','bible_version','verse_id','lexicon_id','highlighted_text','journal_text','access_level','rel_code'];

    public function rules()
    {
        return  [
            'journal_text' => 'required',
        ];
    }

    public static $columns = [
        "Journal Text"=>"journal_text",
        "Verse"=>"verse_id",
        "Relations"=>false,
        "Tags"=>false,
        "Accessibility"=>false,
        "Created"=>"created_at"
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function verse() {
        return $this->belongsTo(\App\VersesKingJamesEn::class, 'verse_id', 'id');
    }

    public function note() {
        return $this->belongsTo(\App\Note::class, 'id', 'journal_id');
    }

    public function notes() {
        return $this->belongsToMany(Note::class, 'notes_journal_prayers', 'journal_id', 'note_id');
    }

    public function prayer() {
        return $this->belongsTo(\App\Prayer::class, 'prayer_id', 'id');
    }

    public function prayers() {
        return $this->belongsToMany(Prayer::class, 'notes_journal_prayers', 'journal_id', 'prayer_id');
    }

    public function tags() {
        return $this->belongsToMany(Tag::class, 'journal_tags', 'journal_id', 'tag_id');
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
