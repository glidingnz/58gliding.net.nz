<?php
namespace App\Http\Controllers\Apps;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Waypoint;
use App\Classes\WaypointsLibrary;
use Form;

class WaypointsController extends Controller
{
	public function index()
	{
		return view('waypoints/waypoints-list');
	}

	public function upload(Request $request)
	{

		$wp_lib = new WaypointsLibrary();

		$path = $request->file('waypoints')->store('waypoints');
		$waypoints = $wp_lib->process_cup_file($path);

		foreach ($waypoints AS $waypoint)
		{
			$waypoint->save();
		}

		return redirect('waypoints');
	}

	// public function view($rego)
	// {
	// 	// load aircraft
	// 	if ($aircraft = Aircraft::where('rego', $rego)->first())
	// 	{
	// 		return view('aircraft/aircraft-view', $aircraft);
	// 	}
	// 	abort(404);
	// }

	// public function edit($rego)
	// {
	// 	// load aircraft
	// 	if ($aircraft = Aircraft::where('rego', $rego)->first())
	// 	{
	// 		return view('aircraft/aircraft-edit', $aircraft);
	// 	}
	// 	abort(404);
	// }


	// public function fleet(Request $request)
	// {
	// 	$org = Array('org' => $request->attributes->get('org'));
	// 	return view('aircraft/fleet-list', $org);
	// }

}
