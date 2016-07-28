<?php

namespace App\Http\Controllers\Admin;

use App\BlogArticle;
use App\BlogCategory;
use App\Http\Requests;
use App\Http\Controllers\Controller;


class CmsController extends Controller
{
    public function getList()
    {
        $content['categoriesCount'] = BlogCategory::query()->count();
        $content['articlesCount'] = BlogArticle::query()->count();

        return view('admin.cms.list',
            [
                'page_title' => 'Static Pages',
                'content' => $content,
                'filterAction' => 'cms/list/',
            ]);
    }
}
