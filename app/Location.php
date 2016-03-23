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
    protected $fillable = ['id',/*'book_id','chapter_num','verse_num',*/'location_name','location_description'];

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
