<?php

use Illuminate\Database\Seeder;
use App\CmsPage;

class HomePageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $homeMainBlock = CmsPage::getPage('home_main_block');
        if(!$homeMainBlock){
            $homeMainBlock = [
                'content_type' => CmsPage::CONTENT_HOME,
                'title' => 'Home Main Block',
                'system_name' => 'home_main_block',
                'text' =>'ONLINE <span>STUDY BIBLE</span> COMMUNITY',
                'description' => 'Studying Scripture to Live a Praiseworthy <span class="brr">Life to God</span>',
                'background' => 'bg-home-header.jpg'
            ];
            CmsPage::insert($homeMainBlock);
        }

        $homeReaderBlock = CmsPage::getPage('home_reader_block');
        if(!$homeReaderBlock){
            $homeReaderBlock = [
                'content_type' => CmsPage::CONTENT_HOME,
                'title' => 'Home Reader Block',
                'system_name' => 'home_reader_block',
                'text' =>'STUDY THE <span>BIBLE</span> <br>WITH PURPOSE',
                'description' => 'Learn and compare between different<br> versions of bible.',
                'background' => 'p1-home.png'
            ];
            CmsPage::insert($homeReaderBlock);
        }

        $homeJourneyBlock = CmsPage::getPage('home_journey_block');
        if(!$homeJourneyBlock){
            $homeJourneyBlock = [
                'content_type' => CmsPage::CONTENT_HOME,
                'title' => 'Home Journey Block',
                'system_name' => 'home_journey_block',
                'text' =>'<span>Create</span> your own journey',
                'description' => 'Make a notes and write a journal. Share your favourites with<br>
                                friends. Do something more. Do one more thing.',
                'background' => 'p2-home.png'
            ];
            CmsPage::insert($homeJourneyBlock);
        }

        $homeCommunityBlock = CmsPage::getPage('home_community_block');
        if(!$homeCommunityBlock){
            $homeCommunityBlock = [
                'content_type' => CmsPage::CONTENT_HOME,
                'title' => 'Home Community Block',
                'system_name' => 'home_community_block',
                'text' =>'get involved in<br>
                                <span>OUR community</span>',
                'description' => 'Share your thoughts, prayers with others.<br>
                                Make a new friends.<br>
                                Probably more impressive description.',
                'background' => 'p3-home.jpg'
            ];
            CmsPage::insert($homeCommunityBlock);
        }

        $homeExploreBlock = CmsPage::getPage('home_explore_block');
        if(!$homeExploreBlock){
            $homeExploreBlock = [
                'content_type' => CmsPage::CONTENT_HOME,
                'title' => 'Home Explore Block',
                'system_name' => 'home_explore_block',
                'text' =>'<span>EXPLORE</span> the SYMBOLISM',
                'description' => 'Learn more about historic locations & people.<br>
                                Probably more desription here.',
                'background' => 'p4-home.jpg'
            ];
            CmsPage::insert($homeExploreBlock);
        }
    }
}
