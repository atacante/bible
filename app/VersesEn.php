<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VersesEn extends BaseModel
{
    public $timestamps  = false;

    protected $table = 'verses_king_james_en';
    protected $fillable = ['id', 'book_id', 'chapter_num', 'verse_num', 'verse_text'];

    public function __construct($value = null, array $attributes = array())
    {
        parent::__construct($attributes);
        if ($value) {
            $this->table = $value;
        }
    }

    public function booksListEn()
    {
        return $this->belongsTo(\App\BooksListEn::class, 'book_id', 'id');
    }

    public function locations()
    {
        return $this->belongsToMany(Location::class, 'location_verse', 'verse_id', 'location_id');
    }

    public function peoples()
    {
        return $this->belongsToMany(People::class, 'people_verse', 'verse_id', 'people_id');
    }

    public function lexicon()
    {
        return LexiconBerean::query()
            ->where('book_id', $this->book_id)
            ->where('chapter_num', $this->chapter_num)
            ->where('verse_num', $this->verse_num)
            ->orderBy('id')
            ->get();
    }

    public function symbolism()
    {
        return LexiconBerean::query()
            ->where('book_id', $this->book_id)
            ->where('chapter_num', $this->chapter_num)
            ->where('verse_num', $this->verse_num)
            ->whereNotNull('symbolism')
            ->orderBy('id')
            ->get();
    }
}
