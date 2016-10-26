<?php

namespace App;

use Gloudemans\Shoppingcart\Contracts\Buyable;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

class ShopProduct extends BaseModel implements Buyable{

	protected $fillable = ['category_id', 'name', 'photo', 'short_description', 'long_description', 'price', 'external_link', 'homepage_position'];
	protected $dates = ['created_at', 'updated_at'];

    public static $sizes = ['default' => 'Default','small'=>'Small','medium' => 'Medium','large'=>'Large'];
    public static $colors = ['default' => 'Default', 'black' => 'Black', 'green' => 'Green', 'red' => 'Red', 'blue' => 'Blue'];

	public function rules()
	{
		$rules = [
			'category_id' => 'required',
			'name' => 'required|max:255',
			'short_description' => 'max:255',
			'price' => 'required|numeric',
            'homepage_position' => 'numeric'
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
    public function getBuyableIdentifier($options = null){
        return $this->id;
    }

    /**
     * Get the description or title of the Buyable item.
     *
     * @return string
     */
    public function getBuyableDescription($options = null){
        return $this->name;
    }

    /**
     * Get the price of the Buyable item.
     *
     * @return float
     */
    public function getBuyablePrice($options = null){
        return $this->price;
    }

    public static function boot()
    {
        parent::boot();
        static ::deleted(function($model) {
            if ($model->images->count()) {
                $model->images()->delete();
                File::deleteDirectory(public_path(Config::get('app.productImages') . $model->id));
            }
            return true;
        });
    }

    /**
     * Get Free Positions on HomePage
     */
    public function getFreePositions()
    {
        $allPositions = [1=>1,2=>2,3=>3];

        $usedPositions = self::get()->pluck('homepage_position', 'homepage_position')->except($this->homepage_position)->toArray();

        $free_positions = array_diff($allPositions,$usedPositions);

        return $free_positions;
    }
}
