<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model {

	protected $fillable = [];

	public function user() {
		return $this->belongsTo(User::class, 'user_id', 'id');
	}

	public function category() {
		return $this->belongsTo(Category::class, 'category_id', 'id');
	}

	public function comments(){
		return $this->hasMany(Comment::class, 'article_id', 'id');
	}

}
