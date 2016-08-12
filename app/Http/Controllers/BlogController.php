<?php namespace App\Http\Controllers;

use App\BlogArticle;
use App\BlogCategory;
use App\BlogComment;
use App\UsersViews;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class BlogController extends Controller {


	private function prepareFilters($model)
	{
		$searchFilter = Input::get('search', false);
		$categoryFilter = Input::get('category', false);

		if (!empty($searchFilter)) {
			$model
				->where('text', 'ilike', '%' . $searchFilter . '%')
				->orWhere('title', 'ilike', '%' . $searchFilter . '%');
		}

		if(!empty($categoryFilter)){
			$model->where('category_id', $categoryFilter);
		}

		return $model;
	}

	/**
	 * Display a listing of the resource.
	 * GET /blog
	 *
	 * @return Response
	 */
	public function getIndex()
	{
		$categories =  BlogCategory::all();
		$articles =  BlogArticle::query();

		$articles = $this->prepareFilters($articles);

		$articles = $articles->orderBy('published_at', SORT_DESC)->paginate(10);

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
		/* Track user views */
		if(Auth::check() && $article){
			UsersViews::thackView($article,UsersViews::CAT_BLOG);
		}
		/* End Track user views */
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
			return Redirect::to('/blog/article/'.$article->id);
		}
	}

}