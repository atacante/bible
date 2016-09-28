<?php

namespace App\Http\Controllers\Admin;

use App\BlogArticle;
use App\BlogCategory;
use App\Http\Requests;
use App\Http\Controllers\Controller;


class BlogController extends Controller
{
    public function getList()
    {
        $content['categoriesCount'] = BlogCategory::query()->count();
        $content['articlesCount'] = BlogArticle::query()->count();

        return view('admin.blog.list',
            [
                'page_title' => 'CMS',
                'content' => $content,
                'filterAction' => 'blog/list/',
            ]);
    }
}
