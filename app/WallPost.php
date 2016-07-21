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
    protected $fillable = ['id','type','wall_type','user_id','verse_id','rel_id','text','access_level'];

    public function rules()
    {
        return  [
            'text' => 'required',
        ];
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function comments()
    {
        return $this->morphMany('App\WallComment','item','type')->orderBy('created_at','desc');
    }

    public function likes()
    {
        return $this->morphToMany('App\User','item','wall_likes')->orderBy('wall_likes.created_at','desc');
    }
}
