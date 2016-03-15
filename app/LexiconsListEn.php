<?php namespace App;

class LexiconsListEn extends BaseModel
{

    public static function lexiconsList()
    {
        return [
            [
                'lexicon_name' => 'King James Lexicon',
                'lexicon_code' => 'kjv',
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
    }
}
