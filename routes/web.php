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

Route::get('/', 'HomeController@homepage');

Route::get('/start-comparing', 'HomeController@index')->middleware('auth');

// Search Functions

Route::get('/search', 'SearchController@search')->middleware('auth');

Route::get('/product/{id}', 'SearchController@products')->middleware('auth');

Auth::routes(['verify' => true]);