<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;

class Journal extends BaseModel
{
    const ACCESS_PRIVATE = 'private';
    const ACCESS_PUBLIC_ALL = 'public_for_all';
    const ACCESS_PUBLIC_GROUPS = 'public_for_groups';
    const ACCESS_SPECIFIC_GROUPS = 'public_for_specific_groups';

    public $timestamps = true;
    public $note_text;
    public $prayer_text;

    protected $table = 'journal';
    protected $dates = ['created_at','updated_at','published_at'];
    protected $fillable = ['id','user_id','note_id','prayer_id','bible_version','verse_id','lexicon_id','highlighted_text','journal_text','access_level','rel_code','only_show_group_owner'];

    public function rules()
    {
        $rules['journal_text'] = 'required';
        if(Request::input('share_for_groups') == self::ACCESS_SPECIFIC_GROUPS){
            $rules['groups'] = 'required';
        }
        return $rules;
    }

    public function messages()
    {
        $messages['groups.required'] = 'Please select groups from list.';
        return $messages;
    }

    public static $columns = [
        "Journal Text"=>"journal_text",
        "Verse"=>"verse_id",
        "Relations"=>false,
        "Tags"=>false,
        "Accessibility"=>false,
        "Created"=>"created_at"
    ];

    protected static function boot() {
        parent::boot();
        static::deleting(function($model) {
            return true;
        });
    }

    public function setAccessLevelAttribute($value)
    {
        $this->attributes['access_level'] = Request::get('share_for_groups',$value);
    }

    public function text() {
        return $this->journal_text;
    }

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
