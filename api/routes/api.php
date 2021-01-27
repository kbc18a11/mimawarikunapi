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

Route::group(['middleware' => ['api']], function () {
    Route::post('/register', 'UserController@store');

    //ログイン
    Route::post('/login', 'AuthController@login');
    Route::post('/logout', function (Request $request) {
        return ['result' => 'ログアウトしました'];
    });


    Route::get('/user', 'UserController@index');

    Route::get('/room', 'RoomController@index');
    Route::get('/room/{id}', 'RoomController@show');
    Route::post('/room', 'RoomController@store');
    Route::put('/room/{id}', 'RoomController@index');
});
