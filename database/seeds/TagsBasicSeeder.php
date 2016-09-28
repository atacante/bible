<?php

use App\BooksListEn;
use App\Tag;
use App\User;
use App\VersesAmericanStandardEn;
use App\VersionsListEn;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Kodeine\Acl\Models\Eloquent\Role;

class TagsBasicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $basicTags = [
            'fundamental' => [
                'type' => Tag::TYPE_SYSTEM,
                'tag_name' => 'fundamental',
            ],
            'review_this' => [
                'type' => Tag::TYPE_SYSTEM,
                'tag_name' => 'review this',
            ],
            'important' => [
                'type' => Tag::TYPE_SYSTEM,
                'tag_name' => 'important',
            ],
            'minor' => [
                'type' => Tag::TYPE_SYSTEM,
                'tag_name' => 'minor',
            ],
        ];
        Tag::insert($basicTags);
    }
}
