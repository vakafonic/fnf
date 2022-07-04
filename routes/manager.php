<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => LaravelLocalization::setLocale()], function()
{
    Route::group(['middleware' => ['web']], function() {

// Login Routes...
        Route::get('login', ['as' => 'login', 'uses' => 'Authmanager\LoginController@showLoginForm']);
        Route::post('login', ['as' => 'm.login.post', 'uses' => 'Authmanager\LoginController@mlogin']);
        Route::post('logout', ['as' => 'm.logout', 'uses' => 'Authmanager\LoginController@mlogout']);

// Registration Routes...
//Route::get('register', ['as' => 'register', 'uses' => 'Auth\RegisterController@showRegistrationForm']);
//Route::post('register', ['as' => 'register.post', 'uses' => 'Auth\RegisterController@register']);

// Password Reset Routes...
        Route::get('password/reset', ['as' => 'password.reset', 'uses' => 'Authmanager\ForgotPasswordController@showLinkRequestForm']);
        Route::post('password/email', ['as' => 'password.email', 'uses' => 'Authmanager\ForgotPasswordController@sendResetLinkEmail']);
        Route::get('password/reset/{token}', ['as' => 'password.reset.token', 'uses' => 'Authmanager\ResetPasswordController@showResetForm']);
        Route::post('password/reset', ['as' => 'password.reset.post', 'uses' => 'Authmanager\ResetPasswordController@reset']);

    });

    Route::group(['middleware' => ['auth.admin']], function() {

        // Home
        Route::get('/', 'Manager\HomeController@index')->name('m.home');

        // Search
        Route::get('search-word', 'Manager\HomeController@searchWord')->name('m.search.word');
        Route::get('get-search-word', 'Manager\HomeController@getSearchWord')->name('m.get.search.word');

        // Users
        Route::get('users', 'Manager\UsersController@index')->name('m.users');
        Route::get('users/post/all', 'Manager\UsersController@allUsers')->name('m.users.post.all');
        Route::get('user/new', 'Manager\UsersController@newUser')->name('m.users.new');
        Route::post('user/create', 'Manager\UsersController@createUser')->name('m.users.create');
        Route::get('user/edit/{user}', 'Manager\UsersController@edit')->name('m.users.edit');
        Route::post('user/edit/{user}', 'Manager\UsersController@editPost')->name('m.users.edit.post');
        Route::post('apiadmin/users/delete/{user}', 'Manager\UsersController@deleteUser')->name('m.users.delete');

        Route::group(['prefix'=>'pages','as'=>'m.pages.'], function(){
            Route::get('/', ['as' => 'index', 'uses' => 'Manager\PagesController@index']);
            Route::get('/edit/{page}', ['as' => 'edit', 'uses' => 'Manager\PagesController@getEdit']);
            Route::put('/edit/{page}', ['as' => 'edit', 'uses' => 'Manager\PagesController@putEdit']);
            Route::get('all', ['as' => 'all', 'uses' => 'Manager\PagesController@getAll']);
            Route::get('categories/{page_id}', ['as' => 'categories', 'uses' => 'Manager\PagesController@getCategories']);
            Route::get('heroes/{page_id}', ['as' => 'heroes', 'uses' => 'Manager\PagesController@getHeroes']);
        });

        Route::get('comments', ['as' => 'm.comments.index', 'uses' => 'Manager\CommentsController@index']);
        Route::get('comments/all', ['as' => 'm.comments.all', 'uses' => 'Manager\CommentsController@all']);
        Route::get('comment/edit/{comment}', ['as' => 'm.comment.edit', 'uses' => 'Manager\CommentsController@edit']);
        Route::post('comment/edit/{comment}', 'Manager\CommentsController@editPost')->name('m.comment.edit.post');
        Route::post('comment/public/{comment}', ['as' => 'm.comment.public', 'uses' => 'Manager\CommentsController@public']);
        Route::post('comment/delete/{comment}', ['as' => 'm.comment.delete', 'uses' => 'Manager\CommentsController@delete']);

        Route::group(['prefix'=>'feedbacks','as'=>'m.feedbacks.'], function() {
            Route::get('/', ['as' => 'index', 'uses' => 'Manager\FeedbacksController@index']);
            Route::get('get', ['as' => 'get', 'uses' => 'Manager\FeedbacksController@getAll']);
            Route::post('message/{feedback}', ['as' => 'feedback', 'uses' => 'Manager\FeedbacksController@postFeedback']);
            Route::post('delete-message/{feedback}', ['as' => 'feedback', 'uses' => 'Manager\FeedbacksController@postDeleteMessage']);
        });

        Route::group(['prefix'=>'seo-page','as'=>'m.seopage.'], function() {
            Route::get('/', ['as' => 'index', 'uses' => 'Manager\SeopageController@index']);
            Route::get('all', ['as' => 'all', 'uses' => 'Manager\SeopageController@getAll']);
            Route::get('new', ['as' => 'new', 'uses' => 'Manager\SeopageController@getNew']);
            Route::get('edit/{seopage}', ['as' => 'edit', 'uses' => 'Manager\SeopageController@getNew']);
            Route::put('new', ['as' => 'new.put', 'uses' => 'Manager\SeopageController@putNew']);
            Route::put('edit/{seopage}', ['as' => 'edit.put', 'uses' => 'Manager\SeopageController@putNew']);
            Route::get('genries', ['as' => 'genries', 'uses' => 'Manager\SeopageController@getGenries']);
            Route::get('heroes', ['as' => 'heroes', 'uses' => 'Manager\SeopageController@getHeroes']);
            Route::post('public/{seopage}/{show}', ['as' => 'public', 'uses' => 'Manager\SeopageController@postPublic'])->where(['show' => '0|1']);
            Route::post('sort', ['as' => 'sort', 'uses' => 'Manager\SeopageController@postSort']);
            Route::post('delete/{seopage}', ['as' => 'delete', 'uses' => 'Manager\SeopageController@postDelete']);
        });

        Route::group(['prefix'=>'games','as'=>'m.games.'], function(){
            Route::get('/', ['as' => 'index', 'uses' => 'Manager\GamesController@index']);
            Route::get('all', ['as' => 'all', 'uses' => 'Manager\GamesController@getAll']);
            Route::get('new', ['as' => 'create', 'uses' => 'Manager\GamesController@create']);
            Route::get('edit/{game}', ['as' => 'edit', 'uses' => 'Manager\GamesController@create']);
            Route::post('new', ['as' => 'put.create', 'uses' => 'Manager\GamesController@putCreate']);
            Route::post('/videoduration', ['as' => 'video.duration', 'uses' => 'Manager\GamesController@getVideoDuration']);
            Route::post('edit/{game}', ['as' => 'put.edit', 'uses' => 'Manager\GamesController@putCreate']);
            Route::post('public/{game}/{show}', ['as' => 'post.public', 'uses' => 'Manager\GamesController@postPublic'])
                ->where(['show' => '0|1']);
            Route::post('details/{game}', ['as' => 'post.details', 'uses' => 'Manager\GamesController@postDetails']);
            Route::post('delete/{game}', ['as' => 'post.delete', 'uses' => 'Manager\GamesController@postDelete']);

            Route::get('import', ['as' => 'import', 'uses' => 'Manager\GamesController@import']);

            Route::get('similar/{game_id}', ['as' => 'similar', 'uses' => 'Manager\GamesController@getSimilar']);

            Route::get('categories', ['as' => 'categories', 'uses' => 'Manager\GameCategoriesController@index']);
            Route::get('category/new', ['as' => 'category.create', 'uses' => 'Manager\GameCategoriesController@create']);

            Route::get('genres', ['as' => 'genres', 'uses' => 'Manager\GameGenresController@index']);
            Route::get('genres/all', ['as' => 'genres.all', 'uses' => 'Manager\GameGenresController@all']);
            Route::get('genres/new', ['as' => 'genres.new', 'uses' => 'Manager\GameGenresController@getNew']);
            Route::get('genres/edit/{genre}', ['as' => 'genres.edit', 'uses' => 'Manager\GameGenresController@getNew']);
            Route::put('genres/new', ['as' => 'genres.put.new', 'uses' => 'Manager\GameGenresController@puttNew']);
            Route::post('genres/delete/{genre}', ['as' => 'genres.delete', 'uses' => 'Manager\GameGenresController@postDelete']);
            Route::put('genres/edit/{genre}', ['as' => 'genres.put.edit', 'uses' => 'Manager\GameGenresController@puttNew']);
            Route::post('genres/sort', ['as' => 'genres.sort', 'uses' => 'Manager\GameGenresController@postSort']);
            Route::post('genres/{psm}/{genre}/{show}', ['as' => 'genres.psm', 'uses' => 'Manager\GameGenresController@postPsm'])
                ->where(['show' => '0|1', 'psm' => 'public|show_menu|show_menug']);

            Route::get('heroes', ['as' => 'heroes', 'uses' => 'Manager\GameHeroesController@index']);
            Route::get('heroes/all', ['as' => 'heroes.all', 'uses' => 'Manager\GameHeroesController@all']);
            Route::post('heroes/sort', ['as' => 'heroes.sort', 'uses' => 'Manager\GameHeroesController@postSort']);
            Route::get('heroes/new', ['as' => 'heroes.get.new', 'uses' => 'Manager\GameHeroesController@getNew']);
            Route::get('heroes/edit/{heroes}', ['as' => 'heroes.get.edit', 'uses' => 'Manager\GameHeroesController@getNew']);
            Route::post('heroes/delete/{heroes}', ['as' => 'heroes.post.delete', 'uses' => 'Manager\GameHeroesController@postDelete']);
            Route::put('heroes/new', ['as' => 'heroes.put.new', 'uses' => 'Manager\GameHeroesController@putNew']);
            Route::put('heroes/edit/{heroes}', ['as' => 'heroes.put.edit', 'uses' => 'Manager\GameHeroesController@putNew']);
            Route::post('heroes/{psm}/{heroes}/{show}', ['as' => 'heroes.psm', 'uses' => 'Manager\GameHeroesController@postPsm'])
                ->where(['show' => '0|1', 'psm' => 'public|show_menu']);

        });

        Route::group(['prefix'=>'games/api','as'=>'m.games.api.'], function() {
            Route::get('developer/get', ['as' => 'developer.get', 'uses' => 'Manager\GameApiController@getDeveloper']);
        });

        Route::group(['prefix'=>'game_features','as'=>'m.game_features.'], function(){
            Route::get('/', ['as' => 'index', 'uses' => 'Manager\GameFeaturesController@index']);
            Route::get('create', ['as' => 'create', 'uses' => 'Manager\GameFeaturesController@create']);
        });

        Route::group(['prefix'=>'setting','as'=>'m.setting.'], function(){
            Route::get('language', ['as' => 'language', 'uses' => 'Manager\SettingController@language']);
            Route::post('language', ['as' => 'language.post', 'uses' => 'Manager\SettingController@languagePost']);
            Route::get('language/all', ['as' => 'language.all', 'uses' => 'Manager\SettingController@languageAll']);
            Route::post('language/sort', ['as' => 'language.sort', 'uses' => 'Manager\SettingController@languageSort']);

            Route::get('translation', ['as' => 'translation', 'uses' => 'Manager\TranslationController@translation']);
            Route::get('translation/all', ['as' => 'translation.all', 'uses' => 'Manager\TranslationController@translationAll']);
        });

        Route::group(['prefix'=>'developers','as'=>'m.developers.'], function(){
            Route::get('', ['as' => 'index', 'uses' => 'Manager\DevelopersController@index']);
            Route::get('all', ['as' => 'all', 'uses' => 'Manager\DevelopersController@all']);
            Route::post('new', ['as' => 'new', 'uses' => 'Manager\DevelopersController@postNew']);
            Route::post('edit/{developer}', ['as' => 'edit', 'uses' => 'Manager\DevelopersController@postNew']);
            Route::post('save', ['as' => 'save', 'uses' => 'Manager\DevelopersController@postSave']);
            Route::post('delete/{developer}', ['as' => 'delete', 'uses' => 'Manager\DevelopersController@postDelete']);
        });

        Route::group(['prefix'=>'command','as'=>'m.command.'], function(){
            Route::get('', ['as' => 'index', 'uses' => 'Manager\CommandController@index']);
            Route::get('all', ['as' => 'all', 'uses' => 'Manager\CommandController@getAll']);
            Route::post('edit/{buttonsPlay}', ['as' => 'edit', 'uses' => 'Manager\CommandController@postEdit']);
            Route::post('save/{buttonsPlay}', ['as' => 'save', 'uses' => 'Manager\CommandController@postSave']);
        });

        Route::group(['prefix'=>'popups','as'=>'m.popups.'], function(){
            Route::get('', ['as' => 'index', 'uses' => 'Manager\PopupController@index']);
            Route::get('all', ['as' => 'all', 'uses' => 'Manager\PopupController@getAll']);
            Route::get('edit/{popup}', ['as' => 'edit', 'uses' => 'Manager\PopupController@getEdit']);
            Route::put('edit/{popup}', ['as' => 'edit.put', 'uses' => 'Manager\PopupController@putEdit']);
        });

        Route::group(['prefix'=>'apiadmin','as'=>'m.'], function(){
            Route::post('lang/getedit/{language}', ['as' => 'language.api.get', 'uses' => 'Manager\SettingController@languageGet']);
            Route::post('lang/delete/{language}', ['as' => 'language.api.delete', 'uses' => 'Manager\SettingController@languageDelete']);

            Route::post('trans/new', ['as' => 'trans.api.new', 'uses' => 'Manager\TranslationController@apiGetNew']);
            Route::post('trans/get/{trans}', ['as' => 'trans.api.new', 'uses' => 'Manager\TranslationController@apiGettrans']);
            Route::post('trans/save', ['as' => 'trans.api.save', 'uses' => 'Manager\TranslationController@apiGetSave']);
        });

    });
});


