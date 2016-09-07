<?php

namespace App\Helpers;

use App\BaseModel;
use App\LexiconBase;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

class ModelHelper
{
    public static function cacheLexicon($verses = [],$version = false, $versesIds = []){
        ini_set('memory_limit', '512M');
        if(!count($verses)){
            $verses = self::query()->get();
        }
        if(count($versesIds)){
            $verses = self::whereIn('id',$versesIds)->get();
        }
        $progressBar = new ProgressBarHelper(count($verses),10);
        $progressBar->start('Started caching lexicon for '.$version.' version');
        foreach ($verses as $verse) {
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
            $progressBar->update();
        }
        $progressBar->finish();
    }

    public static function cacheSymbolismForBeginnerMode($verses = [],$version = false){
        ini_set('memory_limit', '512M');
        if(!count($verses)){
            $verses = self::query()->get();
        }
        $progressBar = new ProgressBarHelper(count($verses),10);
        $progressBar->start('Started caching symbolism for '.$version.' version');
        foreach ($verses as $verse) {
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
            $progressBar->update();
        }
        $progressBar->finish();
    }

    public static function createLexiconStructure($lexiconCode)
    {
        ini_set('memory_limit', '1024M');
        $lexiconModel = BaseModel::getLexiconModelByVersionCode($lexiconCode);
        $lexiconBaseCount = LexiconBase::count();
        $partCount = 500;
        $offset = 0;
        $parts = ceil($lexiconBaseCount/$partCount);

        $progressBar = new ProgressBarHelper($lexiconBaseCount,10);
        $progressBar->start('Creating BASE lexicon structure');
        for($i = 1;$i<=$parts;$i++){
            $lexiconBase = LexiconBase::skip($offset)->take($partCount)->orderBy('id')->get();
            $data = [];
            foreach ($lexiconBase as $key => $lexiconItem) {
                $data[$key]['book_id'] = $lexiconItem->book_id;
                $data[$key]['chapter_num'] = $lexiconItem->chapter_num;
                $data[$key]['verse_num'] = $lexiconItem->verse_num;
                $data[$key]['strong_num'] = $lexiconItem->strong_num;
                $data[$key]['transliteration'] = $lexiconItem->transliteration;
                $data[$key]['verse_part_he'] = $lexiconItem->verse_part_he;
                $data[$key]['verse_part_el'] = $lexiconItem->verse_part_el;
                $progressBar->update();
            }
            $lexiconModel::insert($data);
            $offset += $partCount;
        }

        $progressBar->finish();
    }
}