<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class BaseModel extends Model {

    public static function getModelByTableName($name)
    {
        $modelName = __NAMESPACE__.'\\'.ucfirst(camel_case($name));
        return new $modelName();
    }

    public static function getVersesModelByVersionCode($name)
    {
        $locale = Config::get('app.locale');// temporary static variable
        $tables_prefix = 'verses_';
        $tables_suffix = '_'.$locale;
        $table_name = $tables_prefix.ucfirst(camel_case($name)).$tables_suffix;
        $modelName = __NAMESPACE__.'\\'.ucfirst(camel_case($table_name));
        return new $modelName();
    }

    public static function getVersesTableByVersionCode($name)
    {
        $locale = Config::get('app.locale');// temporary static variable
        $tables_prefix = 'verses_';
        $tables_suffix = '_'.$locale;
        $table_name = $tables_prefix.$name.$tables_suffix;
        return $table_name;
    }

    public static function getChapters($book,$version = false){
        if(!$version){ //If no params exist will be used default version to get list of chapters, because all are the same for each Bible version
            $version = Request::input('version',Config::get('app.defaultBibleVersion'));
            if($version == 'all'){
                $version = Config::get('app.defaultChaptersVersion');
            }
        }
        $versesModel = self::getVersesModelByVersionCode($version);
        return $versesModel::
        select(['chapter_num', 'book_id'])
            ->distinct()
            ->with('booksListEn')
            ->where('book_id', $book)
            ->orderBy('chapter_num')
            ->get()
            ->toArray();
    }

    public static function getVerses($book,$chapter,$version = false){
        if(!$version){ //If no params exist will be used default version to get list of verses, because all are the same for each Bible version
            $version = Request::input('version',Config::get('app.defaultBibleVersion'));
            if($version == 'all'){
                $version = Config::get('app.defaultChaptersVersion');
            }
        }
        $versesModel = self::getVersesModelByVersionCode($version);
        return $versesModel::
        select(['chapter_num', 'book_id','verse_num'])
            ->distinct()
            ->with('booksListEn')
            ->where('book_id', $book)
            ->where('chapter_num', $chapter)
            ->orderBy('verse_num')
            ->get()
            ->toArray();
    }

    private static function prepareVersesUnionQuery($version,$q){

        $keywords = explode(' ',$q);
        $q = implode(" ", array_filter($keywords));

        $phraseCond = str_replace(' ',' & ',$q);
        $wordCond = str_replace(' ',' | ',$q);

        $defaultVersesTable = self::getVersesTableByVersionCode($version);
        $versesModel = self::getVersesModelByVersionCode($version);
        /*return  DB::raw('SELECT
                        ts_rank_cd(searchtext, queryPhrase) rankPhrase,
                        ts_rank_cd(searchtext, queryWord) rankWord,
                        ts_headline(\'english\',verse_text,queryPhrase,\'HighlightAll=TRUE\') highlighted_verse_text,
                        *
                        FROM '.$defaultVersesTable.',
                        to_tsquery(\'Better & is & the & end\') queryPhrase,
                        to_tsquery(\'Better | is | the | end\') queryWord
                        where searchtext @@ queryPhrase OR searchtext @@ queryWord');*/
        return $versesModel::query()
            ->select(DB::raw('
                                    ts_rank_cd(searchtext, queryPhrase) rankPhrase,
                                    ts_rank_cd(searchtext, queryWord) rankWord,
                                    ts_headline(\'english\',verse_text,queryPhrase,\'HighlightAll=TRUE\') highlighted_verse_text,
                                    *
                                '))
            ->from(DB::raw('
                                    '.$defaultVersesTable.',
                                    to_tsquery(\''.$phraseCond.'\') queryPhrase,
                                    to_tsquery(\''.$wordCond.'\') queryWord
                                '))
            ->whereRaw(DB::raw('
                                    searchtext @@ queryPhrase/* OR searchtext @@ queryWord*/
                                '));
    }

    public static function searchEverywhere($q)
    {
        $versions = VersionsListEn::versionsList();
        $combinedUnion = self::prepareVersesUnionQuery(Config::get('app.defaultBibleVersion'),$q);
        if ($versions) {
            foreach ($versions as $version) {
                if(Config::get('app.defaultBibleVersion') != $version['version_code']){
                    $union = self::prepareVersesUnionQuery($version['version_code'],$q);
                    $combinedUnion->union($union);
                }
            }
        }

//        $finalQuery = DB::table(DB::raw('('.$combinedUnion->toSql().') bibles'))
//                    ->select(DB::raw('
//                        book_id,
//                        chapter_num,
//                        verse_num,
//                        max(highlighted_verse_text),
//                        max(rankPhrase) rankPhrase,
//                        max(rankWord) rankWord
//                        '))
//                    ->groupBy(DB::raw('book_id,chapter_num,verse_num'))
//                    ->orderByRaw(DB::raw('rankPhrase DESC,rankWord DESC'));
//
//        var_dump($finalQuery->toSql());exit;
        $versesModel = self::getVersesModelByVersionCode(Config::get('app.defaultBibleVersion'));
        $finalQuery = $versesModel::query()
            ->select(DB::raw('
                book_id,
                chapter_num,
                verse_num,
                max(highlighted_verse_text) highlighted_verse_text,
                max(rankPhrase) rankPhrase,
                max(rankWord) rankWord
                '))
            ->from(DB::raw('('.$combinedUnion->toSql().') bibles'))
//            ->from($combinedUnion->toSql())
//            ->whereRaw(DB::raw(''))
            ->groupBy(DB::raw('book_id,chapter_num,verse_num'))
            ->orderByRaw(DB::raw('rankPhrase DESC,rankWord DESC,book_id'))
        ;

        return $finalQuery;
    }
}
