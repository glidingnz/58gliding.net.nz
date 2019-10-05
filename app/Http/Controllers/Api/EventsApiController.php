<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;
use App\Models\Event;
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

		// limit by organisation
		if ($request->has('org_id'))
		{
			if ($request->input('org_id')!='gnz')
			{
				$query->where('org_id','=',$request->input('org_id'));
			}
			else
			{
				$query->whereNull('org_id');
			}
		}

		if ($request->has('featured'))
		{
			$query->where(function ($query) use ($request) {
				$query->where('featured','=',(boolean)$request->input('featured', true));
			});
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
			return $this->success($results);
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

		$slug = simple_string(strtolower($slug));

		// count the number of times this slug has already been used
		$slug_matches = Event::where('slug', 'like', $slug . '-%')->orWhere('slug', $slug)->get();
		// figure out the biggest
		$biggest = 0;
		foreach ($slug_matches AS $slug_match)
		{
			$biggest=1;
			$match = preg_match("/^.*\-([0-9]*)$/", $slug_match['slug'], $matches);
			if (sizeof($matches)>0)
			{
				$found = (int)$matches[1];
				if ($found > $biggest) $biggest = $found;
			}
		}
		if ($biggest>0)
		{
			$biggest = $biggest + 1;
			$slug = $slug . '-' . $biggest;
		}

		$event = Event::create($input);

		// default the end date to the start date unless given otherwise
		$event->end_date = $request->input('end_date', $request->input('start_date', null));
		$event->type = $request->input('type', $request->input('type', 'other'));
		$event->slug = $slug;
		$event->creator_user_id = Auth::user()->id;
		$event->save();

		return $this->sendResponse($event->toArray(), 'Event Created');
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
}
