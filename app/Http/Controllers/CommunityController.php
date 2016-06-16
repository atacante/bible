<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class CommunityController extends Controller
{
    public function getIndex()
    {
        echo "asdasdasdasd";
    }

    public function getJoin()
    {
        
    }

    public function getWall()
    {
        return view('community.wall', []);
    }

    public function getGroups()
    {
        return view('community.groups', []);
    }

    public function getBlog()
    {
        return view('community.blog', []);
    }
}
