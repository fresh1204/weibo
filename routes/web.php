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

/*
Route::get('/', function () {
    return view('welcome');
});
*/

Route::get('/','StaticPagesController@home')->name('home');
Route::get('/help','StaticPagesController@help')->name('help');
Route::get('/about','StaticPagesController@about')->name('about');

//注册路由
Route::get('signup','UsersController@create')->name('signup');

Route::get('users','UsersController@index')->name('users.index');
Route::get('users/{user}','UsersController@show')->name('users.show');
Route::get('users/create','UsersController@create')->name('users.create');
Route::post('users/store','UsersController@store')->name('users.store');
Route::get('users/{user}/edit','UsersController@edit')->name('users.edit');
Route::patch('users/{user}','UsersController@update')->name('users.update');

//会话登录退出
Route::get('login','SessionsController@create')->name('login');
Route::post('login','SessionsController@store')->name('login');
Route::delete('logout','SessionsController@destroy')->name('logout');
