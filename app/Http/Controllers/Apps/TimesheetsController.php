<?php
namespace App\Http\Controllers\Apps;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Fleet;
// use App\Classes\LoadAircraft;

class TimesheetsController extends Controller
{
	public function index()
	{
		return view('timesheets/timesheets');
	}
}
