<?php

namespace App\Http\Composers;


use App\BlogCategory;
use App\CmsPage;
use App\Helpers\ViewHelper;
use App\VerseOfDay;
use Illuminate\Contracts\View\View;

class CmsMenuComposer {

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $data['cmsItems'] = CmsPage::where('published',true)->get(['system_name','title']);
        $view->with('data', $data);
    }

}