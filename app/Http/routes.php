<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    Route::get('/', [
        'as' => 'reader', 'uses' => 'ReaderController@getOverview'
    ]);

    Route::controller('reader', 'ReaderController');
    Route::controller('ajax', 'AjaxController');

    Route::controllers([
        'auth' => 'Auth\AuthController',
        'password' => 'Auth\PasswordController',
    ]);
});

// Admin area
//Route::get('admin', function () {
//    return redirect('/admin/lexicon/view');
//});
Route::get('admin', [
    'middleware' => ['auth', 'acl'],
    'is' => 'administrator',
    'as' => 'dashboard',
    'uses' => 'Admin\DashboardController@index'
]);

$router->group([
    'prefix' => 'admin',
    'namespace' => 'Admin',
    'middleware' => ['auth', 'acl'],
    'is' => 'administrator',
], function () {
    Route::controllers([
        'lexicon' => 'LexiconController',
        'bible' => 'BibleController',
        'user' => 'UserController',
//        'auth' => 'AdminAuth\AuthController',
    ]);
});

View::composer('reader.filters', 'App\Http\Composers\BibleFiltersComposer');
View::composer('admin.partials.filters', 'App\Http\Composers\BibleFiltersComposer');
