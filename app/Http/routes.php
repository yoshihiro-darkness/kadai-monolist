<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'WelcomeController@index');

//User registration
Route::get('signup', 'Auth\AuthController@getRegister')->name('signup.get');
Route::post('signup', 'Auth\AuthController@postRegister')->name('signup.post');

// login authentification
Route::get('login', 'Auth\AuthController@getLogin')->name('login.get');
Route::post('login', 'Auth\AuthController@postLogin')->name('login.post');
Route::get('logout', 'Auth\AuthController@getLogout')->name('logout.get');

// Ranking
Route::get('ranking/want', 'RankingController@want')->name('ranking.want');
Route::get('ranking/have', 'RankingController@have')->name('ranking.have');

Route::group(['middleware' => 'auth'], function () {
	Route::resource('items', 'ItemsController', ['only' => ['create', 'show']]);
	Route::post('want', 'ItemsController@want')->name('item_user.want');
	Route::delete('want', 'ItemsController@dont_want')->name('item_user.dont_want');
	Route::post('have', 'ItemsController@have')->name('item_user.have');
	Route::delete('have', 'ItemsController@dont_have')->name('item_user.dont_have');
	Route::resource('users', 'UsersController', ['only' => ['show']]);
});	
