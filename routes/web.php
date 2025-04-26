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
});

// Route::get('/students/edit/{id}', 'StudentsController@edit')->name('student.edit')->middleware('admin');
// Route::put('/students/update', 'StudentsController@update')->name('student.update')->middleware('admin');
// Route::delete('/students/delete/{id}', 'StudentsController@delete')->name('student.delete')->middleware('admin');
