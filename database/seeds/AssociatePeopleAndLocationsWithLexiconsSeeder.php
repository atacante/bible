<?php

use App\BooksListEn;
use App\Helpers\ProgressBarHelper;
use App\LexiconBase;
use App\LexiconBerean;
use App\LexiconKjv;
use App\LexiconNasb;
use App\Location;
use App\People;
use App\VersesAmericanStandardEn;
use App\VersionsListEn;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yangqi\Htmldom\Htmldom;

class AssociatePeopleAndLocationsWithLexiconsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ini_set('memory_limit', '768M');

        /* Clear symbolism field from relations filling. Uncomment to use. */
/*
        $this->clearSymbolism(LexiconNasb::class);
        $this->clearSymbolism(LexiconKjv::class);
        $this->clearSymbolism(LexiconBerean::class);
        exit('Symbolism cleared');
*/

        $people = People::all();
        if (count($people)) {
            $progressBar = new ProgressBarHelper((count($people)), 10);
            $progressBar->start('Started seeding data for association people with lexicons');

            foreach ($people as $key => $peopl) {
                $peopl->lexicons()->sync([]);
                $peopl->associateLexicons('attach');
                $progressBar->update();
            }

            $progressBar->finish('Done');
        }

        $locations = Location::all();
        if (count($locations)) {
            $progressBar = new ProgressBarHelper((count($locations)), 10);
            $progressBar->start('Started seeding data for association locations with lexicons');

            foreach ($locations as $key => $location) {
                $location->lexicons()->sync([]);
                $location->associateLexicons('attach');
                $progressBar->update();
            }

            $progressBar->finish('Done');
        }
    }

    public function clearSymbolism($model){
        $phrases = $model::where('symbolism','...')->get();
        $progressBar = new ProgressBarHelper($phrases->count(), 10);
        $progressBar->start('Started clearing "..." from '.$model.' lexicon');
        foreach ($phrases as $phrase) {
            $phrase->symbolism = '';
            $phrase->save();
            $progressBar->update();
        }
        $progressBar->finish('Done');
    }
}
