<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::any('/admin', 'Admin\Controller@execute')->middleware('is_admin')->name('admin');

Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');
// Route::get('/questions', 'HomeController@questions')->name('questions');
Route::get('/api/questions', 'Api\QuestionController@index');
Route::post('/api/question/answer', 'Api\QuestionController@answer');
Route::get('/api/user', 'Api\UserController@getUserInfo');
Route::post('/api/user/update', 'Api\UserController@update');

Route::any('{any}', 'HomeController@spa')->where('any', '.*');
