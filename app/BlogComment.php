<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class BlogComment extends BaseModel {



	protected $fillable = ['text', 'user_id'];

	public function rules()
	{
		$rules = [
			'text' => 'required',
		];

		return $rules;
	}

	public function user() {
		return $this->belongsTo(User::class, 'user_id', 'id');
	}

	public function article() {
		return $this->belongsTo(BlogArticle::class, 'article_id', 'id');
	}

}
