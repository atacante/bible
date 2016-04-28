<?php namespace App;

class VersionsListEn extends BaseModel
{

    /**
     * Generated
     */

    public $timestamps = false;

    protected $table = 'versions_list_en';
    protected $fillable = ['id', 'version_code', 'version_name'];

    public static function versionsList()
    {
        /*
            !!!IMPORTANT!!!
            Changing "version_code" value requires changing corresponding DB tables names. Tables format: "verses_[version_code]_[lang]"
            "version_name" value should match with headers in bibles.csv during seeding data
        */
        return [
            [
                'version_name' => 'American Standard Version',
                'version_code' => 'american_standard',
            ],
            [
                'version_name' => 'New American Standard Version',
                'version_code' => 'nasb',
            ],
            [
                'version_name' => 'King James Bible',
                'version_code' => 'king_james',
            ],
            [
                'version_name' => 'Berean Bible',
                'version_code' => 'berean',
            ],
            [
                'version_name' => 'Douay-Rheims Bible',
                'version_code' => 'douay_rheims',
            ],
            [
                'version_name' => 'Darby Bible Translation',
                'version_code' => 'darby_bible_translation',
            ],
            [
                'version_name' => 'English Revised Version',
                'version_code' => 'english_revised',
            ],
            [
                'version_name' => 'Webster Bible Translation',
                'version_code' => 'webster_bible',
            ],
            [
                'version_name' => 'World English Bible',
                'version_code' => 'world_english',
            ],
            [
                'version_name' => 'Young\'s Literal Translation',
                'version_code' => 'youngs_literal',
            ],
            [
                'version_name' => 'American King James Version',
                'version_code' => 'american_king_james',
            ],

        ];
    }

    public static function getVersionByCode($code)
    {
        foreach (self::versionsList() as $version) {
            if($version['version_code'] == $code){
                return $version['version_name'];
            }
        }
        return false;
    }
}
