<?php namespace App;

class Order extends BaseModel
{

    protected $fillable = ['user_id', 'user_meta_id', 'transaction_id', 'total_paid', 'tax', 'subtotal'];
    protected $dates = ['created_at', 'updated_at'];

    public function rules()
    {
        $rules = [
            'user_id' => 'required',
            'user_meta_id' => 'required'
        ];

        return $rules;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function userMeta()
    {
        return $this->belongsTo(UsersMeta::class, 'user_meta_id', 'id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }
}
