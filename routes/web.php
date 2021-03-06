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

Route::get('/','StaticPagesController@home')->name('home');
Route::get('/help','StaticPagesController@help')->name('help');
Route::get('/about','StaticPagesController@about')->name('about');

Route::get('/sigrup','UsersController@create')->name('sigrup');
Route::get('/sigrup/confirm/{token}','UsersController@confirmEmail')->name('confirm_email');


Route::resource('users','UsersController');

Route::get('login','SessionController@create')->name('login');
Route::post('login','SessionController@store')->name('login');
Route::delete('logout','SessionController@destory')->name('logout');

//密码重置
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
//显示重置密码的邮箱发送页面
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
//邮箱发送重设链接
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
//密码更新页面
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
//执行密码更新操作

//生成微博路由
Route::resource('statuses','StatusesController',['only' => ['store','destroy']]);

Route::get('/users/{user}/followings','UsersController@followings')->name('users.followings');
Route::get('/users/{user}/followers','UsersController@followers')->name('users.followers');


Route::post('/users/followers/{user}','FollowersController@store')->name('followers.store');
Route::delete('/users/followers/{user}','FollowersController@destroy')->name('followers.destroy');
