<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/createdatabase', 'HomeController@createDatabase')->name('createdatabase');
Route::post('/newdatabase', 'HomeController@newDatabase')->name('newdatabase');


Route::get('/test', 'HomeController@test')->name('test');
Route::post('/test', 'HomeController@storeTest')->name('storetest');

Route::get('/newuser', 'HomeController@newUser')->name('newuser');
Route::post('/storeuser', 'HomeController@storeUser')->name('storeuser');
