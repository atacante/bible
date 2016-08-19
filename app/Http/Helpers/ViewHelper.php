<?php

namespace App\Helpers;

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
                $chaptersArr[$chapter['chapter_num']] = $chapter['books_list_en']['book_name'].' '.$chapter['chapter_num'];
            }
        }
        return $chaptersArr;
    }

    public static function prepareVersesForSelectBox($verses)
    {
        $versesArr = [];
        if(count($verses)){
            foreach($verses as $verse){
                $versesArr[$verse['verse_num']] = 'Verse'.' '.$verse['verse_num'];
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

    public static function classActivePath($path)
    {
        $path = explode('.', $path);
        $segment = 1;
        foreach($path as $p) {
            if((request()->segment($segment) == $p) == false) {
                return '';
            }
            $segment++;
        }
        return ' active';
    }

    public static function adminUrlSegment()
    {
        if(\Request::is('admin*')){
            return '/admin';
        }
        return '';
    }

    public static function prepareVerseText($verse, $allowDiff = false)
    {
        $readerMode = Request::cookie('readerMode',false);
        if((!$readerMode || $readerMode == 'beginner') && !empty($verse->verse_text_with_symbolism) && (!Request::input('diff',false) || $allowDiff)){
            return $verse->verse_text_with_symbolism;
        }
        elseif(!empty($verse->verse_text_with_lexicon) && (!Request::input('diff',false) || $allowDiff)){
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
        $text = str_replace($phrase,'<i><span><strong>'.$phrase.'</strong></span></i>',$text);
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
                $icon = 'fa-sticky-note';
                break;
            case 'journal':
                $icon = 'fa-book';
                break;
            case 'prayer':
                $icon = 'fa-hand-paper-o';
                break;
        }
        return $icon;
    }

    public static function getAccessLevelIcon($level)
    {
        $html = '';

        switch($level){
            case Note::ACCESS_PRIVATE:
                $html = '<i title="Private" class="fa fa-lock" aria-hidden="true"></i>';
                break;
            case Note::ACCESS_PUBLIC_ALL:
            case WallPost::ACCESS_PUBLIC_ALL:
                $html = '<i title="Public - share with everyone" class="fa fa-globe" aria-hidden="true"></i>';
                break;
            case Note::ACCESS_PUBLIC_GROUPS:
                $html = '<i title="Public - share with Groups I am member of" class="fa fa-users" aria-hidden="true"></i>';
                break;
            case WallPost::ACCESS_PUBLIC_FRIENDS:
                $html = '<i title="Public - share with friends" class="fa ion-person-stalker" aria-hidden="true"></i>';
                break;
        }

        return $html;
    }

    public static function getEntryIcon($type)
    {
        $html = '';

        switch($type){
            case 'note':
                $html = '<i title="Note" class="fa fa-btn fa-sticky-note"></i>';
                break;
            case 'journal':
                $html = '<i title="Journal" class="fa fa-btn fa-book"></i>';
                break;
            case 'prayer':
                $html = '<i title="Prayer" class="fa fa-btn fa-hand-paper-o"></i>';
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

    public static function showTooltip($model)
    {

    }
}