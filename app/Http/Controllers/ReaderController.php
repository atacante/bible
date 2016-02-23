<?php

namespace App\Http\Controllers;

use App\BaseModel;
use App\BooksListEn;
use App\Helpers\ViewHelper;
use App\VersesAmericanStandardEn;
use App\VersionsListEn;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ReaderController extends Controller
{
    public function getRead()
    {
        $version = Request::input('version', Config::get('app.defaultBibleVersion'));
        if($version == 'all'){
            return Redirect::to('/reader/overview?'.http_build_query(Request::input()));
        }
        $book = Request::input('book', Config::get('app.defaultBookNumber'));
        $chapter = Request::input('chapter', Config::get('app.defaultChapterNumber'));

        $versesModel = BaseModel::getVersesModelByVersionCode($version);

        $content['version'] = VersionsListEn::getVersionByCode($version);
        $content['heading'] = BooksListEn::find($book)->book_name . " " . $chapter;
        $content['verses'] = $versesModel::query()->where('book_id', $book)->where('chapter_num', $chapter)->orderBy('verse_num')->get();

        $content['pagination'] = $this->pagination($chapter, $book);

        $compare['versions'] = ViewHelper::prepareForSelectBox(VersionsListEn::versionsList(), 'version_code', 'version_name');
        $compareResetParams = Request::input();
        unset($compareResetParams['compare']);
        unset($compareResetParams['diff']);
        $compare['resetParams'] = $compareResetParams;
        if($compareVersion = Request::input('compare', false)){
            $compare['version'] = VersionsListEn::getVersionByCode($compareVersion);
            $compareVersesModel = BaseModel::getVersesModelByVersionCode($compareVersion);
            $compare['verses'] = $compareVersesModel::query()->where('book_id', $book)->where('chapter_num', $chapter)->orderBy('verse_num')->get();

            if(Request::input('diff', false)){
                $diff = new \cogpowered\FineDiff\Diff(new \cogpowered\FineDiff\Granularity\Word);
                if(count($compare['verses']) && count($content['verses'])){
                    foreach($content['verses'] as $key => $verse){
                        $compare['verses'][$key]->verse_text = $diff->render(strip_tags($verse->verse_text),strip_tags($compare['verses'][$key]->verse_text));
                    }
                }
            }
        }

        return view('reader.main', ['compare' => $compare,'content' => $content]);
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
        return view('reader.overview', [/*'filters' => $filters,*/ 'content' => $content]);
    }

    private function pagination($currentChapter, $currentBook)
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

        return ['chapterPrev' => $prevChapter, 'chapterNext' => $nextChapter, 'bookPrev' => $prevBook, 'bookNext' => $nextBook];
    }
}
