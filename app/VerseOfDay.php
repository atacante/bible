<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class VerseOfDay extends Model
{
    protected $table = 'verse_of_day';

    public $timestamps = false;

    protected $fillable =  ['verse_id', 'show_at','image','image_mobile'];

    public function verse()
    {
        return $this->belongsTo(\App\VersesNasbEn::class, 'verse_id', 'id');
    }

    public static function getTodayVerse()
    {
        $verse = self::where('show_at', Carbon::today())->orderBy('id', 'DESC')->first();
        if (!$verse) {
            $todayVerse = self::getRandomVerse();
            $verse = self::createById($todayVerse->id);
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

        $data = ['verse_id' => $verseId,'show_at' => $showAt];

        $prevVerseWithImages = self::whereNotNull('image')->orderBy('id', 'DESC')->first();
        if ($prevVerseWithImages) {
            $data['image'] = $prevVerseWithImages->image;
            $data['image_mobile'] = $prevVerseWithImages->image_mobile;
        }

        $verse = self::create($data);

        return $verse;
    }
}
