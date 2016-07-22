<?php namespace App;

class BlogArticle extends BaseModel {

	protected $fillable = ['category_id', 'text', 'title', 'user_id'];
	protected $dates = ['created_at', 'updated_at', 'published_at'];

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
