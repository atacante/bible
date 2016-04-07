<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

class ModelHelper
{
    public static function cacheLexicon($verses = [],$version = false){
        ini_set('memory_limit', '512M');
        if(!count($verses)){
            $verses = self::query()->get();
        }
        $versesCount = count($verses);
        $progressBarPersentStep = 1;
        $progressBarPart = round($versesCount/(100/$progressBarPersentStep));
        $progressBarStatus = 0;
        $i = 0;
        foreach ($verses as $verse) {
            $i++;
            $verse->verse_text_with_lexicon = $verse->verse_text;
            $lexicon = $verse->lexicon();
            if ($lexicon) {
                $parts = [];
                foreach($lexicon as $vesrePart){
                    if(!empty($vesrePart->verse_part) && trim($vesrePart->verse_part) != '-'){
                        $parts[$vesrePart->id] = $vesrePart->verse_part;
                    }
                }
                $parts = array_unique($parts);
                uasort($parts,function($a,$b){
                    return strlen($b)-strlen($a);
                });
                if($verse->book_id == 40 && $verse->chapter_num == 1 && $verse->verse_num == 2){
//                    var_dump($verse->book_id);
//                    var_dump($verse->chapter_num);
//                    var_dump($verse->verse_num);
//                    var_dump($verse->verse_text);
//                    var_dump($parts);
//                    exit;
                }

                foreach ($parts as $key => $part) {
                    $part = str_replace('[','<i>',$part);
                    $part = str_replace(']','</i>',$part);
                    $verse->verse_text_with_lexicon = str_replace($part,"<-$key->".$part."<|>",$verse->verse_text_with_lexicon);
                }

                $verse->verse_text_with_lexicon = str_replace("<-","<span class='word-definition'  data-lexversion= \"".$version."\" data-lexid=\"",$verse->verse_text_with_lexicon);
                $verse->verse_text_with_lexicon = str_replace("->",'">',$verse->verse_text_with_lexicon);
                $verse->verse_text_with_lexicon = str_replace("<|>","</span>",$verse->verse_text_with_lexicon);
                $verse->save();
            }
            if($i == $progressBarPart){
                echo "Progress ".($progressBarStatus+$progressBarPersentStep)."%\n";
                $progressBarStatus++;
                $i = 0;
            }
        }
    }

    public static function cacheSymbolismForBeginnerMode($verses = [],$version = false){
        ini_set('memory_limit', '512M');
        if(!count($verses)){
            $verses = self::query()->get();
        }
        $versesCount = count($verses);
        $progressBarPersentStep = 1;
        $progressBarPart = round($versesCount/(100/$progressBarPersentStep));
        $progressBarStatus = 0;
        $i = 0;
        foreach ($verses as $verse) {
            $i++;
            $verse->verse_text_with_symbolism = $verse->verse_text;
            $lexicon = $verse->symbolism();
            if ($lexicon) {
                $parts = [];
                foreach($lexicon as $vesrePart){
                    if(!empty($vesrePart->symbolism)){
                        $parts[$vesrePart->id] = $vesrePart->verse_part;
                    }
                }

                if(count($parts)){
                    $parts = array_unique($parts);
                    uasort($parts,function($a,$b){
                        return strlen($b)-strlen($a);
                    });

                    foreach ($parts as $key => $part) {
                        $part = str_replace('[','<i>',$part);
                        $part = str_replace(']','</i>',$part);
                        $verse->verse_text_with_symbolism = str_replace($part,"<-$key->".$part."<|>",$verse->verse_text_with_symbolism);
                    }

                    $verse->verse_text_with_symbolism = str_replace("<-","<span class='word-definition' data-lexversion= \"".$version."\" data-lexid=\"",$verse->verse_text_with_symbolism);
                    $verse->verse_text_with_symbolism = str_replace("->",'">',$verse->verse_text_with_symbolism);
                    $verse->verse_text_with_symbolism = str_replace("<|>","</span>",$verse->verse_text_with_symbolism);
                }

                $verse->save();
            }
            if($i == $progressBarPart){
                echo "Progress ".($progressBarStatus+$progressBarPersentStep)."%\n";
                $progressBarStatus++;
                $i = 0;
            }
        }
    }
}