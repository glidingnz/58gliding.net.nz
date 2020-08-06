<?php
namespace App\Http\Controllers\Apps;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Day;
use App\Models\Event;
use App\Models\Org;
use Carbon\Carbon;

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

	/**
	 * Get the bare minimum needed to render out a list of events.
	 * We can't use Vue here, because the file is too big to embed into another website.
	 * Instead we'll do it the old fashioned way, get a list of data and output it.
	 */
	public function embedCal(Request $request)
	{
		$timezone = new \DateTimeZone($request->input('timezone', 'Pacific/Auckland'));
		
		$query = Event::query()->with('org');

		$org = \Request::get('_ORG');

		// limit by organisation
		if ($org)
		{
			// filter to either the selected organisation OR 'featured' events
			$query->where(function($query) use ($request, $org) {

				$query->where('org_id','=',$org->id);
				$query->orWhere('featured','=', true);
			});
		}


		if ($request->has('timerange'))
		{
			$todaysDate = Carbon::today($timezone);
			$todaysDate->setTimezone('UTC');

			switch ($request->input('timerange'))
			{
				case 'future':
					$query->where('end_date', '>=', $todaysDate->format('Y-m-d'));
					break;
				case 'past':
					$query->where('end_date', '<', $todaysDate->format('Y-m-d'));
					break;
			}
		}


		if ($request->has('type') && $request->input('type')!='all') {
			$query->where('type', $request->input('type'));
		}

		$query->orderBy('start_date');

		if ($data['events'] = $query->get())
		{
			// generate nice display start/end dates/times
			foreach ($data['events'] AS $key=>$result)
			{
				$start_date = Carbon::createFromFormat('Y-m-d', substr($result['start_date'], 0, 10))->format('jS M Y');
				$end_date = Carbon::createFromFormat('Y-m-d', substr($result['end_date'], 0, 10))->format('jS M Y');

				$data['events'][$key]['nice_start_date'] = $start_date;
				$data['events'][$key]['nice_end_date'] = $end_date;

				//$results[$key]->can_edit = $result->can_edit;
			}

			return view('calendar/embed-cal', $data);
		}

		return $this->sendError('Events not loaded');
		
	}

}
