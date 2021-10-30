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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group(['namespace' => 'Api', 'as' => 'api.'], function () {

    Route::post('login', 'LoginController@login')->name('login');

    Route::post('register', 'RegisterController@register')->name('register');

    Route::group(['middleware' => ['auth:api']], function () {

        Route::get('user', 'AuthenticationController@user')->name('user');

        Route::post('logout', 'LoginController@logout')->name('logout');

        Route::get('kakugens', 'KakugenController@index')->name('kakugens');

        // お気に入り
        Route::post('kakugens/{kakugen_id}/favorite', 'FavoriteController@store');
        Route::delete('kakugens/{kakugen_id}/favorite', 'FavoriteController@destroy');

        // コメント
        Route::post('kakugens/{kakugen_id}/comment', 'CommentController@store');
        Route::put('kakugens/{kakugen_id}/comment', 'CommentController@update');
        Route::delete('kakugens/{kakugen_id}/comment', 'CommentController@destroy');

        Route::get('mykakugens', 'MyKakugenController@index');

        Route::get('person', 'PersonController@index');
    });
});
