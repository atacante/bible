<?php

namespace App\Helpers;

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
}