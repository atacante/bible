<?php
namespace App;

class BooksListEn extends BaseModel
{

    /**
     * Generated
     */

    public $timestamps  = false;

    protected $table = 'books_list_en';
    protected $fillable = ['id', 'book_name'];


    public function versesAmericanStandardEns()
    {
        return $this->hasMany(\App\VersesAmericanStandardEn::class, 'book_id', 'id');
    }
}
