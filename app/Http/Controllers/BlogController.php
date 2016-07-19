<?php namespace App\Http\Controllers;

use App\BlogArticle;
use App\BlogCategory;

class BlogController extends Controller {

	/**
	 * Display a listing of the resource.
	 * GET /blog
	 *
	 * @return Response
	 */
	public function index()
	{
		$categories =  BlogCategory::all();
		$articles =  BlogArticle::orderBy('published_at', SORT_DESC)->paginate(2);

		return view('blog.blog',['categories'=>$categories, 'articles'=>$articles]);
	}

	/**
	 * Display a listing of the resource by category.
	 * GET /blog/category/{id}
	 *
	 * @return Response
	 */
	public function getCategory($id)
	{
		$categories =  BlogCategory::all();
		$articles =  BlogArticle::where('category_id', $id)->orderBy('published_at', SORT_DESC)->paginate(1);

		return view('blog.blog',['categories'=>$categories, 'articles'=>$articles]);
	}

	/**
	 * Display a view of the .
	 * GET /blog/article/{id}
	 *
	 * @return Response
	 */
	public function getArticle($id)
	{
		$article =  BlogArticle::find($id);

		return view('blog.article_view',['article'=>$article]);
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /blog/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /blog
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /blog/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /blog/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /blog/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /blog/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}