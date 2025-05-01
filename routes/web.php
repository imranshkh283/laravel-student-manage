<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/admin', 'AdminController@index')->name('admin');
Route::get('/admin/show-chart', 'AdminController@showChart')->name('show-chart');

Route::get('/profile', 'ProfileController@index')->name('profile');
Route::put('/profile', 'ProfileController@update')->name('profile.update');

Route::get('/students', 'StudentsController@index')->name('students');
Route::get('/students/create', 'StudentsController@create')->name('student.create');
Route::post('/students/insert', 'StudentsController@insert')->name('student.insert');

// middleware group
Route::middleware('admin')->group(function () {
    Route::get('/students/edit/{id}', 'StudentsController@edit')->name('student.edit');
    Route::put('/students/update', 'StudentsController@update')->name('student.update');
    Route::delete('/students/delete/{id}', 'StudentsController@delete')->name('student.delete');
    Route::get('/students/import', 'StudentsController@import')->name('student.import');
    Route::get('/students/csvSample', 'StudentsController@studentCSVSampleDownload')->name('student.csvSample');
    Route::post('/students/import', 'StudentsController@csvImport')->name('student.csvImport');

    // TODO: separate exams api
    Route::get('/admin/api_config', 'ExamsController@index')->name('api_config');
    Route::get('/admin/load_quiz', 'ExamsController@loadQuiz')->name('load_quiz');
    Route::get('/admin/quiz_page', 'ExamsController@quizPage')->name('quiz_page');
});
