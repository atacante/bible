<?php namespace App;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;

class VersionsListEn extends BaseModel
{

    /**
     * Generated
     */

    public $timestamps = false;

    protected $table = 'versions_list_en';
    protected $fillable = ['id', 'version_code', 'version_name'];

    public static function versionsListAll()
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
                'enabled'      => false,
                'enabled_to_compare'      => true,
            ],
            [
                'version_name' => 'New American Standard Version',
                'version_code' => 'nasb',
                'enabled'      => true,
                'enabled_to_compare'      => true,
            ],
            [
                'version_name' => 'King James Bible',
                'version_code' => 'king_james',
                'enabled'      => true,
                'enabled_to_compare'      => true,
            ],
            [
                'version_name' => 'Berean Bible',
                'version_code' => 'berean',
                'enabled'      => true,
                'enabled_to_compare'      => true,
            ],
            [
                'version_name' => 'Douay-Rheims Bible',
                'version_code' => 'douay_rheims',
                'enabled'      => false,
                'enabled_to_compare'      => true,
            ],
            [
                'version_name' => 'Darby Bible Translation',
                'version_code' => 'darby_bible_translation',
                'enabled'      => false,
                'enabled_to_compare'      => true,
            ],
            [
                'version_name' => 'English Revised Version',
                'version_code' => 'english_revised',
                'enabled'      => false,
                'enabled_to_compare'      => true,
            ],
            [
                'version_name' => 'Webster Bible Translation',
                'version_code' => 'webster_bible',
                'enabled'      => false,
                'enabled_to_compare'      => true,
            ],
            [
                'version_name' => 'World English Bible',
                'version_code' => 'world_english',
                'enabled'      => false,
                'enabled_to_compare'      => true,
            ],
            [
                'version_name' => 'Young\'s Literal Translation',
                'version_code' => 'youngs_literal',
                'enabled'      => false,
                'enabled_to_compare'      => true,
            ],
            [
                'version_name' => 'American King James Version',
                'version_code' => 'american_king_james',
                'enabled'      => false,
                'enabled_to_compare'      => true,
            ],

        ];
    }

    public static function allVersionsList()
    {
        return VersionsListEn::query()->orderBy('id')->get()->toArray();
    }

    public static function versionsList()
    {
        /*$versions = [];
        foreach (self::versionsListAll() as $version) {
            if($version['enabled']){
                $versions[] = $version;
            }
        }
        return $versions;*/
        return VersionsListEn::query()->where('enabled',true)->get()->toArray();
    }

    public static function versionsToCompareList($basicVersion,$book)
    {
        $basicVersion = Input::get('version',Config::get('app.defaultBibleVersion'));
        $versionsToCheck = VersionsListEn::query()->where('enabled_to_compare',true)->whereNotIn('version_code',[$basicVersion]);
        /* Manual Exception for Berean (this version does not contain books before 40) */
        if($book <= 40){
            $versionsToCheck->whereNotIn('version_code',['berean']);
        }
        return $versionsToCheck->get()->toArray();

        /* Check each version on book existing. Automatic exceptions */
//        $versionsToCheck = VersionsListEn::query()->where('enabled_to_compare',true)->whereNotIn('version_code',[$basicVersion])->get();
//        $versions = [];
//        foreach($versionsToCheck as $version){
//            $versesModel = BaseModel::getVersesModelByVersionCode($version->version_code);
//            $verses = $versesModel::where('book_id',$book)->first();
//            if($verses){
//                $versions[] = $version->version_code;
//            }
//        }
//        return VersionsListEn::query()->whereIn('version_code',$versions)->get()->toArray();
    }

    public static function getVersionByCode($code)
    {
        foreach (self::versionsListAll() as $version) {
            if($version['version_code'] == $code){
                return $version['version_name'];
            }
        }
        return false;
    }
}
