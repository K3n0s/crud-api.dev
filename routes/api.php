<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/obiekty', 'App\Http\Controllers\ObiektyController@index');
Route::get('/obiekt/{id}', 'App\Http\Controllers\ObiektyController@show');
Route::post('/obiekt', 'App\Http\Controllers\ObiektyController@create');
Route::put('/obiekt/{id}', 'App\Http\Controllers\ObiektyController@edit');
Route::delete('/obiekt/{id}', 'App\Http\Controllers\ObiektyController@delete');

//Operacje wyszukiwania routy
Route::get('/obiekty/findByNumer', 'App\Http\Controllers\ObiektyController@findByNumer');
Route::get('/obiekty/findByDate', 'App\Http\Controllers\ObiektyController@findByDate');
Route::get('/obiekty/findByStatus', 'App\Http\Controllers\ObiektyController@findByStatus');
Route::get('/obiekty/findByHistoryStatus', 'App\Http\Controllers\ObiektyController@findByHistoryStatus');