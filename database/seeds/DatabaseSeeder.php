<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AdministratorSeeder::class);
        $this->call(BasicRolesSeeder::class);
        $this->call(BibleAllVersionsSeeder::class);
        $this->call(BaseLexiconSeeder::class);
        $this->call(LexiconSeeder::class);
        $this->call(BibleBereanSeeder::class);
        $this->call(BereanLexiconSeeder::class);
        $this->call(LocationsSeeder::class);
        $this->call(BibleNasbSeeder::class);
        $this->call(NasbLexiconSeeder::class);
        $this->call(StrongsConcordanceSeeder::class);
        $this->call(StrongsNasecSeeder::class);
    }
}
