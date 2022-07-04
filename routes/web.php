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

Route::post('get-header-modal', 'ModalController@renderHeaderModalByTypeAndLanguage')->name('get-header-modal');

Route::group(['prefix' => LaravelLocalization::setLocale()], function()
{
    Route::get('sitemap.xml', 'SitemapController@index')->name('sitemap');
    Route::get('games.xml', 'SitemapController@games')->name('sitemap.games');

    Route::get('/', 'HomeController@index')->name('index')->middleware('slashes');
    Route::post('/mainpage-games', 'HomeController@postGames')->name('mainpage-games')->middleware('slashes');

    Route::any('mods/{page}/', ['as' => 'mods.page', 'uses' => 'ModsController@mods'])->where(['page' =>'[0-9]+'])->middleware('slashes');
    Route::any('mods/', 'ModsController@mods')->name('mods')->middleware('slashes');
    Route::any('new-mods/{page}/', ['as' => 'new-mods.page', 'uses' => 'ModsController@newmods'])->where(['page' =>'[0-9]+'])->middleware('slashes');
    Route::any('new-mods/', 'ModsController@newmods')->name('new-mods')->middleware('slashes');
    Route::get('week-7', 'HomeController@week7')->name('week-7')->middleware('slashes');

    #Games
    Route::any('{slug}/', ['as' => 'game', 'uses' => 'GameController@gamepage'])->where(['url' => '[a-zA-Z\-\_0-9]+'])->middleware('slashes');
    Route::group(['prefix'=>'games','as'=>'games.'], function() {
        Route::post('like/{game}',['before' => 'csrf', 'as' => 'like', 'uses' => 'GameController@postLike'])->where(['game' => '[0-9]+']);
    });

    #Comments
    Route::group(['prefix'=>'comments','as'=>'comments.'], function() {
        Route::post('/game/{game_id}', ['before' => 'csrf', 'as' => 'get.game', 'uses' => 'CommentsController@getGame'])
            ->where(['game_url' => '[0-9]+']);
        Route::post('/{game}/new', ['before' => 'csrf', 'as' => 'new', 'uses' => 'CommentsController@postNew'])->where(['game' => '[0-9]+']);
        Route::post('/rate', ['before' => 'csrf', 'as' => 'rate', 'uses' => 'CommentsController@postRate']);
        Route::post('/comment', ['before' => 'csrf', 'as' => 'comment', 'uses' => 'CommentsController@postComment']);
        Route::post('/comment/delete/{comment}', ['before' => 'csrf', 'as' => 'comment.update', 'uses' => 'CommentsController@postCommentDelete'])->where(['comment' => '[0-9]+']);
        Route::post('/comment/update/{comment}', ['before' => 'csrf', 'as' => 'comment.delete', 'uses' => 'CommentsController@postCommentUpdate'])->where(['comment' => '[0-9]+']);
    });

    Route::post('search_prev', ['before' => 'csrf', 'as' => 'search_prev', 'uses' => 'SearchController@searchPrev']);
    Route::post('search_ajax', ['before' => 'csrf', 'as' => 'search_ajax', 'uses' => 'SearchController@searchAjax']);
    Route::get('search/{word}', 'SearchController@index')->name('search');
    Route::post('search/{word}', ['before' => 'csrf', 'as' => 'search.post', 'uses' => 'SearchController@postIndex']);


    Route::post('search_prev', ['before' => 'csrf', 'as' => 'search_prev', 'uses' => 'SearchController@searchPrev']);
    Route::post('search_ajax', ['before' => 'csrf', 'as' => 'search_ajax', 'uses' => 'SearchController@searchAjax']);
    Route::get('search/{word}', 'SearchController@index')->name('search');
    Route::post('search/{word}', ['before' => 'csrf', 'as' => 'search.post', 'uses' => 'SearchController@postIndex']);
    Route::post('rating', ['before' => 'csrf', 'as' => 'rating', 'uses' => 'RatingController@postAdd']);

    Route::any('{page?}', 'Controller@get404Code')->where('page','.*');
});
