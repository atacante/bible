<?php namespace App;

class Order extends BaseModel {

	protected $fillable = ['user_id', 'user_meta_id', 'transaction_id', 'total_paid'];
	protected $dates = ['created_at', 'updated_at'];

	public function rules()
	{
		$rules = [
			'user_id' => 'required',
			'user' => 'required'
		];

		return $rules;
	}

	public function user() {
		return $this->belongsTo(User::class, 'user_id', 'id');
	}

	public function userMeta() {
		return $this->hasOne(UsersMeta::class, 'user_meta_id', 'id');
	}

}
