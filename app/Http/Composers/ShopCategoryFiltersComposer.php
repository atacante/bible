<?php

namespace App\Http\Composers;

use App\ShopCategory;
use Illuminate\Contracts\View\View;

class ShopCategoryFiltersComposer
{

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $filters['categories'] = ShopCategory::get()->pluck('title', 'id')->toArray();
        $view->with('filters', $filters);
    }
}
