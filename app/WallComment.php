<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WallComment extends BaseModel
{
    protected $fillable = array('user_id', 'text');

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function rules()
    {
        return  [
            'text' => 'required',
        ];
    }
}
