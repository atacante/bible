<?php

namespace App\Helpers;

use App\BlogCategory;
use App\CmsPage;
use App\Group;
use App\Journal;
use App\Note;
use App\Prayer;
use App\Tag;
use App\VersionsListEn;
use App\WallPost;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

class ViewHelper
{
    public static function prepareForSelectBox($items,$value,$text)
    {
        $optionsArr = [];
        if(count($items)){
            foreach($items as $item){
                $optionsArr[$item[$value]] = $item[$text];
            }
        }
        return $optionsArr;
    }

    public static function prepareChaptersForSelectBox($chapters)
    {
        $chaptersArr = [];
        if(count($chapters)){
            foreach($chapters as $chapter){
                $chaptersArr[$chapter['chapter_num']] = $chapter['chapter_num'];
            }
        }
        return $chaptersArr;
    }

    public static function prepareVersesForSelectBox($verses)
    {
        $versesArr = [];
        if(count($verses)){
            foreach($verses as $verse){
//                $versesArr[$verse['verse_num']] = 'Verse'.' '.$verse['verse_num'];
                $versesArr[$verse['verse_num']] = $verse['verse_num'];
            }
        }
        return $versesArr;
    }

    public static function isRoute($path)
    {
        $path = explode('.', $path);
        $segment = 1;
        foreach($path as $p) {
            if((request()->segment($segment) == $p) == false) {
                return false;
            }
            $segment++;
        }
        return true;
    }

    public static function classActivePath($paths)
    {
        if(!is_array($paths)){
            $paths = [$paths];
        }
        foreach ($paths as $path) {
            $path = explode('.', $path);
            $segment = 1;
            foreach($path as $p) {
                if((request()->segment($segment) == $p) == false) {
                    $segment = false;
                    break;
                }
                $segment++;
            }
            if($segment){
                return 'active';
            }
        }
    }

    public static function adminUrlSegment()
    {
        if(\Request::is('admin*')){
            return '/admin';
        }
        return '';
    }

    public static function prepareVerseText($verse, $allowDiff = false, $readerMode = 'beginner')
    {
        if((!$readerMode || $readerMode == 'beginner') && !empty($verse->verse_text_with_symbolism) && (!((Request::input('compare', false) || Request::segment(2) == 'verse') && (!Request::input('diff',false) || Request::input('diff',false) == 'on')) || $allowDiff)){
            return $verse->verse_text_with_symbolism;
        }
        elseif(!empty($verse->verse_text_with_lexicon) && (!((Request::input('compare', false) || Request::segment(2) == 'verse') && (!Request::input('diff',false) || Request::input('diff',false) == 'on')) || $allowDiff)){
            return $verse->verse_text_with_lexicon;
        }
        else{
            return $verse->verse_text;
        }
    }

    public static function highlightLexiconText($phrase,$text)
    {
        $phrase = str_replace('[','<i>',$phrase);
        $phrase = str_replace(']','</i>',$phrase);
        $text = str_replace($phrase,'<strong>'.$phrase.'</strong>',$text);
        return $text;
    }

    public static function detectStrongsDictionary($lexiconinfo)
    {
        if($lexiconinfo->book_id < 40){
            return 'hebrew';
        }
        return 'greek';

    }

    public static function getVerseNum($verseModel){
        return $verseModel?$verseModel->booksListEn->book_name." ".$verseModel->chapter_num.":".$verseModel->verse_num:'-';
    }

    public static function getChapterNum($verseModel){
        return $verseModel?$verseModel->booksListEn->book_name." ".$verseModel->chapter_num:'-';
    }

    public static function getVersionName($versionCode){
        return VersionsListEn::getVersionByCode($versionCode);
    }

    public static function getRelatedItemIcon($type){
        $icon = '';
        switch($type){
            case 'note':
                $icon = 'bs-note';
                break;
            case 'journal':
                $icon = 'bs-journal';
                break;
            case 'prayer':
                $icon = 'bs-pray';
                break;
        }
        return $icon;
    }

    public static function getAccessLevelIcon($level)
    {
        $html = '';

        switch($level){
            case Note::ACCESS_PRIVATE:
                $html = '<i title="Private" class="bs-s-onlyme color8 font-size-13" aria-hidden="true"></i>';
                break;
            case Note::ACCESS_PUBLIC_ALL:
            case WallPost::ACCESS_PUBLIC_ALL:
                $html = '<i title="Public - share with everyone" class="bs-s-public font-size-13" aria-hidden="true"></i>';
                break;
            case Note::ACCESS_PUBLIC_GROUPS:
                $html = '<i title="Public - share with Groups I am member of" class="bs-s-groups font-size-13" aria-hidden="true"></i>';
                break;
            case Note::ACCESS_SPECIFIC_GROUPS:
                $html = '<i title="Public - share with specific Groups" class="bs-s-groupscustom font-size-13" aria-hidden="true"></i>';
                break;
            case WallPost::ACCESS_PUBLIC_FRIENDS:
                $html = '<i title="Public - share with friends" class="bs-friends font-size-13" aria-hidden="true"></i>';
                break;
        }

        return $html;
    }

    public static function getGroupAccessLevelIcon($level)
    {
        $html = '';

        switch($level){
            case Group::ACCESS_SECRET:
                $html = '<i title="Private" class="bs-s-onlyme color8 font-size-13" aria-hidden="true"></i>';
                break;
            case Group::ACCESS_PUBLIC:
                $html = '<i title="Public" class="bs-s-public font-size-13" aria-hidden="true"></i>';
                break;
        }

        return $html;
    }

    public static function getEntryIcon($type)
    {
        $html = '';

        switch($type){
            case 'note':
                $html = '<i title="Note" class="c-verse-icon3 fa-book color7"></i>';
                break;
            case 'journal':
                $html = '<i title="Journal" class="c-verse-icon3 fa-hand-paper-o color6"></i>';
                break;
            case 'prayer':
                $html = '<i title="Prayer" class="c-verse-icon3 fa-sticky-note color5"></i>';
                break;
        }

        return $html;
    }

    public static function getEntryControllerName($entryType)
    {
        $action = '';

        switch($entryType){
            case 'note':
                $action = 'notes';
                break;
            case 'journal':
                $action = 'journal';
                break;
            case 'prayer':
                $action = 'prayers';
                break;
            case 'status':
                $action = 'wall-posts';
                break;
        }

        return $action;
    }

    public static function getMyLikes($entryType)
    {
        $ids = [];

        switch($entryType){
            case 'note':
                $ids = Auth::user()->notesLikes->modelKeys();
                break;
            case 'journal':
                $ids = Auth::user()->journalLikes->modelKeys();
                break;
            case 'prayer':
                $ids = Auth::user()->prayersLikes->modelKeys();
                break;
            case 'status':
                $ids = Auth::user()->statusesLikes->modelKeys();
                break;
        }
        return $ids;
    }

    public static function getMyContentReports($entryType)
    {
        $ids = [];

        switch($entryType){
            case 'note':
                $ids = Auth::user()->notesReports->modelKeys();
                break;
            case 'journal':
                $ids = Auth::user()->journalReports->modelKeys();
                break;
            case 'prayer':
                $ids = Auth::user()->prayersReports->modelKeys();
                break;
            case 'status':
                $ids = Auth::user()->statusesReports->modelKeys();
                break;
        }
        return $ids;
    }

    public static function getEntryTags($entryType,$entryId)
    {
        $tags = '';

        switch($entryType){
            case 'note':
                $tags = Tag::whereHas('notes', function ($q) use($entryId) {
                    $q->where('notes.id',$entryId);
                });
                break;
            case 'journal':
                $tags = Tag::whereHas('journal', function ($q) use($entryId) {
                    $q->where('journal.id',$entryId);
                });
                break;
            case 'prayer':
                $tags = Tag::whereHas('prayers', function ($q) use($entryId) {
                    $q->where('prayers.id',$entryId);
                });
                break;
        }

        return $tags->get();
    }

    public static function getEntriesCount($entryType,$model)
    {
        switch($entryType){
            case 'note':
                $entryModel = new Note();
                break;
            case 'journal':
                $entryModel = new Journal();

                break;
            case 'prayer':
                $entryModel = new Prayer();
                break;
        }

        if($model->verse_id){
            $entryModel = $entryModel->where('verse_id',$model->verse_id);
        }
        else{
            $entryModel = $entryModel->where('rel_code',$model->rel_code);
        }

        return $entryModel->count();
    }

    public static function getGroupAction($dataKey)
    {
        $action = '';
        switch($dataKey){
            case 'groups':
                $action = 'all-groups';
                break;
            case 'myGroups':
                $action = 'my-groups';
                break;
            case 'groupsRequested':
                $action = 'groups-requested';
                break;
            case 'myGroupsRequests':
                $action = 'my-groups-requests';
                break;
            case 'joinedGroups':
                $action = 'joined-groups';
                break;
        }
        return $action;
    }

    public static function checkEntryAccess($model)
    {
        return ($model->access_level == Note::ACCESS_PUBLIC_GROUPS || $model->access_level == Note::ACCESS_SPECIFIC_GROUPS);
    }

    public static function checkNotifTooltip($type)
    {
        switch($type){
            case 'got_related_records_tooltip':
                return Request::segment(2) == 'read' && !Request::input('compare',false) && Auth::check() && Auth::user()->checkNotifTooltip($type);
                break;
            case 'got_chapter_diff_tooltip':
                return Request::segment(2) == 'read' && Request::input('compare',false) && Auth::check() && Auth::user()->checkNotifTooltip($type);
                break;
            case 'got_verse_diff_tooltip':
                return Request::segment(2) == 'verse' && Auth::check() && Auth::user()->checkNotifTooltip($type);
                break;
        }
    }

    public static function getContent($contentType,$systemName)
    {
        $content = CmsPage::where('content_type',$contentType)->where('system_name',$systemName)->first();
        if(!$content){
            $content = new CmsPage();
        }
        return $content;
    }

    public static function checkWallAccess($model)
    {
        return Auth::check() &&
            (Auth::user()->id == $model->owner_id ||
                (($model->access_level == $model::ACCESS_SECRET) &&
                    in_array(Auth::user()->id,$model->members()->lists('users.id')->toArray())
                )
            ) ||
            ($model->access_level == $model::ACCESS_PUBLIC);
    }

    public static function checkBillingInfoToShow()
    {
        return !empty(Request::old('billing_first_name')) ||
               !empty(Request::old('billing_last_name')) ||
               !empty(Request::old('billing_address')) ||
               !empty(Request::old('billing_city')) ||
               !empty(Request::old('billing_postcode')) ||
               !empty(Request::old('billing_country')) ||
               !empty(Request::old('billing_state')) ||
               !empty(Request::old('billing_email')) ||
               !empty(Request::old('billing_phone'));
    }

    public static function getBlogCatId($catName)
    {
        $cat = BlogCategory::where('title',$catName)->first();
        if($cat){
            return $cat->id;
        }
        return false;
    }

    public static function getBookmarkIcon($type,$verse)
    {
        $class = 'fa-bookmark-o';
        $title = 'Add to bookmarks';
        if(self::checkBookmark($type,$verse)){
            $class = 'fa-bookmark';
            $title = 'Remove from bookmarks';
        }
        return '<i title="'.$title.'" class="fa '.$class.' cu-print" style="font-size: 2rem;"></i>';
    }

    public static function checkBookmark($type,$verse)
    {
        if(Auth::check()){
            return (boolean)$verse->bookmarks()->where('user_id',Auth::user()->id)->where('bookmark_type', $type)->get()->count();
        }
        return false;
    }
}