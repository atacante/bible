<?php

namespace App;

class SymbolismEncyclopedia extends BaseModel
{
    protected $table = 'symbolism_encyclopedia';
    protected $fillable = ['id','term_name','term_description','associate_lexicons'];

    public function rules()
    {
        return  [
            'term_name' => 'required',
            'term_description' => 'required',
        ];
    }

    protected static function boot()
    {
        parent::boot();
        self::saved(function ($model) {
            ini_set("max_execution_time", "0");
            if ($model->isDirty('term_name') && !$model->isDirty('associate_lexicons') && $model->associate_lexicons) {
                $model->associateLexicons('detach');
                $model->associateLexicons('attach');
            }
            if (!$model->isDirty('term_name') && $model->isDirty('term_description') && !$model->isDirty('associate_lexicons') && $model->associate_lexicons) {
                $model->associateLexicons('attach');
            }
            if ($model->wasRecentlyCreated || $model->isDirty('associate_lexicons')) {
                if ($model->associate_lexicons) {
                    $model->associateLexicons('attach');
                } else {
                    $model->associateLexicons('detach');
                }
            }
        });
        self::deleted(function ($model) {
            $model->associateLexicons('detach');
        });
    }

    public function associateLexicons($action = 'attach')
    {
        $termName = $this->term_name;
        if ($action == 'detach') {
            $termName = $this->getOriginal('term_name');
        }

        $nasbPhrases = LexiconNasb::where(function ($sq) use ($termName) {
            $sq->orWhere('verse_part', 'ilike', '% '.$termName.' %');
            $sq->orWhere('verse_part', 'ilike', $termName.' %');
            $sq->orWhere('verse_part', 'ilike', '% '.$termName);
            $sq->orWhere('verse_part', 'ilike', $termName);
            $sq->orWhere('verse_part', 'ilike', $termName.',%');
            $sq->orWhere('verse_part', 'ilike', $termName.'.%');
            $sq->orWhere('verse_part', 'ilike', '% '.$termName.',%');
            $sq->orWhere('verse_part', 'ilike', '% '.$termName.'.%');
        });
        $this->fillSymbolism($action, $nasbPhrases);

        $kjvPhrases = LexiconKjv::where(function ($sq) use ($termName) {
            $sq->orWhere('verse_part', 'ilike', '% '.$termName.' %');
            $sq->orWhere('verse_part', 'ilike', $termName.' %');
            $sq->orWhere('verse_part', 'ilike', '% '.$termName);
            $sq->orWhere('verse_part', 'ilike', $termName);
            $sq->orWhere('verse_part', 'ilike', $termName.',%');
            $sq->orWhere('verse_part', 'ilike', $termName.'.%');
            $sq->orWhere('verse_part', 'ilike', '% '.$termName.',%');
            $sq->orWhere('verse_part', 'ilike', '% '.$termName.'.%');
        });
        $this->fillSymbolism($action, $kjvPhrases);

        $bereanPhrases = LexiconBerean::where(function ($sq) use ($termName) {
            $sq->orWhere('verse_part', 'ilike', '% '.$termName.' %');
            $sq->orWhere('verse_part', 'ilike', $termName.' %');
            $sq->orWhere('verse_part', 'ilike', '% '.$termName);
            $sq->orWhere('verse_part', 'ilike', $termName);
            $sq->orWhere('verse_part', 'ilike', $termName.',%');
            $sq->orWhere('verse_part', 'ilike', $termName.'.%');
            $sq->orWhere('verse_part', 'ilike', '% '.$termName.',%');
            $sq->orWhere('verse_part', 'ilike', '% '.$termName.'.%');
        });
        $this->fillSymbolism($action, $bereanPhrases);

        /* ToDo: We can try to optimize associating if needed (initial code below ) */
        /*switch($action){
            case 'attach':
                $nasbPhrases = LexiconNasb::where('verse_part','ilike','%'.$termName.'%');
                $nasbVerses = VersesNasbEn::whereIn('id',$nasbPhrases->lists('verse_id')->toArray());
                $nasbPhrases->update(['symbolism' => $this->term_description]);
                $bereanPhrases = LexiconBerean::where('verse_part','ilike','%'.$termName.'%')->update(['symbolism' => $this->term_description]);
                $kjvPhrases = LexiconKjv::where('verse_part','ilike','%'.$termName.'%')->update(['symbolism' => $this->term_description]);
                break;
            case 'detach':
                $nasbPhrases = LexiconNasb::where('verse_part','ilike','%'.$termName.'%')->update(['symbolism' => ""]);
                $bereanPhrases = LexiconBerean::where('verse_part','ilike','%'.$termName.'%')->update(['symbolism' => ""]);
                $kjvPhrases = LexiconKjv::where('verse_part','ilike','%'.$termName.'%')->update(['symbolism' => ""]);
                break;
        }
        ModelHelper::cacheSymbolismForBeginnerMode($nasbVerses,'nasb');
        ModelHelper::cacheSymbolismForBeginnerMode($kjvVerses,'kjv');
        ModelHelper::cacheSymbolismForBeginnerMode($bereanVerses,'berean');*/
    }

    private function fillSymbolism($action, $phrases)
    {
        switch ($action) {
            case 'attach':
                $phrases->each(function ($item) {
                    $item->update(['symbolism' => $this->term_description]);
                });
                break;
            case 'detach':
                $phrases->each(function ($item) {
                    $item->update(['symbolism' => '']);
                });
                break;
        }
    }
}
