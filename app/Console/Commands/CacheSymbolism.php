<?php

namespace App\Console\Commands;

use App\BaseModel;
use App\Helpers\ModelHelper;
use App\Helpers\ViewHelper;
use App\LexiconsListEn;
use App\VersesKingJamesEn;
use Illuminate\Console\Command;

class CacheSymbolism extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'symbolism:cache {--ver=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process Bible text to add relations with symbolism';

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
        if(!$version = $this->option('ver')){
            $version = 'king_james';
        }
        if ($this->confirm('Cache process may reduce server performance during execution and may take a lot of time. Do you wish to continue? [yes|no]'))
        {
            $this->info('Bible Version - '.$version);
            $versesModel = BaseModel::getVersesModelByVersionCode($version);
            $this->info('Caching in process...');
//            $versesModel::cacheSymbolismForBeginnerMode();
            ModelHelper::cacheSymbolismForBeginnerMode($versesModel::query()->get(),LexiconsListEn::getLexiconCodeByBibleVersion($version));
            $this->info('Caching has been completed successfully!');
        }
        else{
            $this->error('Caching has been canceled!');
        }
    }
}
