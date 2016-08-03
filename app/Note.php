<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;

class Note extends BaseModel
{
    const ACCESS_PRIVATE = 'private';
    const ACCESS_PUBLIC_ALL = 'public_for_all';
    const ACCESS_PUBLIC_GROUPS = 'public_for_groups';
    const ACCESS_SPECIFIC_GROUPS = 'public_for_specific_groups';

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

    public function setAccessLevelAttribute($value)
    {
        $this->attributes['access_level'] = Request::get('share_for_groups',$value);
    }

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

    public function groupsShares()
    {
        return $this->morphToMany('App\Group', 'groups_shares');
    }

    public function comments()
    {
        return $this->morphMany('App\WallComment','item','type')->orderBy('created_at','desc');
    }

    public function contentReports()
    {
        return $this->morphMany('App\ContentReport','item','item_type')->orderBy('created_at','desc');
    }

    public function images()
    {
        return $this->morphMany('App\WallImage','item','item_type');
    }

    public function likes()
    {
        return $this->morphToMany('App\User','item','wall_likes')->orderBy('wall_likes.created_at','desc');
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

    public function syncGroups()
    {
        $groups = [];
        if($this->access_level == self::ACCESS_PUBLIC_GROUPS){
            $groups = Auth::user()->myGroups->modelKeys()+Auth::user()->joinedGroups->modelKeys();
        }
        elseif($this->access_level == self::ACCESS_SPECIFIC_GROUPS){
            $groups = Input::get('groups',[]);
        }
        $this->groupsShares()->sync($groups);
    }
}
