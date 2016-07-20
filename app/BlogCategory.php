<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class BlogCategory extends BaseModel {

	protected $fillable = [];

	public function articles(){
		return $this->hasMany(BlogArticle::class, 'category_id', 'id');
	}
}
