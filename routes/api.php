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
    @TODO: version logic should be in RouteServiceProvider, version should be defined in env
*/

Route::prefix('v1')
    ->group(function () {
        Route::post('signUp', 'AuthController@signUp');
        Route::post('signIn', 'AuthController@signIn');

        Route::middleware('auth:api')->group(function () {
            Route::post('signOut', 'AuthController@signOut');

            Route::get('explore', 'ImageController@explore');

            Route::prefix('classrooms')->group(function () {
//                Route::resource(null, 'ClassroomController')->only(['store', 'show']);
                Route::post(null, 'ClassroomController@store');
                Route::get('{classroom}', 'ClassroomController@show');
                Route::post('{classroom}/join', 'ClassroomController@join');
            });

            Route::post('users/{user}/images', 'UserController@storeImage'); // @TODO optimise image route logic

            Route::post('generate', 'GenerateController');
        });
    });
