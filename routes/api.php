<?php

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

Route::group([
  'middleware' => 'api',
  'namespace'  => 'App\Http\Controllers',
], function ($router) {
  Route::name('auth.')->prefix('auth')->group(function () {
    Route::post('register', 'AuthController@register')->name('register');
    Route::post('login', 'AuthController@login')->name('login');
    Route::post('logout', 'AuthController@logout')->name('logout');
    Route::post('refresh', 'AuthController@refresh')->name('refresh');
    Route::get('profile', 'AuthController@profile')->name('profile');
    Route::get('unauthorized', 'AuthController@unauthorized')->name('unauthorized');
  });
});
