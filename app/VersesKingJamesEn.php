<?php namespace App;

class VersesKingJamesEn extends BaseModel {

    /**
     * Generated
     */
    public $timestamps = false;
    protected $table = 'verses_king_james_en';
    protected $fillable = ['id', 'book_id', 'chapter_num', 'verse_num', 'verse_text','verse_text_with_lexicon'];


    public function booksListEn() {
        return $this->belongsTo(BooksListEn::class, 'book_id', 'id');
    }

    public function locations() {
        return $this->belongsToMany(Location::class, 'location_verse', 'verse_id', 'location_id');
    }

    public function lexicon() {
        return LexiconKjv::query()
            ->where('book_id',$this->book_id)
            ->where('chapter_num',$this->chapter_num)
            ->where('verse_num',$this->verse_num)
            ->orderBy('id')
            ->get();
    }

    public function symbolism() {
        return LexiconKjv::query()
            ->where('book_id',$this->book_id)
            ->where('chapter_num',$this->chapter_num)
            ->where('verse_num',$this->verse_num)
            ->whereNotNull('symbolism')
            ->orderBy('id')
            ->get();
    }

//    public function lexicon() {
//        return $this->hasMany(LexiconKjv::class, 'book_id', 'book_id')
//            ->where('chapter_num',$this->chapter_num)
//            ->where('verse_num',$this->verse_num);
//    }

    public static function cacheLexicon($verses = []){
        ini_set('memory_limit', '512M');
        if(!count($verses)){
            $verses = self::query()->get();
        }
        $versesCount = count($verses);
        $progressBarPersentStep = 1;
        $progressBarPart = round($versesCount/(100/$progressBarPersentStep));
        $progressBarStatus = 0;
        $i = 0;
        foreach ($verses as $verse) {
            $i++;
            $verse->verse_text_with_lexicon = $verse->verse_text;
            $lexicon = $verse->lexicon();

            if ($lexicon) {
                $parts = [];
                foreach($lexicon as $vesrePart){
                    if(!empty($vesrePart->symbolism)){
                        $parts[$vesrePart->id] = $vesrePart->verse_part;
                    }
                }

                $parts = array_unique($parts);
                uasort($parts,function($a,$b){
                    return strlen($b)-strlen($a);
                });

                foreach ($parts as $key => $part) {
                    $part = str_replace('[','<i>',$part);
                    $part = str_replace(']','</i>',$part);
                    $verse->verse_text_with_lexicon = str_replace($part,"<-$key->".$part."<|>",$verse->verse_text_with_lexicon);
                }

                $verse->verse_text_with_lexicon = str_replace("<-","<span class='word-definition' data-lexid=\"",$verse->verse_text_with_lexicon);
                $verse->verse_text_with_lexicon = str_replace("->",'">',$verse->verse_text_with_lexicon);
                $verse->verse_text_with_lexicon = str_replace("<|>","</span>",$verse->verse_text_with_lexicon);
                $verse->save();
            }
            if($i == $progressBarPart){
                echo "Progress ".($progressBarStatus+$progressBarPersentStep)."%\n";
                $progressBarStatus++;
                $i = 0;
            }
        }
    }

    public static function cacheSymbolismForBeginnerMode($verses = []){
        ini_set('memory_limit', '512M');
        if(!count($verses)){
            $verses = self::query()->get();
        }
        $versesCount = count($verses);
        $progressBarPersentStep = 1;
        $progressBarPart = round($versesCount/(100/$progressBarPersentStep));
        $progressBarStatus = 0;
        $i = 0;
        foreach ($verses as $verse) {
            $i++;
            $verse->verse_text_with_symbolism = $verse->verse_text;
            $lexicon = $verse->symbolism();
            if ($lexicon) {
                $parts = [];
                foreach($lexicon as $vesrePart){
                    $parts[$vesrePart->id] = $vesrePart->verse_part;
                }

                $parts = array_unique($parts);
                uasort($parts,function($a,$b){
                    return strlen($b)-strlen($a);
                });

                foreach ($parts as $key => $part) {
                    $part = str_replace('[','<i>',$part);
                    $part = str_replace(']','</i>',$part);
                    $verse->verse_text_with_symbolism = str_replace($part,"<-$key->".$part."<|>",$verse->verse_text_with_symbolism);
                }

                $verse->verse_text_with_symbolism = str_replace("<-","<span class='word-definition' data-lexid=\"",$verse->verse_text_with_symbolism);
                $verse->verse_text_with_symbolism = str_replace("->",'">',$verse->verse_text_with_symbolism);
                $verse->verse_text_with_symbolism = str_replace("<|>","</span>",$verse->verse_text_with_symbolism);
                $verse->save();
            }
            if($i == $progressBarPart){
                echo "Progress ".($progressBarStatus+$progressBarPersentStep)."%\n";
                $progressBarStatus++;
                $i = 0;
            }
        }
    }
}
