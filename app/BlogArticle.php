<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class BlogArticle extends Model {

	protected $fillable = [];

	public function user() {
		return $this->belongsTo(User::class, 'user_id', 'id');
	}

	public function category() {
		return $this->belongsTo(BlogCategory::class, 'category_id', 'id');
	}

	public function comments(){
		return $this->hasMany(BlogComment::class, 'article_id', 'id');
	}

}
