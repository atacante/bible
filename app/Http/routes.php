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
        'location' => 'LocationController',
        'peoples' => 'PeoplesController',
        'coupons' => 'CouponsController',
        'blog' => 'BlogController',
        'categories' => 'CategoriesController',
        'articles' => 'ArticlesController',
//        'auth' => 'AdminAuth\AuthController',
    ]);
});

/*Route::group([
        'before' => ['auth', 'acl'],
        'is' => 'administrator',
    ], function ()
{
    Route::get('/laravel-filemanager', '\Unisharp\Laravelfilemanager\controllers\LfmController@show');
    Route::post('/laravel-filemanager/upload', '\Unisharp\Laravelfilemanager\controllers\LfmController@upload');
    Route::controllers([
        'laravel-filemanager' => '\Unisharp\Laravelfilemanager\controllers\LfmController',
        'laravel-filemanager' => '\Unisharp\Laravelfilemanager\controllers\CropController',
        'laravel-filemanager' => '\Unisharp\Laravelfilemanager\controllers\DeleteController',
        'laravel-filemanager' => '\Unisharp\Laravelfilemanager\controllers\DownloadController',
        'laravel-filemanager' => '\Unisharp\Laravelfilemanager\controllers\FolderController',
        'laravel-filemanager' => '\Unisharp\Laravelfilemanager\controllers\ItemsController',
        'laravel-filemanager' => '\Unisharp\Laravelfilemanager\controllers\RenameController',
        'laravel-filemanager' => '\Unisharp\Laravelfilemanager\controllers\ResizeController',
        'laravel-filemanager' => '\Unisharp\Laravelfilemanager\controllers\UploadController',
    ]);
});*/

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

Route::get('/reader/my-study-verse', [
    'uses'          => 'ReaderController@getMyStudyVerse',
    'middleware'    => ['auth', 'acl'],
    'is'            => 'user'
]);

Route::group(['middleware' => ['web']], function () {
    Route::get('/', [
        'as' => 'reader', 'uses' => 'SiteController@getHome'
    ]);

    Route::controller('reader', 'ReaderController');
    Route::controller('ajax', 'AjaxController');
    Route::controller('locations', 'LocationsController');
    Route::controller('peoples', 'PeoplesController');
    Route::controller('site', 'SiteController');
    Route::controller('community', 'CommunityController');
    Route::controller('groups', 'GroupsController');
//    Route::controller('notes', 'NotesController', ['getComments']);

/*    Route::get('/blog/category/{id}', ['uses' => 'BlogController@getCategory']);
    Route::get('/blog/article/{id}', ['uses' => 'BlogController@getArticle']);*/
    Route::controller('blog', 'BlogController');

    Route::get('/community', [
        'as' => 'community', 'uses' => 'CommunityController@getWall'
    ]);

    Route::get('/notes/comments', array('uses' => 'NotesController@getComments'));

    Route::controllers([
        'auth' => 'Auth\AuthController',
        'password' => 'Auth\PasswordController',
    ]);
});

Route::get('notes/comments/{id}', array('middleware' => 'web', 'uses' => 'NotesController@getComments'));
Route::get('journal/comments/{id}', array('middleware' => 'web', 'uses' => 'JournalController@getComments'));
Route::get('prayers/comments/{id}', array('middleware' => 'web', 'uses' => 'PrayersController@getComments'));
Route::get('wall-posts/comments/{id}', array('middleware' => 'web', 'uses' => 'WallPostsController@getComments'));

Route::group([
        'middleware' => ['auth', 'acl'],
        'is' => 'user'
], function () {
//    Route::resource('reader', 'ReaderController@getMyStudyVersev');
    Route::controller('notes', 'NotesController');
    Route::controller('journal', 'JournalController');
    Route::controller('prayers', 'PrayersController');
    Route::controller('wall-posts', 'WallPostsController');
});

//Route::get('notes/comments', [
//    'uses'          => 'NotesController@getComments',
//    'middleware'    => ['web'],
//    'as'            => 'notes'
//]);

/*
| User routes
*/

Route::group([
    'middleware' => ['auth', 'acl'],
    'is' => 'administrator|user'
    ],
    function () {
        Route::controllers([
            'user' => 'UserController',
        ]);
    });

//Route::get('user/delete-avatar', [
//    'uses'        => 'UserController@anyDeleteAvatar',
//    'middleware'   => ['auth', 'acl'],
//    'is'           => 'administrator',
//]);

/*Route::group([
    'middleware' => ['auth', 'acl'],
    'is' => 'administrator|user',
],
    function () {
//        Route::get('user/delete-avatar', [
//            'uses' => 'UserController@anyDeleteAvatar',
//        ]);
        Route::controllers([
            'user' => 'UserController',
        ]);
    });*/

View::composer('reader.filters', 'App\Http\Composers\BibleFiltersComposer');
View::composer('admin.partials.filters', 'App\Http\Composers\BibleFiltersComposer');
View::composer('admin.user.filters', 'App\Http\Composers\UserFiltersComposer');
View::composer('locations.filters', 'App\Http\Composers\BibleFiltersComposer');
View::composer('admin.location.filters', 'App\Http\Composers\BibleFiltersComposer');
View::composer('admin.peoples.filters', 'App\Http\Composers\BibleFiltersComposer');
View::composer('admin.articles.filters', 'App\Http\Composers\CategoryFiltersComposer');
View::composer('peoples.filters', 'App\Http\Composers\BibleFiltersComposer');
View::composer('notes.filters', 'App\Http\Composers\NotesFiltersComposer');
View::composer('journal.filters', 'App\Http\Composers\NotesFiltersComposer');
View::composer('prayers.filters', 'App\Http\Composers\NotesFiltersComposer');
View::composer('user.my-journey', 'App\Http\Composers\NotesFiltersComposer');
