<?php namespace App;

class LexiconsListEn extends BaseModel
{

    public static function lexiconsList()
    {
        return [
            [
                'lexicon_name' => 'King James Lexicon',
                'lexicon_code' => 'kjv',
                'bible_version' => 'king_james'
            ],
            [
                'lexicon_name' => 'Berean Lexicon',
                'lexicon_code' => 'berean',
                'bible_version' => 'berean'
            ],
        ];
    }

    public static function getLexiconByCode($code)
    {
        foreach (self::lexiconsList() as $version) {
            if($version['lexicon_code'] == $code){
                return $version['lexicon_name'];
            }
        }
        return false;
    }

    public static function getLexiconCodeByBibleVersion($bVersion)
    {
        foreach (self::lexiconsList() as $version) {
            if($version['bible_version'] == $bVersion){
                return $version['lexicon_code'];
            }
        }
        return false;
    }
}
