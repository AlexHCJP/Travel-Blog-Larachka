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



Route::middleware(['api'])->group(function (){
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');

    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::get('me', 'AuthController@me');


    Route::post('uploadImage', 'APIController@uploadImage');

    Route::get('countries', 'APIController@countries');
    Route::get('cities', 'APIController@cities');
    Route::get('blogs', 'APIController@blogs');
    Route::get('bestCities', 'APIController@bestCities');
    Route::get('lastBlogs', 'APIController@lastBlogs');
    Route::get('home', 'APIController@home');
    Route::get('user/{user}', 'APIController@user');

    Route::resource('/blog', 'BlogController')->except('create', 'edit', 'index');
    Route::resource('/comment', 'CommentController')->except('create', 'edit', 'index', 'show');
    Route::resource('/city', 'CityController')->except('create', 'edit', 'index', 'store', 'destroy');
    Route::resource('/country', 'CountryController')->except('create', 'edit', 'store', 'destroy', 'update');
});

