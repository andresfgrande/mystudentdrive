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
Route::post('/addperiod','vuejs\StudyController@addPeriod')->name('add_period');
Route::get('/getstudiesajax','vuejs\StudyController@getStudiesAjax')->name('get_studies_ajax');
Route::get('/getperiodsbyyear','vuejs\StudyController@getPeriodsByYear')->name('get_periods_by_year');

/**********VISTAS PRINCIPALES********/

Route::resource('/study/{study_id}','StudyViewController', ['only' => ['index', 'create', 'store',]]);
Route::get('/years_by_one_study','vuejs\StudyViewController@getYearsByOneStudy')->name('get_years_by_one_study');
Route::get('/sections_by_subject','vuejs\StudyViewController@getSectionsBySubject')->name('get_sections_by_subject');
Route::get('/files_by_section','vuejs\StudyViewController@getFilesBySection')->name('get_files_by_section');
Route::post('/add_section','vuejs\StudyViewController@addSection')->name('add_section');
Route::put('/edit_section','vuejs\StudyViewController@editSection')->name('edit_section');

Route::post('/upload_file', 'aws\UploadFileController@uploadFile')->name('upload_file');
Route::get('/download_file', 'aws\UploadFileController@downloadFile')->name('download_file');
Route::delete('/delete_file', 'aws\UploadFileController@deleteFile')->name('delete_file');

Route::delete('/delete_section', 'vuejs\StudyViewController@deleteSection')->name('delete_section');
Route::delete('/delete_subject', 'vuejs\StudyViewController@deleteSubject')->name('delete_subject');
Route::delete('/delete_year', 'vuejs\StudyViewController@deleteYear')->name('delete_year');
Route::delete('/delete_study', 'vuejs\StudyViewController@deleteStudy')->name('delete_study');
