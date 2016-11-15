<?php

namespace App;

use App\Helpers\ModelHelper;
use Illuminate\Database\Eloquent\Model;

class People extends BaseModel
{
    public $timestamps  = true;

    protected $table = 'peoples';
    protected $fillable = ['id','people_name','people_description','associate_verses'];

    public function rules()
    {
        return  [
            'people_name' => 'required',
            'people_description' => 'required',
        ];
    }

    public function images()
    {
        return $this->hasMany(PeopleImages::class, 'people_id', 'id');
    }

    public function verses()
    {
        return $this->belongsToMany(VersesKingJamesEn::class, 'people_verse', 'people_id', 'verse_id');
    }

    public function lexicons()
    {
        return $this->belongsToMany(LexiconNasb::class, 'people_lexicon', 'people_id', 'lexicon_id'); // we using LexiconNasb because this version is a base for all other lexicons
    }

    public function associateVerses($action = 'attach')
    {
        $characterName = $this->people_name;
        if ($action == 'detach') {
            $characterName = $this->getOriginal('people_name');
        }
        $verses = BaseModel::searchEverywhere(pg_escape_string($characterName));
        if ($verses->get()->count()) {
            switch ($action) {
                case 'attach':
                    $this->verses()->attach($verses->lists('id')->toArray());
                    break;
                case 'detach':
                    $this->verses()->detach($verses->lists('id')->toArray());
                    break;
            }
        }
    }

    public function associateLexicons($action = 'attach')
    {
        $characterName = $this->people_name;
        if ($action == 'detach') {
            $characterName = $this->getOriginal('people_name');
        }
        $nasbPhrases = LexiconNasb::where(function ($sq) use ($characterName) {
            $sq->orWhere('verse_part', 'like', '% '.$characterName.' %');
            $sq->orWhere('verse_part', 'like', $characterName.' %');
            $sq->orWhere('verse_part', 'like', '% '.$characterName);
            $sq->orWhere('verse_part', 'like', $characterName);
            $sq->orWhere('verse_part', 'like', $characterName.',%');
            $sq->orWhere('verse_part', 'like', $characterName.'.%');
            $sq->orWhere('verse_part', 'like', '% '.$characterName.',%');
            $sq->orWhere('verse_part', 'like', '% '.$characterName.'.%');
        });

        $kjvPhrases = LexiconKjv::where(function ($sq) use ($characterName) {
            $sq->orWhere('verse_part', 'like', '% '.$characterName.' %');
            $sq->orWhere('verse_part', 'like', $characterName.' %');
            $sq->orWhere('verse_part', 'like', '% '.$characterName);
            $sq->orWhere('verse_part', 'like', $characterName);
            $sq->orWhere('verse_part', 'like', $characterName.',%');
            $sq->orWhere('verse_part', 'like', $characterName.'.%');
            $sq->orWhere('verse_part', 'like', '% '.$characterName.',%');
            $sq->orWhere('verse_part', 'like', '% '.$characterName.'.%');
        });
        $bereanPhrases = LexiconBerean::where(function ($sq) use ($characterName) {
            $sq->orWhere('verse_part', 'like', '% '.$characterName.' %');
            $sq->orWhere('verse_part', 'like', $characterName.' %');
            $sq->orWhere('verse_part', 'like', '% '.$characterName);
            $sq->orWhere('verse_part', 'like', $characterName);
            $sq->orWhere('verse_part', 'like', $characterName.',%');
            $sq->orWhere('verse_part', 'like', $characterName.'.%');
            $sq->orWhere('verse_part', 'like', '% '.$characterName.',%');
            $sq->orWhere('verse_part', 'like', '% '.$characterName.'.%');
        });

        $ids = array_unique(array_merge($nasbPhrases->lists('id')->toArray(), $kjvPhrases->lists('id')->toArray(), $bereanPhrases->lists('id')->toArray()));

        if (count($ids)) {
            switch ($action) {
                case 'attach':
                    $this->lexicons()->attach($ids);
                    ModelHelper::updateSymbolism(LexiconNasb::class, $ids);
                    ModelHelper::updateSymbolism(LexiconKjv::class, $ids);
                    ModelHelper::updateSymbolism(LexiconBerean::class, $ids);
                    break;
                case 'detach':
                    $this->lexicons()->detach($ids);
                    ModelHelper::clearSymbolism(LexiconNasb::class, $ids);
                    ModelHelper::clearSymbolism(LexiconKjv::class, $ids);
                    ModelHelper::clearSymbolism(LexiconBerean::class, $ids);
                    break;
            }

//                ->update(['symbolism' => '...']);
//            ModelHelper::cacheSymbolismForBeginnerMode($versesModel::query()->get(),LexiconsListEn::getLexiconCodeByBibleVersion($version));
            /*LexiconKjv::whereIn('id',$this->lexicons()->lists('lexicon_id')->toArray())
                ->where(function($sq){
                    $sq->orwhere('symbolism','');
                    $sq->orWhere('symbolism',null);
                })
                ->get(['id']);*/
//                ->update(['symbolism' => '...']);
//            ModelHelper::cacheSymbolismForBeginnerMode($versesModel::query()->get(),LexiconsListEn::getLexiconCodeByBibleVersion($version));
            /*LexiconBerean::whereIn('id',$this->lexicons()->lists('lexicon_id')->toArray())
                ->where(function($sq){
                    $sq->orwhere('symbolism','');
                    $sq->orWhere('symbolism',null);
                })
                ->get(['id']);*/
//                ->update(['symbolism' => '...']);
//            ModelHelper::cacheSymbolismForBeginnerMode($versesModel::query()->get(),LexiconsListEn::getLexiconCodeByBibleVersion($version));
        }
    }
}
