<?php

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


Auth::routes();
Route::get('/dashboard', 'DashboardController@index')->name('dashboard');


//Route::get('/', function () {
//    return view('welcome');
//});
Route::get('/', 'IndexController@index' );
//Route::get('/dashboard/mi-perfil', 'UserController@index' );

/*****ACCOUNT*****/
Route::resource('account', 'UserController');
Route::put('update/password','vuejs\UpdatePasswordController@updatePass')->name('updatePass');
Route::post('/uploadphoto', 'vuejs\UploadPhotoController@uploadProfilePhoto')->name('uploadphoto');
Route::delete('/deletephoto','vuejs\UploadPhotoController@deleteProfilePhoto')->name('deletephoto');

/******SECTIONS*******/
Route::get('/testajax', 'vuejs\UploadPhotoController@testFunction')->name('test');

/******ESTUDIOS******/
Route::resource('studies', 'StudyController');
Route::get('/yearsbystudy','vuejs\StudyController@getYearsByStudy')->name('get_years_by_study');
Route::get('/subjectsbyyear','vuejs\StudyController@getSubjectsByYear')->name('get_subjects_by_year');
Route::post('/addstudy','vuejs\StudyController@addStudy')->name('add_study');
Route::post('/addyear','vuejs\StudyController@addYear')->name('add_year');
Route::post('/addsubject','vuejs\StudyController@addSubject')->name('add_subject');
Route::get('/getstudiesajax','vuejs\StudyController@getStudiesAjax')->name('get_studies_ajax');
