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
    return view('login');
})->middleware('already_login');

Route::post('/register', 'App\Http\Controllers\AuthController@register');
Route::post('/login-user','App\Http\Controllers\AuthController@submitlogin');
Route::get('logout','App\Http\Controllers\AuthController@logout');

Route::group(['middleware' => 'is_logedin'], function () {

    Route::get('/profile', 'App\Http\Controllers\UserController@profile');
    Route::get('/list', 'App\Http\Controllers\UserController@list');
    Route::get('/comments/{id}', 'App\Http\Controllers\UserController@comments');
    Route::post('/comment', 'App\Http\Controllers\UserController@comment');
    Route::post('/reply', 'App\Http\Controllers\UserController@reply');

    Route::post('/postlike', 'App\Http\Controllers\UserController@postlike');
    
    Route::view('/add_interest', 'add_interest');
    Route::post('/add_interest', 'App\Http\Controllers\UserController@add_interest');

});
