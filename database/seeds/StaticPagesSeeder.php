<?php

use App\BooksListEn;
use App\CmsPage;
use App\Tag;
use App\User;
use App\VersesAmericanStandardEn;
use App\VersionsListEn;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Kodeine\Acl\Models\Eloquent\Role;

class StaticPages extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CmsPage::where('system_name','about')->delete();
        $pageAbout = [
            'title' => 'About Us',
            'system_name' => 'about',
            'text' =>
                '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ab cum dolorem hic illum incidunt non,
                placeat sit! Dolore labore magnam repudiandae sapiente?
                Adipisci cum fuga illo ipsum optio quis voluptatum. Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                Ab cum dolorem hic illum incidunt non, placeat sit! Dolore labore magnam repudiandae sapiente? </p>
                <p>Adipisci cum fuga illo ipsum optio quis voluptatum. Lorem ipsum dolor sit amet, consectetur adipisicing
                elit. Ab cum dolorem hic illum incidunt non, placeat sit!
                Dolore labore magnam repudiandae sapiente? Adipisci cum fuga illo ipsum optio quis voluptatum.
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ab cum dolorem hic illum incidunt non,
                placeat sit! Dolore labore magnam repudiandae sapiente? Adipisci cum fuga illo ipsum optio quis voluptatum.</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ab cum dolorem hic illum incidunt non, placeat sit!
                Dolore labore magnam repudiandae sapiente? Adipisci cum fuga illo ipsum optio quis voluptatum.</p>',
            'meta_title' => 'About Us',
            'meta_keywords' => 'About Us',
            'meta_description' => 'About Us',
        ];
        CmsPage::insert($pageAbout);
    }
}
