<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class People extends BaseModel
{
    public $timestamps  = true;

    protected $table = 'peoples';
    protected $fillable = ['id','people_name','people_description','associate_verses'];

    public function rules()
    {
        return  [
            'people_name' => 'required',
            'people_description' => 'required',
        ];
    }

    public function images() {
        return $this->hasMany(PeopleImages::class, 'people_id', 'id');
    }

    public function verses() {
        return $this->belongsToMany(VersesKingJamesEn::class, 'people_verse', 'people_id', 'verse_id');
    }

    public function associateVerses($action = 'attach'){
        $characterName = $this->people_name;
        if($action == 'detach'){
            $characterName = $this->getOriginal('people_name');
        }
        $verses = BaseModel::searchEverywhere(pg_escape_string($characterName));
        if($verses->get()->count()){
            switch($action){
                case 'attach':
                    $this->verses()->attach($verses->lists('id')->toArray());
                    break;
                case 'detach':
                    $this->verses()->detach($verses->lists('id')->toArray());
                    break;
            }
        }
    }
}
