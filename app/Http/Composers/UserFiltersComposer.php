<?php

namespace App\Http\Composers;

use App\BaseModel;
use App\BooksListEn;
use App\Helpers\ViewHelper;
use App\VersionsListEn;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Kodeine\Acl\Models\Eloquent\Role;

class UserFiltersComposer
{

    public function __construct()
    {
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $filters['roles'] = ViewHelper::prepareForSelectBox(Role::all()->toArray(), 'slug', 'name');
        $view->with('filters', $filters);
    }
}
