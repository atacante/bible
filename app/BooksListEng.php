<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class BooksListEng extends Model {

    /**
     * Generated
     */

    public $timestamps  = false;

    protected $table = 'books_list_eng';
    protected $fillable = ['id', 'book_name'];


    public function versesAmericanStandardEngs() {
        return $this->hasMany(\App\VersesAmericanStandardEng::class, 'book_id', 'id');
    }


}
