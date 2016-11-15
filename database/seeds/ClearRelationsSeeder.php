<?php

use App\BlogArticle;
use App\BooksListEn;
use App\Group;
use App\GroupUser;
use App\Tag;
use App\User;
use App\UsersViews;
use App\VersesAmericanStandardEn;
use App\VersionsListEn;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Kodeine\Acl\Models\Eloquent\Role;

class ClearRelationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $usersIds = User::whereHas('roles', function ($q) {
            $q->whereNotIn('slug', [Config::get('app.role.admin')]);
        })
            ->lists('id')
            ->toArray();
        $groupsIds = Group::lists('id')->toArray();
        $blogIds = BlogArticle::lists('id')->toArray();

        Group::whereNotIn('owner_id', $usersIds)->delete();
        GroupUser::whereNotIn('user_id', $usersIds)->delete();
        GroupUser::whereNotIn('group_id', $groupsIds)->delete();
        UsersViews::whereNotIn('user_id', $usersIds)->delete();
        UsersViews::whereNotIn('item_id', $blogIds)->where('item_type', UsersViews::CAT_BLOG)->delete();
    }
}
