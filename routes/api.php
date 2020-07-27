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

Route::apiResource('/roles', 'API\RoleController');


Route::post('/login','API\AuthController@login');
Route::post('/register','API\AuthController@register');
Route::post('/logout','API\AuthController@logout');
Route::post('/check','API\AuthController@check');
Route::post('/password/forgot','API\ForgotPasswordController@sendResetLinkEmail');
Route::post('/password/reset','API\ResetPasswordController@reset');


Route::group(['middleware' => ['auth:sanctum']], function () {

    /**
     * Roles
     */
//    Route::apiResource('/roles', 'API\RoleController');

    /**
     * Users
     */
    Route::patch('/users/{user}/restore', 'API\UserController@restore');
    Route::apiResource('/users', 'API\UserController');

    /**
     * Profile
     */
    Route::post('/profile/update','API\ProfileController@update');
    Route::post('/profile/change-password','API\ProfileController@changePassword');
    Route::post('/profile/upload-avatar','API\ProfileController@uploadAvatar');

});
