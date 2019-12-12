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
        Route::post('register', 'UserController@postregister');
        Route::post('login', 'UserController@login');
        Route::post('logout', 'UserController@logout');
    });

    Route::group(['prefix' => 'message', 'middleware' => ['login']], function ()
    {
        Route::post('create', 'MessageController@create');
        Route::post('update/{id}', 'MessageController@update');
        Route::post('delete/{id}', 'MessageController@delete');
        Route::post('reply_create', 'ReplyController@reply_create');
        Route::post('reply_update/{rid}', 'ReplyController@reply_update');
        Route::post('reply_delete/{rid}', 'ReplyController@reply_delete');
        Route::get('list', 'MessageController@list');
        Route::get('info', 'MessageController@info');
    });
});

