<?php

namespace App;

class ProductImages extends BaseModel
{
    public $timestamps  = true;

    protected $table = 'product_images';
    protected $fillable = ['id','product_id','image'];

    public function product()
    {
        return $this->belongsTo(ShopProduct::class, 'product_id', 'id');
    }
}
