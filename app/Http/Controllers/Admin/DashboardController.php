<?php

namespace App\Http\Controllers\Admin;

use App\LexiconsListEn;
use App\VersionsListEn;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index(){
        $content['lexiconsCount'] = count(LexiconsListEn::lexiconsList());
        $content['bibleVersionsCount'] = count(VersionsListEn::versionsList());
        return view('admin.dashboard.main',
            [
                'page_title' => 'Dashboard',
                'content' => $content
            ]);
    }
}
