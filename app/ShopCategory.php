<?php

namespace App;

class ShopCategory extends BaseModel
{

    protected $fillable = ['title'];

    public function rules()
    {
        $rules = [
            'title' => 'required|max:255',
        ];

        return $rules;
    }

    public function products()
    {
        return $this->hasMany(ShopProducts::class, 'category_id', 'id');
    }
}
