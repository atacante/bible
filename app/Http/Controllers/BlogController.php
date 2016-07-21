<?php namespace App\Http\Controllers;

use App\BlogArticle;
use App\BlogCategory;
use App\BlogComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class BlogController extends Controller {

	/**
	 * Display a listing of the resource.
	 * GET /blog
	 *
	 * @return Response
	 */
	public function getIndex()
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
		$articles =  BlogArticle::where('category_id', $id)
						->orderBy('published_at', SORT_DESC)
						->paginate(1);

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

	public function postSaveComment(Request $request)
	{
		$article = BlogArticle::find(Input::get('id'));
		if (!$article) {
			abort(404);
		}

		$model = new BlogComment();
		$text = Input::get('text');
		$data = ['user_id' => Auth::user()->id, 'text' => $text];
		$this->validate($request, $model->rules());

		$commentCreated = $article->comments()->create($data);
		if ($commentCreated) {
			return view('blog.article_view',['article'=>$article]);
		}
	}

}