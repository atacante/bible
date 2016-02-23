<?php

namespace App\Http\Composers;

use App\BaseModel;
use App\BooksListEn;
use App\Helpers\ViewHelper;
use App\VersionsListEn;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;

class BibleFiltersComposer {

    public function __construct()
    {

    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $book = Request::input('book', Config::get('app.defaultBookNumber'));
        $chapter = Request::input('chapter',Config::get('app.defaultChapterNumber'));
        $filters['versions'] = ViewHelper::prepareForSelectBox(VersionsListEn::versionsList(), 'version_code', 'version_name');
        $filters['books'] = ViewHelper::prepareForSelectBox(BooksListEn::all()->toArray(), 'id', 'book_name');
        $filters['chapters'] = ViewHelper::prepareChaptersForSelectBox(BaseModel::getChapters($book));
        $filters['verses'] = ViewHelper::prepareVersesForSelectBox(BaseModel::getVerses($book,$chapter));
        $view->with('filters', $filters);
    }

}