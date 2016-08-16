<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UsersViews extends BaseModel
{
    const CAT_READER = 'reader';
    const CAT_LEXICON = 'lexicon';
    const CAT_STRONGS = 'strongs';
    const CAT_BLOG = 'blog';

//    protected $fillable = array('user_id', 'views');

    public static function thackView($model,$category,$version = null){
        if($model->views()->where('user_id',Auth::user()->id)->get()->count()){
            $model->views()->updateExistingPivot(Auth::user()->id, ['views' => DB::raw('views+1')]);
        }
        else{
            $model->views()->attach(Auth::user()->id,['item_category' => $category,'views' => 1,'bible_version'=>$version]);
        }
    }

    public function item()
    {
        return $this->morphTo();
    }
}
