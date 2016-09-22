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

class StaticPagesSeeder extends Seeder
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
            'content_type' => CmsPage::CONTENT_PAGE,
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

        CmsPage::where('system_name','contact_main')->delete();
        $contactMain = [
            'content_type' => CmsPage::CONTENT_PAGE,
            'title' => 'Contact Us',
            'system_name' => 'contact_main',
            'text' =>
                "<p>We are here to answer any questions you may have about our experiences. Reach out to us and we'll respond as soon as we can.</p>
                <p>Even if there is something you have always wanted to experience and can't find it on BibleProject, let us know and we promise we'll do our best to find it for you and send you there.</p>",
            'meta_title' => 'Contact Us',
            'meta_keywords' => 'Contact Us',
            'meta_description' => 'Contact Us',
        ];
        CmsPage::insert($contactMain);

        CmsPage::where('system_name','contact_aside')->delete();
        $contactAside = [
            'content_type' => CmsPage::CONTENT_PAGE,
            'title' => 'Contact Aside',
            'system_name' => 'contact_aside',
            'text' =>
                '<dl>
                    <dt>EMAIL</dt>
                    <dd><a href="mailto:info@bibleproject.com" title="Click to send us an email">info@bibleproject.com</a></dd>
                    <dt>PHONE</dt>
                    <dd><a href="tel:00000000000" title="Click to call us">+44 20 0000 0000</a></dd>
                    <dt>SKYPE</dt>
                    <dd><a href="skype:bibleproject?call" title="Click to call us on Skype">bibleproject</a></dd>
                    <dt>ON THE WEB</dt>
                    <dd class="social-links">
                        <a class="fb" href="http://www.facebook.com/bibleproject" title="Find us on Facebook">Facebook</a>
                        <br />
                        <a class="tt" href="http://twitter.com/bibleproject" title="Find us on Twitter">Twitter</a>
                    </dd>
                </dl>',
            'meta_title' => 'Contact Us',
            'meta_keywords' => 'Contact Us',
            'meta_description' => 'Contact Us',
        ];
        CmsPage::insert($contactAside);

        CmsPage::where('system_name','how_it_works')->delete();
        $howItWorks = [
            'content_type' => CmsPage::CONTENT_PAGE,
            'title' => 'How it works',
            'system_name' => 'how_it_works',
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
            'meta_title' => 'How it works',
            'meta_keywords' => 'How it works',
            'meta_description' => 'How it works',
        ];
        CmsPage::insert($howItWorks);

        CmsPage::where('system_name','bsc_events')->delete();
        $BSCEvents = [
            'content_type' => CmsPage::CONTENT_PAGE,
            'title' => 'BSC Events',
            'system_name' => 'bsc_events',
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
            'meta_title' => 'BSC Events',
            'meta_keywords' => 'BSC Events',
            'meta_description' => 'BSC Events',
        ];
        CmsPage::insert($BSCEvents);

        CmsPage::where('system_name','seminars')->delete();
        $seminars = [
            'content_type' => CmsPage::CONTENT_PAGE,
            'title' => 'Seminars',
            'system_name' => 'seminars',
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
            'meta_title' => 'Seminars',
            'meta_keywords' => 'Seminars',
            'meta_description' => 'Seminars',
        ];
        CmsPage::insert($seminars);

        CmsPage::where('system_name','diff_explain')->delete();
        $diffTooltip = [
            'content_type' => CmsPage::CONTENT_TOOLTIP,
            'title' => 'Diff tooltip',
            'system_name' => 'diff_explain',
            'text' => 'Diff tooltip',
        ];
        CmsPage::insert($diffTooltip);

        CmsPage::where('system_name','beginner_mode')->delete();
        $beginnerTooltip = [
            'content_type' => CmsPage::CONTENT_TOOLTIP,
            'title' => 'Beginner mode',
            'system_name' => 'beginner_mode',
            'text' => 'Beginner tooltip',
        ];
        CmsPage::insert($beginnerTooltip);

        CmsPage::where('system_name','intermediate_mode')->delete();
        $intermediateTooltip = [
            'content_type' => CmsPage::CONTENT_TOOLTIP,
            'title' => 'Intermediate mode',
            'system_name' => 'intermediate_mode',
            'text' => 'Intermediate tooltip',
        ];
        CmsPage::insert($intermediateTooltip);
    }
}
