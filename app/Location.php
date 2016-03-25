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
    protected $fillable = ['id','location_name','location_description'];

    public function rules()
    {
        return  [
            'location_name' => 'required',
            'location_description' => 'required',
        ];
    }

    public function booksListEn() {
        return $this->belongsTo(BooksListEn::class, 'book_id', 'id');
    }

    public function images() {
        return $this->hasMany(LocationImages::class, 'location_id', 'id');
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
}
