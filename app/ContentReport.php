<?php

namespace App;

class ContentReport extends BaseModel
{
    protected $fillable = array('user_id', 'reason_text');

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function rules()
    {
        return  [
            'reason_text' => 'required',
        ];
    }
}
