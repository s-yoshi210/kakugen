<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\Api\KakugenController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\MyKakugenController;
use App\Http\Controllers\Api\PersonController;

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
    Route::post('login', [LoginController::class, 'login'])->name('login');
    // アカウント作成
    Route::post('register', [RegisterController::class, 'register'])->name('register');

    // 認証済みの場合のみ有効
    Route::group(['middleware' => ['auth:api']], function () {

        // ログインユーザー情報取得
        Route::get('user', [AuthenticationController::class, 'user'])->name('user');

        // ログアウト
        Route::post('logout', [LoginController::class, 'logout'])->name('logout');

        // 「今日の格言」取得
        Route::get('kakugens', [KakugenController::class, 'index'])->name('kakugens');

        // お気に入り
        Route::post('kakugens/{kakugen_id}/favorite', [FavoriteController::class, 'store']);
        Route::delete('kakugens/{kakugen_id}/favorite', [FavoriteController::class, 'destroy']);

        // コメント
        Route::post('kakugens/{kakugen_id}/comment', [CommentController::class, 'store']);
        Route::put('kakugens/{kakugen_id}/comment', [CommentController::class, 'update']);
        Route::delete('kakugens/{kakugen_id}/comment', [CommentController::class, 'destroy']);

        // My格言取得
        Route::get('mykakugens', [MyKakugenController::class, 'index']);

        // wiki人物情報取得
        Route::get('person', [PersonController::class, 'index']);

    });
});
