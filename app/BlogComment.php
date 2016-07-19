<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class BlogComment extends Model {

	protected $fillable = [];

	public function user() {
		return $this->belongsTo(User::class, 'user_id', 'id');
	}

	public function article() {
		return $this->belongsTo(BlogArticle::class, 'article_id', 'id');
	}

}
