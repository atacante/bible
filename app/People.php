<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class People extends BaseModel
{
    public $timestamps  = true;

    protected $table = 'peoples';
    protected $fillable = ['id','people_name','people_description'];

    public function rules()
    {
        return  [
            'people_name' => 'required',
            'people_description' => 'required',
        ];
    }

    public function images() {
        return $this->hasMany(PeopleImages::class, 'people_id', 'id');
    }
}
