<?php

namespace App\Http\Composers;

use App\BlogCategory;
use App\Helpers\ViewHelper;
use App\VerseOfDay;
use Illuminate\Contracts\View\View;

class VerseOfDayComposer
{

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $data['verseOfDay'] = VerseOfDay::getTodayVerse();
        $view->with('data', $data);
    }
}
