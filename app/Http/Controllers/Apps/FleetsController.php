<?php
namespace App\Http\Controllers\Apps;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Fleet;
// use App\Classes\LoadAircraft;

class FleetsController extends Controller
{
	public function index()
	{
		return view('aircraft/fleets');
	}

	public function edit($slug)
	{
		// load the event from the slug
		if ($fleet = Fleet::where('slug', $slug)->first())
		{
			return view('aircraft/fleet-edit', $fleet);
		}
		abort(404);
	}


}
