<?php

namespace App;

class WallLike extends BaseModel
{
    protected $fillable = array('user_id');

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
