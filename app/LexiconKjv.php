<?php
namespace App;

use App\Helpers\ModelHelper;

class LexiconKjv extends BaseModel
{

    /**
     * Generated
     */

    public static $FIRE_EVENTS = true;

    public $timestamps  = false;

    protected $table = 'lexicon_kjv';
    protected $fillable = ['id', 'book_id','chapter_num','verse_num','verse_part','strong_num','strong_1_word_def','transliteration','symbolism','definition','verse_part_el','verse_part_he','symbolism_updated_at'];

    protected $dates = ['symbolism_updated_at'];

    public function booksListEn()
    {
        return $this->belongsTo(BooksListEn::class, 'book_id', 'id');
    }

    public function cacheVerse($ids = [])
    {
        $verses[] = VersesKingJamesEn::query()
            ->where('book_id', $this->book_id)
            ->where('chapter_num', $this->chapter_num)
            ->where('verse_num', $this->verse_num)
            ->first();
//        VersesKingJamesEn::cacheLexicon($verses);
        ModelHelper::cacheLexicon($verses, 'kjv', $ids);
    }

    public function cacheSymbolismForBeginnerMode()
    {
        $verses[] = VersesKingJamesEn::query()
            ->where('book_id', $this->book_id)
            ->where('chapter_num', $this->chapter_num)
            ->where('verse_num', $this->verse_num)
            ->first();
//        VersesKingJamesEn::cacheSymbolismForBeginnerMode($verses);
        ModelHelper::cacheSymbolismForBeginnerMode($verses, 'kjv');
    }

    public function locations()
    {
        return $this->belongsToMany(Location::class, 'location_lexicon', 'lexicon_id', 'location_id');
    }

    public function peoples()
    {
        return $this->belongsToMany(People::class, 'people_lexicon', 'lexicon_id', 'people_id');
    }

    public function views()
    {
        return $this->morphToMany('App\User', 'item', 'users_views')->withTimestamps();
    }
}
