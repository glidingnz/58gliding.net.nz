<?php
namespace App\Http\Controllers\Apps;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Fleet;
use Gate;

class TimesheetsController extends Controller
{
	public function index()
	{
		if (Gate::allows('experimental-features'))
		{
			return view('timesheets/timesheets');
		}
		return redirect('/');
	}

	public function edit($id)
	{
		if (Gate::allows('experimental-features'))
		{
			return view('timesheets/timesheet-edit');
		}
		return redirect('/');
	}
}
