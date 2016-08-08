<?php namespace App;

class UsersMeta extends BaseModel {

	protected $fillable = ['user_id', 'billing_first_name','billing_last_name', 'billing_address', 'billing_city', 'billing_postcode', 'billing_country', 'billing_state', 'billing_email', 'billing_phone', 'shipping_first_name','shipping_last_name', 'shipping_address', 'shipping_city', 'shipping_postcode', 'shipping_country', 'shipping_state', 'shipping_email', 'shipping_phone'];
	protected $dates = ['created_at', 'updated_at'];

	public function rules()
	{
		$rules = [
			'user_id' => 'required',
		];

		return $rules;
	}

	public function user() {
		return $this->belongsTo(User::class, 'user_id', 'id');
	}

}
