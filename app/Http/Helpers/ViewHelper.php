<?php

namespace App\Helpers;

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
        $text = str_replace($phrase,'<i><span class="text-red">'.$phrase.'</span></i>',$text);
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
}