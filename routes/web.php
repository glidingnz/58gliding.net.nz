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

Route::get('/', function () {
	return view('welcome', ['org'=>\Request::get('_ORG')]);
});


Route::get('/oauth', function () {
	return view('oauth');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::get('/aircraft', 'Apps\AircraftController@index');
Route::get('/aircraft/{rego}', 'Apps\AircraftController@view');
Route::get('/aircraft/{rego}/edit', 'Apps\AircraftController@edit');

Route::get('/tracking', 'Apps\TrackingController@index');
Route::get('/tracking/{year}-{month}-{day}', 'Apps\TrackingController@day');
Route::get('/tracking/{year}-{month}-{day}/{rego}', 'Apps\TrackingController@track');
Route::get('/tracking2/{year}-{month}-{day}', 'Apps\TrackingController@day2');
Route::get('/calendar', 'Apps\CalendarController@index');

Route::get('/ratings-report', 'Apps\MembersController@ratingsReport');

Route::POST('/overland', 'Api\v1\TrackingApiController@overland'); // special route for overland cell phone tracking app. Easier to type than API URL.
Route::POST('/btraced/{rego}', 'Api\v1\TrackingApiController@btraced'); // special route for btraced cell phone tracking app. Easier to type than API URL.
Route::GET('/btraced/{rego}', 'Api\v1\TrackingApiController@btraced'); // special route for btraced cell phone tracking app. Easier to type than API URL.

Route::get('/api-control', 'HomeController@api_controller');

Route::get('/calendar', 'Apps\CalendarController@index');

Route::get('/members', 'Apps\MembersController@index');
Route::group(['middleware' => ['auth']], function () {
	Route::get('/user/account', 'UserController@view_account');
	Route::post('/update-account', 'UserController@update_account');

	Route::get('/admin', 'AdminController@index');
	Route::get('/admin/users', 'AdminUsersController@index');
	Route::get('/admin/users/{user}/roles', 'AdminUsersController@roles');
	Route::post('/admin/import-flarm', 'AdminController@import_flarm');
	Route::post('/admin/import-badges', 'AdminController@import_badges');
	Route::post('/admin/import-qgps', 'AdminController@import_qgps');
	Route::post('/admin/sync-qgps', 'AdminController@sync_qgps');
	Route::post('/admin/import-aircraft-from-caa', 'AdminController@import_aircraft_from_caa');
	Route::post('/admin/email-address-changes', 'AdminController@email_address_changes');

	Route::get('/members/{id}', 'Apps\MembersController@view');
	Route::get('/members/{id}/achievements', 'Apps\MembersController@achievements');
	Route::get('/members/{id}/achievements/edit', 'Apps\MembersController@edit_achievements');
	Route::get('/members/{id}/edit', 'Apps\MembersController@edit');
	Route::get('/members/{id}/ratings', 'Apps\MembersController@ratings');
	Route::get('/members/download/{key}', 'Apps\MembersController@download');

	Route::get('/calendar/edit', 'Apps\CalendarController@edit');

});