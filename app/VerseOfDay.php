<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class VerseOfDay extends Model
{
    protected $table = 'verse_of_day';

    public $timestamps = false;

    protected $fillable =  ['verse_id', 'show_at'];

    public function verse() {
        return $this->belongsTo(\App\VersesNasbEn::class, 'verse_id', 'id');
    }

    public static function getTodayVerse()
    {
        $verse = self::where('show_at',Carbon::today())->orderBy('id', 'DESC')->first();
        if(!$verse){
            $todayVerse = self::getRandomVerse();
            self::createById($todayVerse->id);
        }
        return $verse;
    }

    public static function getRandomVerse()
    {
        return VersesNasbEn::orderByRaw("RANDOM()")->limit(1)->first();
    }

    public static function createById($verseId, $tomorrow = false)
    {
        $showAt = ($tomorrow)? Carbon::tomorrow() : Carbon::today();

        $verse = self::create(['verse_id' => $verseId,'show_at' => $showAt]);

        return $verse;
    }


}
