<?php

namespace App\Http\Composers;


use App\BlogCategory;
use App\Helpers\ViewHelper;
use Illuminate\Contracts\View\View;

class VerseOfDayComposer {

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $data['verse'] = BlogCategory::get()->pluck('title','id')->toArray();
        $view->with('data', $data);
    }

}