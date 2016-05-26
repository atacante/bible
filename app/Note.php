<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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
        "Tags"=>false,
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
