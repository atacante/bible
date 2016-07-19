<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model {

	protected $fillable = [];

	public function articles(){
		return $this->hasMany(BlogArticle::class, 'category_id', 'id');
	}
}
