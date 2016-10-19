<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class Highlight extends Model
{
    const COLOR_GREEN = 'j-green';
    const COLOR_YELLOW = 'j-yellow';

    public $timestamps = true;

    public $version;

    protected $table = 'highlights';
    protected $fillable = ['id','user_id','bible_version','verse_id','highlighted_text','color'];

    protected $dates = ['created_at', 'updated_at'];

    public function verseFrom()
    {
        if(!$this->version){
            $this->version = Config::get('app.defaultBibleVersion');
        }
        $versesModel = BaseModel::getVersesModelByVersionCode($this->version);
        return $this->hasOne($versesModel, 'id', 'verse_from_id');
    }

    public function verseTo()
    {
        if(!$this->version){
            $this->version = Config::get('app.defaultBibleVersion');
        }
        $versesModel = BaseModel::getVersesModelByVersionCode($this->version);
        return $this->hasOne($versesModel, 'id', 'verse_to_id');
    }
}
