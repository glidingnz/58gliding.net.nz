<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;
use App\Models\Event;

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
		$query = Event::query();

		// limit by organisation
		if ($request->has('org_id')) $query->where('org_id','=',$request->input('org_id'));

		// needs to get start and ends correctly for time ranges
		if ($request->has('start_date')) $query->where('end_date', '>=', $request->input('start_date'));
		//if ($request->has('end_date')) $query->where('day_date', '<=', $request->input('end_date'));

		$query->orderBy('start_date');

		if ($results = $query->get())
		{
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
		$event->slug = $slug;
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
		$event = Event::find($id);

		if (!$event) {
			return $this->sendError('Event not found');
		}

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

		$event->update($input, $id);

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
