<?php

use App\BooksListEn;
use App\Helpers\ProgressBarHelper;
use App\LexiconBase;
use App\LexiconBerean;
use App\LexiconKjv;
use App\LexiconNasb;
use App\People;
use App\VersesAmericanStandardEn;
use App\VersionsListEn;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yangqi\Htmldom\Htmldom;

class PeopleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ini_set('memory_limit', '768M');
        $excelData = Excel::load(base_path('resources/data/strongs_people_places.xlsx'), function ($reader) {
        })->get();
        if (count($excelData)) {
            $progressBar = new ProgressBarHelper((count($excelData)), 10);
            $progressBar->start('Started seeding data for people');

            DB::statement("TRUNCATE TABLE peoples CASCADE");
            DB::statement("TRUNCATE TABLE people_verse");
            DB::statement("ALTER SEQUENCE peoples_id_seq RESTART WITH 1");
            DB::statement("ALTER SEQUENCE people_images_id_seq RESTART WITH 1");

            foreach ($excelData as $key => $row) {
                if ($row['person_place'] == 'Person' && !empty($row['word'])) {
                    $data['associate_verses'] = true;
                    $data['people_name'] = $row['word'];
                    $data['people_description'] = !empty($row['short_tag_definition'])?$row['short_tag_definition']:' ';
                    People::create($data);
                }
                $progressBar->update();
            }
            $progressBar->finish('');
        }
    }
}
