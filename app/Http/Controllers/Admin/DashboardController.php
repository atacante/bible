<?php

namespace App\Http\Controllers\Admin;

use App\BlogArticle;
use App\BlogCategory;
use App\Coupon;
use App\Helpers\ViewHelper;
use App\LexiconsListEn;
use App\Location;
use App\People;
use App\SymbolismEncyclopedia;
use App\VersionsListEn;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;

class DashboardController extends Controller
{
    public function index()
    {
        $content['lexiconsCount'] = count(LexiconsListEn::lexiconsList());
        $content['termsCount'] = SymbolismEncyclopedia::query()->count();
        $content['bibleVersionsCount'] = count(VersionsListEn::versionsList());
        $content['usersCount'] = User::query()
            ->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
            ->leftJoin('roles', 'roles.id', '=', 'role_user.role_id')
            ->where('roles.slug', Config::get('app.role.user'))
            ->count();
        $content['totalUsersCount'] = User::query()->count();
        $content['locationsCount'] = Location::query()->count();
        $content['peoplesCount'] = People::query()->count();
        $content['couponsCount'] = Coupon::query()->count();
        $content['articlesCount'] = BlogArticle::query()->count();
        return view(
            'admin.dashboard.main',
            [
                'page_title' => 'Dashboard',
                'content' => $content
            ]
        );
    }
}
