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
Route::delete('users/{user}','UsersController@destroy')->name('users.destroy');

//会话登录退出
Route::get('login','SessionsController@create')->name('login');
Route::post('login','SessionsController@store')->name('login');
Route::delete('logout','SessionsController@destroy')->name('logout');


//激活令牌
Route::get('signup/confirm/{token}','UsersController@confirmEmail')->name('confirm_email');

//密码重置

//一个邮箱表单
Route::get('password/reset','Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
//对邮箱表单进行处理(发送邮件，以返回一个带口令重置密码的表单)
Route::post('password/email','Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');

//重置密码表单
Route::get('password/reset/{token}','Auth\ResetPasswordController@showResetForm')->name('password.reset');
//对重置密码表单进行处理
Route::post('password/reset','Auth\ResetPasswordController@reset')->name('password.update');

//微博发布与删除
Route::resource('statuses','StatusesController',['only'=>['store','destroy']]);

//用户关注与粉丝
Route::get('users/{user}/followings','UsersController@followings')->name('users.followings');
Route::get('users/{user}/followers','UsersController@followers')->name('users.followers');
