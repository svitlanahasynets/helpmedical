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

/*
|--------------------------------------------------------------------------
| Admin Routing
|--------------------------------------------------------------------------
*/



Route::group(['prefix' => 'admin'], function () {

	Route::get('login',  'Auth\LoginController@showLoginForm')->name('admin.login');
	Route::post('login', 'Auth\LoginController@login');
	Route::get('logout', 'Auth\LoginController@logout')->name('admin.logout');

	Route::group(['middleware' => 'auth', 'namespace' => 'Admin'], function () {

	    Route::get('/{id?}', ['as' => 'dashboard', 'uses' => 'DashboardController@index'])->where(['id' => '[0-9]+']);

		Route::match(['get', 'post'], 'schedule', 'CompetitionController@schedule')->name('schedule');
		Route::match(['get', 'post'], 'schedule/view/{id}', ['as' => 'schedule.schedule_view', 'uses' => 'CompetitionController@schedule_view'])->where(['id' => '[0-9]+']);
		Route::match(['get', 'post'], 'schedule/edit/{id}', ['as' => 'schedule.schedule_edit', 'uses' => 'CompetitionController@schedule_edit'])->where(['id' => '[0-9]+']);

		Route::match(['get', 'post'], 'result', 'CompetitionController@result')->name('result');

		Route::post('delete-file', ['as' => 'admin.deletefile', 'uses' => 'CompetitionController@deleteFile']);

		Route::match(['get', 'post'], 'employee/search_skills', ['as' => 'employee.search_skills.ajax', 'uses' => 'EmployeeController@searchSkills']);
		Route::post('employee/editsave/{id}', ['as' => 'employee.editsave', 'uses' => 'EmployeeController@editsave'])->where(['id' => '[0-9]+']);
		// 뒤에다 Restfull controller 를 정의해준다!
		Route::resource('employee', 'EmployeeController');

		Route::get('profile', 'ProfileController@index')->name('profile');
        Route::post('profile', 'ProfileController@update')->name('profile.save');


	});
});

/*
|--------------------------------------------------------------------------
| Frontend Routing
|--------------------------------------------------------------------------
*/

Route::group(['namespace' => 'Frontend'], function () {
	Route::match(['get', 'post'], '/', ['as' => 'homepage', 'uses' => 'HomepageController@index']);
	Route::match(['get', 'post'], 'competition/overview/{id}', ['as' => 'competition.overview', 'uses' => 'HomepageController@competition_view'])->where(['id' => '[0-9]+']);
	Route::match(['get', 'post'], 'schedule/overview/{id}', ['as' => 'schedule.overview', 'uses' => 'HomepageController@schedule_view'])->where(['id' => '[0-9]+']);
});
