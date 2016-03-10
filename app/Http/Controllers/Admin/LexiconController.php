<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class LexiconController extends Controller
{
    public function getView()
    {
        return view('admin.lexicon.view', []);
    }
}
