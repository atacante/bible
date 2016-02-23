<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
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
}
