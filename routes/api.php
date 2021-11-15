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

    // ログイン
    Route::post('login', 'LoginController@login')->name('login');
    // アカウント作成
    Route::post('register', 'RegisterController@register')->name('register');

    // 認証済みの場合のみ有効
    Route::group(['middleware' => ['auth:api']], function () {

        // ログインユーザー情報取得
        Route::get('user', 'AuthenticationController@user')->name('user');

        // ログアウト
        Route::post('logout', 'LoginController@logout')->name('logout');

        // 「今日の格言」取得
        Route::get('kakugens', 'KakugenController@index')->name('kakugens');

        // お気に入り
        Route::post('kakugens/{kakugen_id}/favorite', 'FavoriteController@store');
        Route::delete('kakugens/{kakugen_id}/favorite', 'FavoriteController@destroy');

        // コメント
        Route::post('kakugens/{kakugen_id}/comment', 'CommentController@store');
        Route::put('kakugens/{kakugen_id}/comment', 'CommentController@update');
        Route::delete('kakugens/{kakugen_id}/comment', 'CommentController@destroy');

        // My格言取得
        Route::get('mykakugens', 'MyKakugenController@index');

        // wiki人物情報取得
        Route::get('person', 'PersonController@index');

    });
});
