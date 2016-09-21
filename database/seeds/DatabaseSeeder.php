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
        $this->call(BibleVersionsListSeeder::class);
        $this->call(BibleAllVersionsSeeder::class);
        $this->call(BibleNasbSeeder::class);
        $this->call(BaseLexiconSeederNasb::class);
        $this->call(NasbLexiconMatchesOffSeeder::class);
        $this->call(KjvLexiconMatchesOnSeeder::class);
        $this->call(BibleBereanSeeder::class);
        $this->call(BereanLexiconMatchesOnSeeder::class);
        $this->call(LocationsSeeder::class);
        $this->call(StrongsConcordanceSeeder::class);
        $this->call(StrongsNasecSeeder::class);
        $this->call(TagsBasicSeeder::class);
//        $this->call(ClearRelationsSeeder::class);
    }
}
