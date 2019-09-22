<?php
namespace App\Http\Controllers\Apps;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Day;
use App\Models\Event;

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

	public function editDuties()
	{
		return view('calendar/duties-edit');
	}

	public function editRoster()
	{
		return view('calendar/roster-edit');
	}

}
