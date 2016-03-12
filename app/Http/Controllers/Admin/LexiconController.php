<?php

namespace App\Http\Controllers\Admin;

use App\BaseModel;
use App\LexiconsListEn;
use \Illuminate\Support\Facades\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LexiconController extends Controller
{
    public function getList()
    {
        $content['lexicons'] = LexiconsListEn::lexiconsList();
        return view('admin.lexicon.list', ['page_title' => "Lexicons",'content' => $content]);
    }

    public function getView($code)
    {
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
        return view('admin.lexicon.view', ['page_title' => $lexicon,'content' => $content,'filterAction' => 'lexicon/view/'.$code]);
    }
}
