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
Route::post('user/logout', 'AuthController@logout');
Route::post('/message/create','MessageController@create');
Route::post('/message/update','MessageController@update');
Route::post('/message/delete','MessageController@delete');
Route::post('/message/reply_create','MessageController@reply_create');
Route::post('/message/reply_update','MessageController@reply_update');
Route::post('/message/reply_delete','MessageController@reply_delete');
Route::get('/message/list','MessageController@list');
Route::get('/message/info','MessageController@info');