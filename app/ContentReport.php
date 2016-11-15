<?php

namespace App;

class ContentReport extends BaseModel
{
    protected $fillable = ['user_id', 'reason_text'];

    public function rules()
    {
        return  [
            'reason_text' => 'required',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function item()
    {
        return $this->morphTo();
    }

    public function type()
    {
        $type = '';
        switch ($this->item_type) {
            case 'App\Note':
                $type = 'note';
                break;
            case 'App\Journal':
                $type = 'journal';
                break;
            case 'App\Prayer':
                $type = 'prayer';
                break;
            case 'App\WallPost':
                $type = 'status';
                break;
        }
        return $type;
    }
}
