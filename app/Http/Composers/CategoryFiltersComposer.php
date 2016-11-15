<?php

namespace App\Http\Composers;

use App\BlogCategory;
use App\Helpers\ViewHelper;
use Illuminate\Contracts\View\View;

class CategoryFiltersComposer
{

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $filters['categories'] = BlogCategory::get()->pluck('title', 'id')->toArray();
        $view->with('filters', $filters);
    }
}
