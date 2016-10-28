<?php namespace App;

class BlogArticle extends BaseModel {

	protected $fillable = ['category_id', 'text', 'title', 'user_id','author_name'];
	protected $dates = ['created_at', 'updated_at', 'published_at'];

	public function rules()
	{
		$rules = [
			'category_id' => 'required',
			'title' => 'required|max:255',
			'text' => 'required',

		];

		return $rules;
	}

	public function user() {
		return $this->belongsTo(User::class, 'user_id', 'id');
	}

	public function category() {
		return $this->belongsTo(BlogCategory::class, 'category_id', 'id');
	}

	public function comments(){
		return $this->hasMany(BlogComment::class, 'article_id', 'id')->orderBy('published_at', SORT_DESC);
	}

	public function views(){
		return $this->morphToMany('App\User','item','users_views')->withTimestamps();
	}

}
