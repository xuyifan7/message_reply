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

//Route::get('/', function () {
//    return view('welcome');
//});

//Route::get('/', 'IndexController@index');

//Route::post('user/one','UserController@one');

Route::post('user/register', 'UserController@postregister');
Route::post('user/login','UserController@login');
Route::post('user/logout', 'UserController@logout');
Route::group(array('prefix'=>'message'),function(){
    Route::any('/create','MessageController@create');
    //Route::any('/update/{post}','MessageController@update');
    //Route::any('/delete/{post}', 'MessageController@delete');
    Route::any('/reply_create/{post}','ReplyController@reply_create');
    Route::any('/reply_update/{post}','ReplyController@reply_update');
    Route::any('/reply_delete/{post}','ReplyController@reply_delete');
    Route::any('/list', 'MessageController@list');
    //Route::any('info/{post}','MessageController@info');
    Route::any('info/{post}','ReplyController@info');
});
//Route::post('message/create','MessageController@create');
Route::post('message/update/{id}','MessageController@update');
Route::get('message/delete/{id}','MessageController@delete');
/*Route::post('message/reply_create/{id}','MessageController@reply_create');
Route::post('message/reply_update/{id}','MessageController@reply_update');
Route::get('message/reply_delete/{id}','MessageController@reply_delete');*/
//Route::get('message/list','MessageController@list');
//Route::get('message/info/{id}','MessageController@info');