<?php
namespace App\Http\Controllers\Apps;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Day;

class CalendarController extends Controller
{
	public function index()
	{
		return view('calendar/calendar');
	}

	public function edit()
	{
		return view('calendar/calendar-edit');
	}

}
