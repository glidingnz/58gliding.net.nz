<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/user', function (Request $request) {
	return $request->user();
})->middleware('auth:api');


Route::group(['prefix'=>'v1', 'namespace' => 'Api\v1'], function()
{

	Route::get('/aircraft/{rego}', 'AircraftApiController@rego')
		->where('rego','(?i)ZK-[A-Z]{3}(?-i)');
		
	Route::resource('aircraft', 'AircraftApiController', ['only' => [
		'index', 'show', 'update'
	]]);
	Route::post('/aircraft/hexes',  'AircraftApiController@hexes'); // given a list of hexes, get the aircraft details

	Route::get('/orgs',  'OrgApiController@index');
	Route::get('/orgs/{id}',  'OrgApiController@show');

	Route::post('/tracking/insert',  'TrackingApiController@insert');
	Route::get('/tracking/days',  'TrackingApiController@days');
	Route::get('/tracking/{dayDate}/hexes',  'TrackingApiController@dayHexes'); // all unique hex codes on that day
	Route::get('/tracking/{dayDate}/pings',  'TrackingApiController@latestDayPings'); // last ping on that day for all hexes
	Route::get('/tracking/{dayDate}/pings/{pointsPerHex}',  'TrackingApiController@dayPings'); // x number of pings per hex for a day
	Route::get('/tracking/{dayDate}/{hex}/pings',  'TrackingApiController@dayHexPings'); // all pings for a hex code on a day
	Route::post('/electron/', 'TrackingApiController@electron');
	Route::get('/electron/', 'TrackingApiController@electron');
	Route::post('/spotnz', 'TrackingApiController@spotnz');
	Route::get('/spotnz', 'TrackingApiController@spotnz');
		
	Route::resource('/ratings', 'RatingsApiController', ['only' => [
		'index'
	]]);
		
	Route::resource('days', 'DaysApiController', ['only' => [
		'index', 'show', 'destroy', 'create', 'update'
	]]);

	Route::get('/roles',  'RolesApiController@index');
	Route::get('/badges',  'BadgesApiController@index');
	Route::get('/waypoints',  'WaypointsApiController@index');


	// special anonymous member stats for external use
	Route::get('/members/anonymous-stats', 'MembersApiController@anonymous_member_dates');
	Route::get('/members/address-changes', 'MembersApiController@address_changes');
	Route::get('/members/address-changes/{limit_date}', 'MembersApiController@address_changes');

	Route::get('/ratings/report',  'RatingMemberApiController@ratingsReport');
	
	Route::group(['middleware' => ['auth:api']], function () {

		Route::get('/users',  'UsersApiController@index');
		Route::get('/users/{userID}/roles',  'RolesApiController@user_roles');
		Route::post('/users/{userID}/roles',  'RolesApiController@add_user_role');
		Route::delete('/role-user/{roleUserID}',  'RolesApiController@delete_user_role');
		Route::post('/role-user/{roleUserID}',  'RolesApiController@update_user_role');
		
		Route::resource('/members/{member_id}/ratings', 'RatingMemberApiController', ['only' => [
			'index', 'store', 'create'
		]]);

		Route::resource('/members', 'MembersApiController', ['only' => [
			'index', 'show', 'update'
		]]);
		Route::post('/members/email',  'MembersApiController@email'); // ability to send emails to the membership

		Route::resource('/achievements', 'AchievementsApiController', ['only' => [
			'index', 'show', 'update', 'destroy', 'store', 'delete'
		]]);
	});
});