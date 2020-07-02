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

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/questions', 'HomeController@questions')->name('questions');
Route::get('/api/questions', 'Api\QuestionController@index');
Route::post('/api/question/answer', 'Api\QuestionController@answer');
Route::post('/api/user/update_info', 'Api\User@update_info');
// Route::get('/{any}', 'HomeController@spa')->where('any', '.*');