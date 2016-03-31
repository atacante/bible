<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PeopleImages extends BaseModel
{
    public $timestamps  = true;

    protected $table = 'people_images';
    protected $fillable = ['id','people_id','image'];

    public function people() {
        return $this->belongsTo(People::class, 'people_id', 'id');
    }
}
