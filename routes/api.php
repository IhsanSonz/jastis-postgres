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

  Route::name('lecture.')->prefix('lecture')->group(function () {
    Route::get('/', 'LectureController@index')->name('all');
    Route::get('{id}', 'LectureController@show')->name('show');
    Route::get('{id}/lecturers', 'LectureController@getLecturers')->name('lecturers');
    Route::get('{id}/students', 'LectureController@getStudents')->name('students');
    Route::post('store', 'LectureController@store')->name('store');
    Route::put('update/{id}', 'LectureController@update')->name('update');
    Route::delete('delete/{id}', 'LectureController@destroy')->name('delete');
  });

  Route::name('user.')->prefix('user')->group(function () {
    Route::get('lecture', 'UserController@getLectures')->name('lecture');
    Route::post('join', 'UserController@joinLecture')->name('join');
    Route::put('update-lecture-role/{id}', 'UserController@updateLevel')->name('update-lecture-role');
    Route::delete('leave', 'UserController@leaveLecture')->name('leave');
  });

});
