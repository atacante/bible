<?php

namespace App\Http\Controllers\Admin;

use App\BaseModel;
use App\VersionsListEn;
use Illuminate\Http\Request as httpRequest;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

class BibleController extends Controller
{
    public function getVersions()
    {
        $content['versions'] = VersionsListEn::versionsList();
        return view('admin.bible.versions', ['page_title' => "Bible Versions",'content' => $content]);
    }

    public function getVerses($code)
    {
        Session::flash('backUrl', Request::fullUrl());

        $book = Request::input('book', false);
        $chapter = Request::input('chapter', false);
        $verse = Request::input('verse', false);

        $version = VersionsListEn::getVersionByCode($code);
        $versesModel = BaseModel::getVersesModelByVersionCode($code);

        $verses = $versesModel::query()->with('booksListEn');
        if(!empty($book)){
            $verses->where('book_id',$book);
        }

        if(!empty($chapter)){
            $verses->where('chapter_num',$chapter);
        }

        if(!empty($verse)){
            $verses->where('verse_num',$verse);
        }
        $content['verses'] = $verses->orderBy('book_id')->orderBy('chapter_num')->orderBy('verse_num')->paginate(20);
        return view('admin.bible.verses',
            [
                'page_title' => $version.' Verses',
                'content' => $content,
                'filterAction' => 'bible/verses/'.$code,
                'versionCode' => $code,
                'versionName' => $version
            ]);
    }

    public function anyUpdate(\Illuminate\Http\Request $request,$code,$id)
    {
        if (Session::has('backUrl')) {
            Session::keep('backUrl');
        }
        $versesModel = BaseModel::getVersesModelByVersionCode($code);
        $version = VersionsListEn::getVersionByCode($code);
        $verse = $versesModel::find($id);
        if (Request::isMethod('put')) {
            $this->validate($request, [
                'verse_text' => 'required',
            ]);
            $verse->update(Input::all());
            return ($url = Session::get('backUrl'))
                ? Redirect::to($url)
                : Redirect::to('/admin/bible/verses/'.$code);
        }
        return view('admin.bible.update',
            [
                'page_title' => 'Update Bible Verse',
                'model' => $verse,
                'version' => $version,
                'versionCode' => $code,
                'versionName' => $version
            ]);
    }
}
