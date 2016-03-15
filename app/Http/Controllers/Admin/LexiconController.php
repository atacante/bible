<?php

namespace App\Http\Controllers\Admin;

use App\BaseModel;
use App\LexiconKjv;
use App\LexiconsListEn;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use \Illuminate\Support\Facades\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class LexiconController extends Controller
{
    public function getList()
    {
        $content['lexicons'] = LexiconsListEn::lexiconsList();
        return view('admin.lexicon.list', ['page_title' => "Lexicons",'content' => $content]);
    }

    public function getView($code)
    {
        Session::flash('backUrl', Request::fullUrl());

        $book = Request::input('book', false);
        $chapter = Request::input('chapter', false);
        $verse = Request::input('verse', false);

        $lexicon = LexiconsListEn::getLexiconByCode($code);
        $lexiconModel = BaseModel::getModelByTableName('lexicon_'.$code);

        $lexiconinfo = $lexiconModel::query()->with('booksListEn');
        if(!empty($book)){
            $lexiconinfo->where('book_id',$book);
        }

        if(!empty($chapter)){
            $lexiconinfo->where('chapter_num',$chapter);
        }

        if(!empty($verse)){
            $lexiconinfo->where('verse_num',$verse);
        }
        $content['lexiconinfo'] = $lexiconinfo->orderBy('book_id')->orderBy('chapter_num')->orderBy('verse_num')->paginate(20);
        return view('admin.lexicon.view',
            [
                'page_title' => $lexicon,
                'content' => $content,
                'filterAction' => 'lexicon/view/'.$code,
                'lexiconCode' => $code,
                'lexiconName' => $lexicon
            ]);
    }

    public function anyUpdate(\Illuminate\Http\Request $request,$code,$id)
    {
        if (Session::has('backUrl')) {
            Session::keep('backUrl');
        }
        $lexiconModel = BaseModel::getModelByTableName('lexicon_'.$code);
        $lexiconName = LexiconsListEn::getLexiconByCode($code);
        $lexicon = $lexiconModel::find($id);
        if (Request::isMethod('put')) {
            $this->validate($request, [
                'verse_part' => 'required',
                'strong_num' => 'required',
                'transliteration' => 'required',
                'strong_1_word_def' => 'required',
            ]);
            $lexicon->update(Input::all());
            return ($url = Session::get('backUrl'))
                ? Redirect::to($url)
                : Redirect::to('/admin/lexicon/view/'.$code);
        }
        return view('admin.lexicon.update',
            [
                'page_title' => 'Update Lexicon Item',
                'model' => $lexicon,
                'lexiconCode' => $code,
                'lexiconName' => $lexiconName,
            ]);
    }
}
