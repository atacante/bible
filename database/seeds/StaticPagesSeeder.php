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
        $pageAbout = CmsPage::where('system_name','about')->first();
        if(!$pageAbout){
            $pageAbout = [
                'content_type' => CmsPage::CONTENT_PAGE,
                'title' => 'About Us',
                'system_name' => 'about',
                'text' =>
                    '<h2>The Bible Study Company</h2>

                    <p>Bible Study Company, LLC. (BSC) is a web application designed to provide a simple and easy to use&nbsp;experience in studying the bible. But why should you use BSC? Your first question may be that there are&nbsp;many e-readers on the web and why is BSC any different? We aren&rsquo;t just an e-reader. We hope to become your online study bible, complete with notes, journals, encyclopedia, prayers and social media&nbsp;component.</p>

                    <p>In our name: Bible Study Company, the emphasis is on&hellip; company. We are a company &ldquo;doing&rdquo; business&nbsp;and we are a &ldquo;company&rdquo; of believers in God who are committed to pursuing the God of the Bible in this&nbsp;way: To find out how He wants us to live a praiseworthy life.&nbsp;</p>

                    <p>In Matthew 28:19 the Lord Jesus told us to go make disciples of all nations. What is a disciple? Someone&nbsp;who mimics a particular teacher and does what this teacher teaches. In John chapter 1 we see that the&nbsp;Lord Jesus is the living word of God. This living word is recorded in the bible. Thus, we can conclude we&nbsp;are to be disciples of his Word which is the bible.</p>',
                'meta_title' => 'About Us',
                'meta_keywords' => 'About Us',
                'meta_description' => 'About Us',
            ];
            CmsPage::insert($pageAbout);
        }

        $contactMain = CmsPage::where('system_name','contact_main')->first();
        if(!$contactMain){
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
        }

        $contactAside = CmsPage::where('system_name','contact_aside')->first();
        if(!$contactAside){
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
        }

        $howItWorks = CmsPage::where('system_name','how_it_works')->first();
        if(!$howItWorks){
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
        }

        $BSCEvents = CmsPage::where('system_name','bsc_events')->first();
        if(!$BSCEvents){
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
        }

        $seminars = CmsPage::where('system_name','seminars')->first();
        if(!$seminars){
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
        }

        $membership = CmsPage::where('system_name','membership')->first();
        if(!$membership){
            $membership = [
                'content_type' => CmsPage::CONTENT_PAGE,
                'title' => 'Membership Levels',
                'system_name' => 'membership',
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
                'meta_title' => 'Membership Levels',
                'meta_keywords' => 'Membership Levels',
                'meta_description' => 'Membership Levels',
            ];
            CmsPage::insert($membership);
        }

        $recommendedResources = CmsPage::where('system_name','recommended_resources')->first();
        if(!$recommendedResources){
            $recommendedResources = [
                'content_type' => CmsPage::CONTENT_PAGE,
                'title' => 'Recommended Resources',
                'system_name' => 'recommended_resources',
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
                'meta_title' => 'Recommended Resources',
                'meta_keywords' => 'Recommended Resources',
                'meta_description' => 'Recommended Resources',
            ];
            CmsPage::insert($recommendedResources);
        }

        $partners = CmsPage::where('system_name','partners')->first();
        if(!$partners){
            $partners = [
                'content_type' => CmsPage::CONTENT_PAGE,
                'title' => 'Partners',
                'system_name' => 'partners',
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
                'meta_title' => 'Partners',
                'meta_keywords' => 'Partners',
                'meta_description' => 'Partners',
            ];
            CmsPage::insert($partners);
        }

        $diffTooltip = CmsPage::where('system_name','diff_explain')->first();
        if(!$diffTooltip){
            $diffTooltip = [
                'content_type' => CmsPage::CONTENT_TOOLTIP,
                'title' => 'Diff tooltip',
                'system_name' => 'diff_explain',
                'text' => 'Diff tooltip',
            ];
            CmsPage::insert($diffTooltip);
        }

        $beginnerTooltip = CmsPage::where('system_name','beginner_mode')->first();
        if(!$beginnerTooltip){
            $beginnerTooltip = [
                'content_type' => CmsPage::CONTENT_TOOLTIP,
                'title' => 'Beginner mode',
                'system_name' => 'beginner_mode',
                'text' => 'Beginner tooltip',
            ];
            CmsPage::insert($beginnerTooltip);
        }

        $intermediateTooltip = CmsPage::where('system_name','intermediate_mode')->first();
        if(!$intermediateTooltip){
            $intermediateTooltip = [
                'content_type' => CmsPage::CONTENT_TOOLTIP,
                'title' => 'Intermediate mode',
                'system_name' => 'intermediate_mode',
                'text' => 'Intermediate tooltip',
            ];
            CmsPage::insert($intermediateTooltip);
        }

        $betaModeTooltip = CmsPage::where('system_name','beta_mode')->first();
        if(!$betaModeTooltip){
            $betaModeTooltip = [
                'content_type' => CmsPage::CONTENT_TOOLTIP,
                'title' => 'Beta Mode',
                'system_name' => 'beta_mode',
                'text' => 'Beta Mode',
            ];
            CmsPage::insert($betaModeTooltip);
        }
    }
}
