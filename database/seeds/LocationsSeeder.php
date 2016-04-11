<?php

use App\BooksListEn;
use App\Helpers\ProgressBarHelper;
use App\LexiconKjv;
use App\Location;
use App\LocationImages;
use App\VersesAmericanStandardEn;
use App\VersionsListEn;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Symfony\Component\Console\Helper\ProgressBar;

class LocationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ini_set('memory_limit', '768M');
        $csv = new \parseCSV(base_path('resources/data/locations/atlascontent.csv'));
        if(count($csv->data)){
            $progressBar = new ProgressBarHelper(count($csv->data),10);
            $progressBar->start('Started seeding locations data');

            DB::statement("TRUNCATE TABLE locations CASCADE");
            DB::statement("ALTER SEQUENCE locations_id_seq RESTART WITH 1");
            DB::statement("ALTER SEQUENCE location_images_id_seq RESTART WITH 1");
            $locationDir = public_path(Config::get('app.locationImages'));
            File::deleteDirectory($locationDir, true);
            File::makeDirectory($locationDir.'thumbs/');
            foreach($csv->data as $key => $row){
                $location['location_name'] = $row['name_advanced'];
                $location['location_description'] = $row['description'];
                $locationModel = Location::create($location);
                /* Area Map */
                $areaImageFromPath = base_path('resources/data/locations/area_maps_400x400/'.$row['image_name']);
                $areaImageName = 'area_map_'.$row['image_name'];
                if(File::exists($areaImageFromPath)){
                    $areaMap = File::copy($areaImageFromPath,$locationDir.$areaImageName);
                    Image::make($locationDir.$areaImageName)->fit(200, 200)->save($locationDir.'thumbs/'.$areaImageName)->destroy();
                    if($areaMap){
                        $areaImage['location_id'] = $locationModel->id;
                        $areaImage['image'] = $areaImageName;
                        LocationImages::create($areaImage);
                    }
                }
                /* Regional Map */
                $regionalImageFromPath = base_path('resources/data/locations/regional_maps_1000x1000/'.$row['image_name']);
                $regionalImageName = 'regional_map_'.$row['image_name'];
                if(File::exists($regionalImageFromPath)){
                    $regionalMap = File::copy($regionalImageFromPath,$locationDir.$regionalImageName);
                    Image::make($locationDir.$regionalImageName)->fit(200, 200)->save($locationDir.'thumbs/'.$regionalImageName)->destroy();
                    if($regionalMap){
                        $regionalImage['location_id'] = $locationModel->id;
                        $regionalImage['image'] = $regionalImageName;
                        LocationImages::create($regionalImage);
                    }
                }
                $progressBar->update();
            }
            $progressBar->finish();
        }
    }
}
