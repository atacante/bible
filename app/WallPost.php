<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WallPost extends BaseModel
{
    const ACCESS_PRIVATE = 'private';
    const ACCESS_PUBLIC_ALL = 'public_all';
    const ACCESS_PUBLIC_FRIENDS = 'public_friends';

    const TYPE_STATUS = 'status';

    const WALL_TYPE_PUBLIC = 'public';
    const WALL_TYPE_GROUP = 'group';
    const WALL_TYPE_FRIENDS = 'friends';

    public $timestamps = true;
    public $note_text;
    public $journal_text;

    protected $table = 'wall_posts';
    protected $dates = ['created_at','updated_at','published_at'];
    protected $fillable = ['id','type','wall_type','user_id','verse_id','rel_id','status_text','access_level'];

    public function rules()
    {
        return  [
            'status_text' => 'required',
        ];
    }

    protected static function boot() {
        parent::boot();
        static::deleting(function($model) {
            return true;
        });
    }

    public function text() {
        return $this->status_text;
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
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
}
