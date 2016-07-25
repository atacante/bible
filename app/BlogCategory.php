<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class BlogCategory extends BaseModel {

	protected $fillable = ['title'];

	public function rules()
	{
		$rules = [
			'title' => 'required|max:255',
		];

		return $rules;
	}

	public function articles(){
		return $this->hasMany(BlogArticle::class, 'category_id', 'id');
	}
}
