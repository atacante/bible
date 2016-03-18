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

// Admin area
/*
| Option for route {domain}.{tld}/admin
*/
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

/*
| Option for route admin.{domain}.{tld}
*/
//Route::group([
//    'domain' => 'admin.{domain}.{tld}',
//    'prefix' => 'admin',
//    'namespace' => 'Admin',
//    'middleware' => ['auth', 'acl'],
//    'is' => 'administrator',
//], function() {
//    Route::get('/', [
//        'middleware' => ['auth', 'acl'],
//        'is' => 'administrator',
//        'as' => 'dashboard',
//        'uses' => 'DashboardController@index'
//    ]);
//    Route::controllers([
//        'lexicon' => 'LexiconController',
//        'bible' => 'BibleController',
//        'user' => 'UserController',
////        'auth' => 'AdminAuth\AuthController',
//    ]);
//});

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


/*
| User routes
*/
Route::group([
    'middleware' => ['auth', 'acl'],
    'is' => 'user'
    ],
    function () {
        Route::controllers([
            'user' => 'UserController',
        ]);
    });



View::composer('reader.filters', 'App\Http\Composers\BibleFiltersComposer');
View::composer('admin.partials.filters', 'App\Http\Composers\BibleFiltersComposer');
