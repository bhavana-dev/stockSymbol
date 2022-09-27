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

/* Route::get('/', function () {
    return view('welcome');
}); */

Route::get('/', 'stockController@index');
Route::get('/fbLogin', 'stockController@fbLogin');
Route::get('/fbLogout', 'stockController@fbLogout');
Route::post('/fbLoginPost', 'stockController@fbLoginPost');

Route::post('/saveData', 'stockController@saveData');