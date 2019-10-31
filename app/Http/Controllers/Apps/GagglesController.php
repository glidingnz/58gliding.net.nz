<?php
namespace App\Http\Controllers\Apps;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Requests;
// use App\Models\Aircraft;
// use App\Classes\LoadAircraft;

class GagglesController extends Controller
{
	public function index()
	{
		return view('aircraft/gaggles');
	}


}
