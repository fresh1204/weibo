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
