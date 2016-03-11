<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LexiconController extends Controller
{
    public function getView()
    {
        return view('admin.lexicon.view', ['page_title' => "Lexicon"]);
    }
}
