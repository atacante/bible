<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ParseCsvCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:csv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse CSV to array';

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
        /*$progress = $this->output->createProgressBar(100);

        $progress->setFormat("%current%/%max% [%bar%] %percent:3s%%");
        $progress->setProgress(0);

        for ($i = 0;$i<10;$i++) {
            $progress->advance();
        }

        $progress->finish();*/
        $this->output->progressStart(10);

        for ($i = 0; $i < 10; $i++) {
            sleep(1);
            $this->output->progressAdvance();
        }

        $this->output->progressFinish();
//        ini_set('memory_limit', '512M');
//        $csv = new \parseCSV(base_path('resources/data/bibles.csv'));
//        $data = [];
//        if(count($csv->data)){
//            foreach($csv->data as $row){
//                $book = explode(':',trim($row['Verse']));
//                $bookAndChapter = explode(' ',$book[0]);
//                $data['chapter'] = array_pop($bookAndChapter);
//                $data['book'] = implode(' ',$bookAndChapter);//array_pop() result
//                $data['verse_num'] = $book[1];
//                $data['verse'] = $row['American Standard Version'];
//            }
//        }
    }
}
