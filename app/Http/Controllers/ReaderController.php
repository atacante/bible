<?php

namespace App\Http\Controllers;

use App\BaseModel;
use App\BooksListEn;
use App\Helpers\ViewHelper;
use App\LexiconKjv;
use App\VersesAmericanStandardEn;
use App\VersionsListEn;
use FineDiffTests\Usage\Base;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ReaderController extends Controller
{
    private $readerMode = 'beginner';

    public function getRead()
    {
        $version = Request::input('version', Config::get('app.defaultBibleVersion'));
        if ($version == 'all') {
            return Redirect::to('/reader/overview?' . http_build_query(Request::input()));
        }
        $book = Request::input('book', Config::get('app.defaultBookNumber'));
        $chapter = Request::input('chapter', Config::get('app.defaultChapterNumber'));

        $versesModel = BaseModel::getVersesModelByVersionCode($version);

        $content['version'] = VersionsListEn::getVersionByCode($version);
        $content['version_code'] = $version;
        $content['heading'] = BooksListEn::find($book)->book_name . " " . $chapter;
        $content['verses'] = $versesModel::query()->where('book_id', $book)->where('chapter_num', $chapter)->orderBy('verse_num')->get();
//        if ($version == 'king_james') {
//            foreach ($content['verses'] as $verse) {
//                $lexicon = $verse->lexicon();
//                if ($lexicon) {
//                    $parts = [];
//                    foreach($lexicon as $vesrePart){
//                        $parts[$vesrePart->id] = $vesrePart->verse_part;
//                    }
//
//                    $parts = array_unique($parts);
//                    uasort($parts,function($a,$b){
//                        return strlen($b)-strlen($a);
//                    });
//
//                    foreach ($parts as $key => $part) {
//                        $part = str_replace('[','<i>',$part);
//                        $part = str_replace(']','</i>',$part);
//                        $verse->verse_text = str_replace($part,"<-$key->".$part."<|>",$verse->verse_text);
//                    }
//
//                    $verse->verse_text = str_replace("<-","<span class='word-definition' data-lexid=\"",$verse->verse_text);
//                    $verse->verse_text = str_replace("->",'">',$verse->verse_text);
//                    $verse->verse_text = str_replace("<|>","</span>",$verse->verse_text);
//
////                    $verse->verse_text = str_replace("<->","<span class='word-definition' data-lexid=\"$key\">",$verse->verse_text);
////                    $verse->verse_text = str_replace("<-!>","</span>",$verse->verse_text);
//                }
//            }
//        }

        $content['pagination'] = $this->pagination($chapter, $book);

        $compare['versions'] = ViewHelper::prepareForSelectBox(VersionsListEn::versionsList(), 'version_code', 'version_name');
        $compareResetParams = Request::input();
        unset($compareResetParams['compare']);
        unset($compareResetParams['diff']);
        $compare['resetParams'] = $compareResetParams;
        if ($compareVersion = Request::input('compare', false)) {
            $compare['version'] = VersionsListEn::getVersionByCode($compareVersion);
            $compare['version_code'] = $compareVersion;
            $compareVersesModel = BaseModel::getVersesModelByVersionCode($compareVersion);
            $compare['verses'] = $compareVersesModel::query()->where('book_id', $book)->where('chapter_num', $chapter)->orderBy('verse_num')->get();

            if (Request::input('diff', false)) {
                $diff = new \cogpowered\FineDiff\Diff(new \cogpowered\FineDiff\Granularity\Word);
                if (count($compare['verses']) && count($content['verses'])) {
                    foreach ($content['verses'] as $key => $verse) {
                        $compare['verses'][$key]->verse_text = $diff->render(strip_tags($verse->verse_text), strip_tags($compare['verses'][$key]->verse_text));
                    }
                }
            }
        }

        return view('reader.main', ['compare' => $compare, 'content' => $content]);
    }

    public function getOverview()
    {
        $book = Request::input('book', Config::get('app.defaultBookNumber'));
        $chapter = Request::input('chapter', Config::get('app.defaultChapterNumber'));

        $content['heading'] = BooksListEn::find($book)->book_name . " " . $chapter;
        $content['verses'] = [];
        $versions = VersionsListEn::versionsList();
        if ($versions) {
            foreach ($versions as $version) {
                $versesModel = BaseModel::getVersesModelByVersionCode($version['version_code']);
                $content['versions'][$version['version_code']]['version_name'] = $version['version_name'];
                $content['versions'][$version['version_code']]['verses'] = $versesModel::query()->where('book_id', $book)->where('chapter_num', $chapter)->orderBy('verse_num')->limit(3)->get();
            }
        }
        return view('reader.overview', [/*'filters' => $filters,*/
            'content' => $content]);
    }

    public function getSearch()
    {
        $q = Request::input('q', false);
        $version = Request::input('version', Config::get('app.defaultBibleVersion'));

        $versions = VersionsListEn::versionsList();
        $content = [];

        $versesModel = BaseModel::getVersesModelByVersionCode($version);
//        $content['verses'] = $versesModel::query()
//                                ->with('booksListEn')
//                                ->where('verse_text', 'ilike', '%'.$q.'%')
//                                ->orderBy('book_id')
//                                ->orderBy('chapter_num')
//                                ->orderBy('verse_num')
//                                ->paginate(10)
        /*->get()*/;
//        $content['verses'] = $versesModel::query()
//                                ->select(DB::raw('
//                                    ts_rank_cd(searchtext, queryPhrase) rankPhrase,
//                                    ts_rank_cd(searchtext, queryWord) rankWord,
//                                    ts_headline(\'english\',verse_text,queryPhrase,\'HighlightAll=TRUE\') highlighted_verse_text,
//                                    *
//                                '))
//                                ->from(DB::raw('
//                                    bible_test,
//                                    to_tsquery(\'Better & is & the & end\') queryPhrase,
//                                    to_tsquery(\'Better | is | the | end\') queryWord
//                                '))
////                                ->with('booksListEn')
//                                ->whereRaw(DB::raw('
//                                    searchtext @@ queryPhrase OR searchtext @@ queryWord
//                                '))
////                                ->orderBy('rankPhrase','DESC')
////                                ->orderBy('rankWord','DESC')
//                                ->orderByRaw(DB::raw('rankPhrase DESC,rankWord DESC'))
//                                ->paginate(10);

//        if ($versions && $q) {
//            foreach ($versions as $version) {
//                $versesModel = BaseModel::getVersesModelByVersionCode($version['version_code']);
//                $versesModel::query()->where('book_id', $book)->where('chapter_num', $chapter)->orderBy('verse_num')->limit(3)->get();
//            }
//        }
        $content['verses'] = BaseModel::searchEverywhere($q)->paginate(10);
        return view('reader.search', ['content' => $content]);
    }

    public function getVerse()
    {
        $version_code = Request::input('version', Config::get('app.defaultBibleVersion'));
        $book = Request::input('book', Config::get('app.defaultBookNumber'));
        $chapter = Request::input('chapter', Config::get('app.defaultChapterNumber'));
        $verse = Request::input('verse', false);

        $versions = VersionsListEn::versionsList();

        if ($verse) {
            $verseModel = BaseModel::getVersesModelByVersionCode($version_code);
            $content['main_verse']['version_name'] = VersionsListEn::getVersionByCode($version_code);
            $content['main_verse']['verse'] = $verseModel::query()
                ->where('book_id', $book)
                ->where('chapter_num', $chapter)
                ->where('verse_num', $verse)
                ->first();

            $content['lexicon'] = LexiconKjv::query()
                ->where('book_id',$book)
                ->where('chapter_num',$chapter)
                ->where('verse_num',$verse)
                ->orderBy('id')
                ->get();

            foreach ($versions as $version) {
                if ($version['version_code'] != $version_code) {
                    $versesModel = BaseModel::getVersesModelByVersionCode($version['version_code']);
                    $content['verse'][$version['version_code']]['version_name'] = $version['version_name'];
                    $content['verse'][$version['version_code']]['verse'] =
                        $versesModel::query()
                            ->where('book_id', $book)
                            ->where('chapter_num', $chapter)
                            ->where('verse_num', $verse)
                            ->first();
                }
            }

            if (Request::input('diff', false)) {
                $diff = new \cogpowered\FineDiff\Diff(new \cogpowered\FineDiff\Granularity\Word);
                foreach ($content['verse'] as $key => $version) {
                    $content['verse'][$key]['verse']->verse_text = $diff->render(strip_tags($content['main_verse']['verse']->verse_text), strip_tags($version['verse']->verse_text));
                }
            }
        }
        $content['pagination'] = $this->pagination($chapter, $book, $verse);
        return view('reader.verse', ['content' => $content, 'filterAction' => 'verse']);
    }

    private function pagination($currentChapter, $currentBook, $currentVerse = false)
    {
        $params = Request::input();

        $allBooks = ViewHelper::prepareForSelectBox(BooksListEn::all()->toArray(), 'id', 'book_name');
        $allChapters = ViewHelper::prepareChaptersForSelectBox(BaseModel::getChapters($currentBook));

        /* Chapters links */
        $prevChapter = $currentChapter - 1;

        if ($prevChapter == 0) {
            $prevChapter = false;
        } else {
            $params['chapter'] = $prevChapter;
            $prevChapter = $params;
        }

        $nextChapter = $currentChapter + 1;
        if (!array_key_exists($nextChapter, $allChapters)) {
            $nextChapter = false;
        } else {
            $params['chapter'] = $nextChapter;
            $nextChapter = $params;
        }

        /* Books links */
        $prevBook = $currentBook - 1;

        if ($prevBook == 0) {
            $prevBook = false;
        } else {
            $params['book'] = $prevBook;
            $params['chapter'] = 1;
            $prevBook = $params;
        }

        $nextBook = $currentBook + 1;
        if (!array_key_exists($nextBook, $allBooks)) {
            $nextBook = false;
        } else {
            $params['book'] = $nextBook;
            $params['chapter'] = 1;
            $nextBook = $params;
        }

        /* Verse links */
        $prevVerse = false;
        $nextVerse = false;
        if ($currentVerse) {
            $allVerses = ViewHelper::prepareVersesForSelectBox(BaseModel::getVerses($currentBook, $currentChapter));
            $prevVerse = $currentVerse - 1;

            if ($prevVerse == 0) {
                $prevVerse = false;
            } else {
                $params['verse'] = $prevVerse;
                $params['chapter'] = $currentChapter;
                $params['book'] = $currentBook;
                $prevVerse = $params;
            }

            $nextVerse = $currentVerse + 1;
            if (!array_key_exists($nextVerse, $allVerses)) {
                $nextVerse = false;
            } else {
                $params['verse'] = $nextVerse;
                $params['chapter'] = $currentChapter;
                $params['book'] = $currentBook;
                $nextVerse = $params;
            }
        }

        return [
            'chapterPrev' => $prevChapter,
            'chapterNext' => $nextChapter,
            'bookPrev' => $prevBook,
            'bookNext' => $nextBook,
            'versePrev' => $prevVerse,
            'verseNext' => $nextVerse,
        ];
    }

    public function anyMode($mode)
    {
        Cookie::queue(Cookie::forever('readerMode', $mode));
        return Redirect::to(URL::previous());
    }
}
