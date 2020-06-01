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
/*****SUBJECT******/

/*****STUDY******/
Route::resource('/study/{study_id}','StudyViewController', ['only' => ['index', 'create', 'store',]]);

Route::get('/years_by_one_study','vuejs\StudyViewController@getYearsByOneStudy')->name('get_years_by_one_study');
Route::get('/sections_by_subject','vuejs\StudyViewController@getSectionsBySubject')->name('get_sections_by_subject');
Route::get('/files_by_section','vuejs\StudyViewController@getFilesBySection')->name('get_files_by_section');
Route::post('/add_section','vuejs\StudyViewController@addSection')->name('add_section');
Route::put('/edit_section','vuejs\StudyViewController@editSection')->name('edit_section');
Route::put('/edit_subject','vuejs\StudyViewController@editSubject')->name('edit_subject');

Route::post('/upload_file', 'aws\UploadFileController@uploadFile')->name('upload_file');
Route::get('/download_file', 'aws\UploadFileController@downloadFile')->name('download_file');
Route::delete('/delete_file', 'aws\UploadFileController@deleteFile')->name('delete_file');

Route::delete('/delete_section', 'vuejs\StudyViewController@deleteSection')->name('delete_section');
Route::delete('/delete_subject', 'vuejs\StudyViewController@deleteSubject')->name('delete_subject');
Route::delete('/delete_year', 'vuejs\StudyViewController@deleteYear')->name('delete_year');
Route::delete('/delete_study', 'vuejs\StudyViewController@deleteStudy')->name('delete_study');

/*****SUBJECT******/
Route::get('/study/{study_id}/subject/{subject_identity}','SubjectController@index')->name('index_subject');

/*****AGENDA*****/
Route::get('/planner','PlannerController@index')->name('index_planner');
Route::post('/add_event_planner','vuejs\PlannerController@addEvent')->name('add_event');
Route::get('/get_events','vuejs\PlannerController@getEvents')->name('get_events');
Route::get('/get_subjects_by_user','vuejs\PlannerController@getSubjectsByUser')->name('get_subjects_by_user');
Route::put('/edit_event_planner','vuejs\PlannerController@editEvent')->name('edit_event');
Route::delete('/delete_event_planner','vuejs\PlannerController@deleteEvent')->name('delete_event');
Route::put('/update_old_events','vuejs\PlannerController@updateOldEvents')->name('update_old_events');
Route::get('/get_events_by_study','vuejs\PlannerController@getEventsByStudy')->name('get_events_by_study');
Route::get('/get_events_by_subject','vuejs\PlannerController@getEventsBySubject')->name('get_events_by_subject');
Route::get('/get_events_by_date','vuejs\PlannerController@getEventsByDate')->name('get_events_by_date');

/******TASKS******/
Route::post('/add_task','vuejs\TaskController@addTask')->name('add_task');
Route::get('/get_tasks_by_user','vuejs\TaskController@getAllTasks')->name('get_tasks_by_user');
Route::get('/get_tasks_by_subject','vuejs\TaskController@getTasksBySubject')->name('get_tasks_by_subject');
Route::delete('/delete_task','vuejs\TaskController@deleteTask')->name('delete_task');
Route::put('/task_done','vuejs\TaskController@taskDone')->name('task_done');
Route::put('/edit_task','vuejs\TaskController@editTask')->name('edit_task');
