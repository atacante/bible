<?php namespace App;

class OrderItem extends BaseModel {

	protected $fillable = ['order_id', 'product_id', 'qty'];
	protected $dates = ['created_at', 'updated_at'];

	public function rules()
	{
		$rules = [
			'order_id' => 'required',
			'product_id' => 'required'
		];

		return $rules;
	}

	public function product() {
		return $this->belongsTo(ShopProduct::class, 'product_id', 'id');
	}

}
