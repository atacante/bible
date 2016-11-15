<?php

namespace App\Console\Commands;

use App\BaseModel;
use App\Helpers\ModelHelper;
use App\LexiconBerean;
use App\LexiconKjv;
use App\LexiconNasb;
use App\LexiconsListEn;
use App\VersesKingJamesEn;
use Illuminate\Console\Command;

class RecacheSomeLexiconItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lexicon:recache-some-lexicon-items';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recache lexicon items according to defined condition';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        ini_set('memory_limit', '768M');
        if ($this->confirm('Cache process may reduce server performance during execution and may take a lot of time. Do you wish to continue? [yes|no]')) {
            $nasbPhrases = LexiconNasb::where(function ($sq) {
                $sq->orWhere('verse_part', 'like', '%[%');
                $sq->orWhere('verse_part', 'like', '%]%');
            })->get();
            if ($nasbPhrases->count() > 0) {
                foreach ($nasbPhrases as $nasbPhrase) {
                    $nasbPhrase->cacheVerse();
                }
            }

            $kjvPhrases = LexiconKjv::where(function ($sq) {
                $sq->orWhere('verse_part', 'like', '%[%');
                $sq->orWhere('verse_part', 'like', '%]%');
            })->get();
            if ($kjvPhrases->count() > 0) {
                foreach ($kjvPhrases as $kjvPhrase) {
                    $kjvPhrase->cacheVerse();
                }
            }

            $bereanPhrases = LexiconBerean::where(function ($sq) {
                $sq->orWhere('verse_part', 'like', '%[%');
                $sq->orWhere('verse_part', 'like', '%]%');
            })->get();
            if ($bereanPhrases->count() > 0) {
                foreach ($bereanPhrases as $bereanPhrase) {
                    $bereanPhrase->cacheVerse();
                }
            }

            $this->info('Caching has been completed successfully!');
        } else {
            $this->error('Caching has been canceled!');
        }
    }
}
