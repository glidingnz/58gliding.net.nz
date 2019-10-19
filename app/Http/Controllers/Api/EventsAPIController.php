<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;
use App\Models\Event;
use App\Models\Org;
use Auth;
use Carbon\Carbon;

/**
 * Class eventsController
 * @package App\Http\Controllers\API
 */

class EventsAPIController extends AppBaseController
{

	/**
	 * Display a listing of the events.
	 * GET|HEAD /events
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function index(Request $request)
	{
		$query = Event::query()->with('org');
		$gnz = Org::where('slug', 'gnz')->first();

		// limit by organisation
		if ($request->has('org_id'))
		{
			// filter to either the selected organisation OR 'featured' events
			$query->where(function($query) use ($request, $gnz) {

				$query->where('org_id','=',$request->input('org_id'));

				if ($request->input('other', true)==='true') {
					$query->orWhere('featured','=', true);
				}
			});
		}


		if ($request->input('gnz', true)==='false') {
			$query->where('org_id', '!=', $gnz->id);
		}


		if ($request->has('type') && $request->input('type')!='all') {
			$query->where('type', $request->input('type'));
		}

		if ($request->has('timerange'))
		{
			$todaysDate = Carbon::today($this->timezone);
			$todaysDate->setTimezone('UTC');

			switch ($request->input('timerange'))
			{
				case 'future':
					$query->where('start_date', '>=', $todaysDate->format('Y-m-d'));
					break;
				case 'past':
					$query->where('end_date', '<', $todaysDate->format('Y-m-d'));
					break;
			}
		}



		// needs to get start and ends correctly for time ranges
		if ($request->has('start_date')) $query->where('end_date', '>=', $request->input('start_date'));
		//if ($request->has('end_date')) $query->where('day_date', '<=', $request->input('end_date'));

		$query->orderBy('start_date');

		if ($results = $query->get())
		{
			foreach ($results AS $key=>$result)
			{
				$results[$key]->can_edit = $result->can_edit;
			}

			// check if we are outputting ical or not
			if ($request->has('ical'))
			{
				$this->getEventsICalObject($results);
			}
			else
			{
				return $this->success($results);
			}
			
		}
		
		return $this->sendError('Events not found');
	}

	/**
	 * Store a newly created event in storage.
	 * POST /events
	 *
	 * @param $request
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$input = $request->all();

		// create a slug if one doesn't exist
		$slug = $request->input('slug', $request->input('name', ''));

		if ($request->has('start_date')) {
			$slug .= '-' . Carbon::createFromFormat('Y-m-d', $request->input('start_date'))->format('M-Y');
		}

		$event = Event::create($input);

		$event->slug = $this->create_unique_slug($slug);

		// default the end date to the start date unless given otherwise
		$event->end_date = $request->input('end_date', $request->input('start_date', null));
		$event->type = $request->input('type', $request->input('type', 'other'));
		if ($request->input('org_id')==null) {
			$event->org_id = $slug;
			$gnz_org = Org::where('slug', 'gnz')->first();
			$event->org_id = $gnz_org->id;
		}
		$event->creator_user_id = Auth::user()->id;
		$event->save();

		return $this->sendResponse($event->toArray(), 'Event Created');
	}



	public function create_unique_slug($slug)
	{
		
		$slug = simple_string(strtolower($slug));

		// count the number of times this slug has already been used
		$slug_matches = Event::where('slug', 'like', $slug . '-%')->orWhere('slug', $slug)->get();
		// figure out the biggest
		$biggest = 0;
		foreach ($slug_matches AS $slug_match)
		{
			$biggest=1;
			preg_match("/^.*\-([0-9]*)$/", $slug_match['slug'], $matches);
			if (sizeof($matches)>0)
			{
				$found = (int)$matches[1];
				if ($found > $biggest) $biggest = $found;
			}
		}

		if ($biggest>0)
		{
			$slug_before_number = $slug;
			preg_match("/^(.*)\-[0-9]*$/", $slug, $matches2);
			if (sizeof($matches2)>0) {
				$slug_before_number = $matches2[1];
			}
			$biggest = $biggest + 1;
			$slug = $slug_before_number . '-' . $biggest;
		}

		return $slug;
	}


	/**
	 * Display the specified events.
	 * GET|HEAD /events/{id}
	 *
	 * @param int $id
	 *
	 * @return Response
	 */
	public function show($id)
	{
		/** @var events $events */
		$event = Event::with('org')->find($id);

		if (!$event) {
			return $this->sendError('Event not found');
		}
		$event->can_edit = $event->can_edit;

		return $this->sendResponse($event->toArray(), 'Event retrieved successfully');
	}

	/**
	 * Update the specified events in storage.
	 * PUT/PATCH /events/{id}
	 *
	 * @param int $id
	 * @param $request
	 *
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		$input = $request->all();

		/** @var events $events */
		$event = Event::find($id);

		if (!$event) {
			return $this->sendError('Event not found');
		}

		$event->fill($input);

		if ($event->isDirty('slug')) {
			$event->slug = $this->create_unique_slug($event->slug);
		}
		
		$event->save();

		return $this->sendResponse($event->toArray(), 'Event updated successfully');
	}




	/**
	 * Remove the specified events from storage.
	 * DELETE /events/{id}
	 *
	 * @param int $id
	 *
	 * @throws \Exception
	 *
	 * @return Response
	 */
	public function destroy($id)
	{
		/** @var events $events */
		$event = Event::find($id);

		if (!$event) {
			return $this->sendError('Event not found');
		}

		$event->delete();

		return $this->sendResponse($id, 'Event deleted successfully');
	}

	/**
		* Gets the events data from the database
		* and populates the iCal object.
		*
		* @return void
		*/
		public function getEventsICalObject($events)
		{
			define('ICAL_FORMAT', 'Ymd\THis\Z');
			define('ICAL_DATE_FORMAT', 'Ymd');

			$icalObject = "BEGIN:VCALENDAR\n" .
			"VERSION:2.0\n" .
			"METHOD:PUBLISH\n" .
			"PRODID:-//Charles Oduk//Tech Events//EN\n";

			// loop over events
			foreach ($events as $event) {



				$uuid = md5($event->id . $event->created_at);

				// check if this is a single day event or not
				if ($event->start_date==$event->end_date || $event->end_date==null) {
					// single day event. Should get time as well
					$start_date = 'DTSTART:' . date(ICAL_FORMAT, strtotime($event->start_date));
					$end_date = 'DTEND:' . date(ICAL_FORMAT, strtotime($event->start_date));
				}
				else
				{
					$end_date_plus_one = Carbon::instance($event->end_date)->add(1, 'day')->format(ICAL_DATE_FORMAT);
					$start_date = 'DTSTART;VALUE=DATE:' . date(ICAL_DATE_FORMAT, strtotime($event->start_date));
					$end_date = 'DTEND;VALUE=DATE:' . $end_date_plus_one;
					// multi day event
				}

				// create a URL for the description text
				$url = env('APP_DOMAIN') . '/events/' . $event->slug;

				// create a summary from the name
				$summary = $event->name;

				// add appropriate things if this is linking directly to a specific organisation's event
				if ($event->org)
				{
					$url = $event->org->slug . '.' . $url;
					
					if ($_SERVER['HTTPS']) $url = 'https://' . $url;
					else $url = 'http://' . $url;

					$summary = '('.$event->org->short_name.') ' . $summary;
				}

				$icalObject .=
				"BEGIN:VEVENT\n" .
				"" . $start_date . "\n" .
				"" . $end_date . "\n" .
				"DTSTAMP:" . date(ICAL_FORMAT, strtotime($event->created_at)) . "\n" .
				"SUMMARY:".$summary . "\n" .
				"UID:".$uuid . '@' . env('APP_DOMAIN') . "\n" .
				"DESCRIPTION:" . $url . "\n" .
				"STATUS:CONFIRMED" . "\n" .
				"LAST-MODIFIED:" . date(ICAL_FORMAT, strtotime($event->updated_at)) . "\n" .
				"LOCATION:" . $event->location  . "\n" .
				"END:VEVENT\n";
			}

			// close calendar
			$icalObject .= "END:VCALENDAR";

			// Set the headers
			header('Content-type: text/calendar; charset=utf-8');
			header('Content-Disposition: attachment; filename="cal.ics"');
		  
			//$icalObject = str_replace(' ', '', $icalObject);

			echo $icalObject;
			exit();
		}
}
