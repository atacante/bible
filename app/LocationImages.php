<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LocationImages extends BaseModel
{
    public $timestamps  = true;

    protected $table = 'location_images';
    protected $fillable = ['id','location_id','image'];

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id', 'id');
    }
}
