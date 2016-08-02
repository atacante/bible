<?php

namespace App;

class ShopProduct extends BaseModel {

	protected $fillable = ['category_id', 'name', 'photo', 'short_description', 'long_description', 'price', 'external_link'];
	protected $dates = ['created_at', 'updated_at'];

	public function rules()
	{
		$rules = [
			'category_id' => 'required',
			'name' => 'required|max:255',
			'price' => 'required|numeric',
		];

		return $rules;
	}

	public function category() {
		return $this->belongsTo(ShopCategory::class, 'category_id', 'id');
	}

    public function images() {
        return $this->hasMany(ProductImages::class, 'product_id', 'id');
    }
}
