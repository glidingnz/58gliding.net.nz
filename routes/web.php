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

Route::post('/register', 'UserController@create'); //override default register route, to use our own
Route::get('/activate', 'UserController@activate');
Route::post('/activate', 'UserController@activate_post');

Route::get('/home', 'HomeController@index')->name('home');


Route::get('/aircraft', 'Apps\AircraftController@index');
Route::get('/aircraft/{rego}', 'Apps\AircraftController@view');
Route::get('/aircraft/{rego}/edit', 'Apps\AircraftController@edit');

Route::get('/waypoints/download', 'Apps\WaypointsController@download')->name('waypoints.download');
Route::match(['get','post'],'/waypoints/upload', 'Apps\WaypointsController@upload')->name('waypoints.upload');
Route::resource('/waypoints', 'Apps\WaypointsController');

Route::match(['get','patch'],'/cups/attach/{ref}', 'Apps\CupsController@attach')->name('cups.attach');
Route::match(['get','patch'],'/cups/detach/{ref}', 'Apps\CupsController@detach')->name('cups.detach');
Route::get('/cups/download/{ref}', 'Apps\CupsController@download')->name('cups.download');
Route::get('/cups/airspace/{ref}', 'Apps\CupsController@airspace')->name('cups.airspace');
Route::resource('/cups', 'Apps\CupsController');

Route::resource('/contests', 'Apps\ContestsController');
Route::resource('/contestclasses', 'Apps\ContestClassesController');
Route::match(['post','patch'],'/contestentries/savedata', 'Apps\ContestEntriesController@savedata')->name('contestentries.savedata');
Route::match(['post','get'],'/contestentries/loaddata', 'Apps\ContestEntriesController@loaddata')->name('contestentries.loaddata');
Route::match(['post','patch'],'/contestentries/contestentries/savedata', 'Apps\ContestEntriesController@savedata')->name('contestentries.savedata');
Route::match(['post','get'],'/contestentries/contestentries/loaddata', 'Apps\ContestEntriesController@loaddata')->name('contestentries.loaddata');
Route::resource('/contestentries', 'Apps\ContestEntriesController');

Route::get('/tracking', 'Apps\TrackingController@index');
Route::get('/tracking/{year}-{month}-{day}', 'Apps\TrackingController@day');
Route::get('/tracking/{year}-{month}-{day}/{rego}', 'Apps\TrackingController@track');
Route::get('/tracking2/{year}-{month}-{day}', 'Apps\TrackingController@day2');

Route::get('/ratings-report', 'Apps\MembersController@ratingsReport');

Route::POST('/overland', 'Api\v1\TrackingApiController@overland'); // special route for overland cell phone tracking app. Easier to type than API URL.
Route::POST('/btraced/{rego}', 'Api\v1\TrackingApiController@btraced'); // special route for btraced cell phone tracking app. Easier to type than API URL.
Route::GET('/btraced/{rego}', 'Api\v1\TrackingApiController@btraced'); // special route for btraced cell phone tracking app. Easier to type than API URL.

Route::get('/api-control', 'HomeController@api_controller');

Route::get('/flying-days', 'Apps\CalendarController@index');


Route::get('/events/', 'Apps\EventsController@index');
Route::get('/events/{slug}', 'Apps\EventsController@viewEvent');

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
	Route::get('/members/{member_id}/ratings/{rating_member_id}', 'Apps\MembersController@rating');
	Route::get('/members/download/{key}', 'Apps\MembersController@download');

	Route::get('/flying-days/edit', 'Apps\CalendarController@edit');
	Route::get('/flying-days/duties/edit', 'Apps\CalendarController@editDuties');
	Route::get('/flying-days/roster/edit', 'Apps\CalendarController@editRoster');

	Route::get('/events/{slug}/edit', 'Apps\EventsController@editEvent');

	Route::get('/gaggles', 'Apps\GagglesController@index');

});