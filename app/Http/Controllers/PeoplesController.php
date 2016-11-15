<?php

namespace App\Http\Controllers;

use App\Location;
//use Illuminate\Http\Request;

use App\Http\Requests;
use App\People;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

class PeoplesController extends Controller
{
    private $searchFilter;
    private $bookFilter;
    private $chapterFilter;
    private $verseFilter;

    private function prepareFilters($model)
    {
        $this->searchFilter = Request::input('search', false);
        $this->bookFilter = Request::input('book', false);
        $this->chapterFilter = Request::input('chapter', false);
        $this->verseFilter = Request::input('verse', false);

        if (!empty($this->searchFilter)) {
            $model->where('people_name', 'ilike', '%'.$this->searchFilter.'%');
//            $locationsModel->orWhere('location_description', 'ilike', '%'.$this->searchFilter.'%');
        }

        if (!empty($this->bookFilter)) {
            $model->whereHas('verses', function ($q) {
                $q->where('book_id', $this->bookFilter);
            });
        }

        if (!empty($this->chapterFilter)) {
            $model->whereHas('verses', function ($q) {
                $q->where('chapter_num', $this->chapterFilter);
            });
        }

        if (!empty($this->verseFilter)) {
            $model->whereHas('verses', function ($q) {
                $q->where('verse_num', $this->verseFilter);
            });
        }
        return $model;
    }

    public function getList()
    {

        Session::flash('backUrl', Request::fullUrl());

        $peoplesModel = $this->prepareFilters(People::query());

        $content['peoples'] = $peoplesModel->orderBy('people_name')->paginate(10);
        return view('peoples.list', ['content' => $content]);
    }

    public function getView($id)
    {
        if (Session::has('backUrl')) {
            Session::keep('backUrl');
        }

        $model = People::query()->with('images')->find($id);

        return view(
            'peoples.view',
            [
                'model' => $model,
            ]
        );
    }
}
