<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model {

	protected $fillable = [];

	public function user() {
		return $this->belongsTo(User::class, 'user_id', 'id');
	}

	public function article() {
		return $this->belongsTo(Article::class, 'article_id', 'id');
	}

}
