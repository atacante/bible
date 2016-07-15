<?php namespace App\Http\Controllers;

use App\Article;
use App\Category;

class BlogController extends Controller {

	/**
	 * Display a listing of the resource.
	 * GET /blog
	 *
	 * @return Response
	 */
	public function index()
	{
		$categories =  Category::all();
		$articles =  Article::all();
		return view('blog.blog',['categories'=>$categories, 'articles'=>$articles]);
	}

	/**
	 * Display a listing of the resource by category.
	 * GET /blog
	 *
	 * @return Response
	 */
	public function getCategory($id)
	{
		$categories =  Category::all();
		$articles =  Article::where('category_id', $id)->get();

		return view('blog.blog',['categories'=>$categories, 'articles'=>$articles]);
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