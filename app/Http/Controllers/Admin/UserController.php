<?php

namespace App\Http\Controllers\Admin;

use App\User;
//use Illuminate\Http\Request;
use \Illuminate\Support\Facades\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public function getList()
    {
        Session::flash('backUrl', Request::fullUrl());
        $content['users'] = User::query()
            ->with('roles')
            ->orderBy('created_at','DESC')
            ->paginate(20);
        return view('admin.user.list',
            [
                'page_title' => 'Users',
                'content' => $content
            ]);
    }
}
