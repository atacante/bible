<?php
namespace App;

use Illuminate\Support\Facades\Request;
use Validator;

class Location extends BaseModel {

    /**
     * Generated
     */

    public $timestamps  = true;

    protected $table = 'locations';
    protected $fillable = ['id','location_name','location_description','associate_verses','g_map'];

    public function rules()
    {
        return  [
            'location_name' => 'required',
            'location_description' => 'required',
//            'g_map' => 'url',
            'g_map' => ['regex:/iframe/','regex:/embed/'],
        ];
    }

    public function booksListEn() {
        return $this->belongsTo(BooksListEn::class, 'book_id', 'id');
    }

    public function images() {
        return $this->hasMany(LocationImages::class, 'location_id', 'id');
    }

    public function verses()
    {
        return $this->belongsToMany(VersesKingJamesEn::class, 'location_verse', 'location_id','verse_id');
    }

    /* Experimental method */
    public function validate()
    {
        $data = Request::input();
        $v = Validator::make($data, [
            'location_name' => 'required',
            'location_description' => 'required',
        ],[]);

        if ($v->fails())
        {
            return redirect()->refresh()->withErrors($v->errors());
        }
        return true;
    }

    public function associateVerses($action = 'attach'){
        $locationName = $this->location_name;
        if($action == 'detach'){
            $locationName = $this->getOriginal('location_name');
        }
        $verses = BaseModel::searchEverywhere(pg_escape_string($locationName));
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
