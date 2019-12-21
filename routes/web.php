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

//Route::get('/', 'IndexController@index');

Route::group(['domain' => config('www.message.reply.com')], function ()
{
    Route::group(['prefix' => 'user'], function ()
    {
        Route::post('register', 'UserController@postRegister');
        Route::post('login', 'UserController@login');
        Route::post('logout', 'UserController@logout');
    });

    Route::group(['prefix' => 'message', 'middleware' => ['login']], function ()
    {
        Route::post('create', 'MessageController@create');
        Route::post('update/{id}', 'MessageController@update');
        Route::post('delete/{id}', 'MessageController@delete');
        Route::post('reply_create', 'ReplyController@replyCreate');
        Route::post('reply_update/{rid}', 'ReplyController@replyUpdate');
        Route::post('reply_delete/{rid}', 'ReplyController@replyDelete');
        Route::get('list', 'MessageController@list');
        Route::get('info', 'MessageController@info');
        Route::get('one_info', 'MessageController@oneInfo');
        Route::get('open_all_info', 'MessageController@openAllInfo');
        Route::get('list', 'MessageController@replyRankingList');
    });
});

