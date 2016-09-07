<?php

namespace App;

use Gloudemans\Shoppingcart\Contracts\Buyable;

class ShopProduct extends BaseModel implements Buyable{

	protected $fillable = ['category_id', 'name', 'photo', 'short_description', 'long_description', 'price', 'external_link'];
	protected $dates = ['created_at', 'updated_at'];

	public function rules()
	{
		$rules = [
			'category_id' => 'required',
			'name' => 'required|max:255',
			'short_description' => 'max:255',
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

    /**
     * Get the identifier of the Buyable item.
     *
     * @return int|string
     */
    public function getBuyableIdentifier(){
        return $this->id;
    }

    /**
     * Get the description or title of the Buyable item.
     *
     * @return string
     */
    public function getBuyableDescription(){
        return $this->name;
    }

    /**
     * Get the price of the Buyable item.
     *
     * @return float
     */
    public function getBuyablePrice(){
        return $this->price;
    }
}
