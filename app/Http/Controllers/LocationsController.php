<?php

namespace App\Http\Controllers;

use App\Location;
//use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

class LocationsController extends Controller
{
    private $searchFilter;
    private $bookFilter;
    private $chapterFilter;
    private $verseFilter;

    private function prepareFilters($locationsModel)
    {
        $this->searchFilter = Request::input('search', false);
        $this->bookFilter = Request::input('book', false);
        $this->chapterFilter = Request::input('chapter', false);
        $this->verseFilter = Request::input('verse', false);

        if (!empty($this->searchFilter)) {
            $locationsModel->where('location_name', 'ilike', '%'.$this->searchFilter.'%');
//            $locationsModel->orWhere('location_description', 'ilike', '%'.$this->searchFilter.'%');
        }

        if (!empty($this->bookFilter)) {
            $locationsModel->whereHas('verses', function ($q) {
                $q->where('book_id', $this->bookFilter);
            });
        }

        if (!empty($this->chapterFilter)) {
            $locationsModel->whereHas('verses', function ($q) {
                $q->where('chapter_num', $this->chapterFilter);
            });
        }

        if (!empty($this->verseFilter)) {
            $locationsModel->whereHas('verses', function ($q) {
                $q->where('verse_num', $this->verseFilter);
            });
        }
        return $locationsModel;
    }

    public function getList()
    {

        Session::flash('backUrl', Request::fullUrl());

        $locationsModel = new Location();

        $locationsModel = $this->prepareFilters($locationsModel->query());

        $content['locations'] = $locationsModel->orderBy('location_name')->paginate(10);
        return view('locations.list', ['content' => $content]);
    }

    public function getView($id)
    {
        if (Session::has('backUrl')) {
            Session::keep('backUrl');
        }

        $model = Location::query()->with('images')->find($id);

        return view(
            'locations.view',
            [
                'model' => $model,
            ]
        );
    }
}
