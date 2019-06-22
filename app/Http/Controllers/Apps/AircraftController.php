<?php
namespace App\Http\Controllers\Apps;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Aircraft;
use App\Classes\LoadAircraft;

class AircraftController extends Controller
{
	public function index()
	{
		return view('aircraft/aircraft-list');
	}

	public function view($rego)
	{
		// load aircraft
		if ($aircraft = Aircraft::where('rego', $rego)->first())
		{
			return view('aircraft/aircraft-view', $aircraft);
		}
		abort(404);
	}

	public function edit($rego)
	{
		// load aircraft
		if ($aircraft = Aircraft::where('rego', $rego)->first())
		{
			return view('aircraft/aircraft-edit', $aircraft);
		}
		abort(404);
	}


	public function fleet(Request $request)
	{
		$org = Array('org' => $request->attributes->get('org'));
		return view('aircraft/fleet-list', $org);
	}

}
