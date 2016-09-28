<?php

namespace App\Http\Controllers\Admin;

use App\BaseModel;
use App\Helpers\ViewHelper;
use App\Lexicon;
use App\LexiconKjv;
use App\LexiconsListEn;
use App\Location;
use App\People;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use \Illuminate\Support\Facades\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Krucas\Notification\Facades\Notification;

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
        $content['lexiconinfo'] = $lexiconinfo->orderBy('book_id')->orderBy('chapter_num')->orderBy('verse_num')->orderBy('id')->paginate(20);
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
        $lexicon = $lexiconModel::with('locations')->find($id);

//        $lexiconModel = new Lexicon('lexicon_'.$code);
//        $lexicon = $lexiconModel->with('locations')->find($id);

        $locations = ViewHelper::prepareForSelectBox(Location::query()->get()->toArray(),'id','location_name');
        $peoples = ViewHelper::prepareForSelectBox(People::query()->get()->toArray(),'id','people_name');
        if (Request::isMethod('put')) {
            $this->validate($request, [
                'verse_part' => 'required',
                'strong_num' => 'required',
                'transliteration' => 'required',
                'definition' => 'required',
            ]);
            if ($lexicon->update(Input::all())) {
                $lexicon->locations()->sync(Input::get('locations',[]));
                $lexicon->peoples()->sync(Input::get('peoples',[]));
                $this->updateLexicons($id);

                Notification::success('Lexicon has been successfully updated');
            }

            return ($url = Session::get('backUrl'))
                ? Redirect::to($url)
                : Redirect::to(ViewHelper::adminUrlSegment().'/lexicon/view/'.$code);
        }

        $lexiconsList = LexiconsListEn::lexiconsList();
        $lexicons = [];
        foreach ($lexiconsList as $key => $lexiconsItem) {
            $lexiconListModel = LexiconsListEn::getLexiconModelByVersionCode($lexiconsItem['lexicon_code']);
            if($lexiconsListItem = $lexiconListModel->find($id)){
                $lexicons[$key] = $lexiconsItem;
                $lexicons[$key]['phrase'] = $lexiconsListItem->verse_part;
            }
        }

        return view('admin.lexicon.update',
            [
                'page_title' => 'Update Lexicon Item',
                'model' => $lexicon,
                'lexiconCode' => $code,
                'lexiconName' => $lexiconName,
                'locations' => $locations,
                'peoples' => $peoples,
                'lexicons' => $lexicons,
            ]);
    }

    private function updateLexicons($id)
    {
        if($lexicons = Input::get("lexicons",false)){
            foreach ($lexicons as $lexiconCode) {
                $lexiconModel = LexiconsListEn::getLexiconModelByVersionCode($lexiconCode);
                if($lexicon = $lexiconModel->find($id)){
                    $lexicon->symbolism = Input::get("symbolism");
                    $lexicon->save();
                }
            }
        }
    }
}
