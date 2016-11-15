<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WallImage extends Model
{
    public $timestamps  = true;

    protected $table = 'wall_images';
    protected $fillable = ['user_id','image'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function rules()
    {
        return  [
            'image' => 'required',
        ];
    }
}
