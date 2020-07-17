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

Route::patch('users/{user}/restore','API\UserController@restore');
Route::apiResource('users','API\UserController');
Route::apiResource('categories','API\CategoryController');


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
