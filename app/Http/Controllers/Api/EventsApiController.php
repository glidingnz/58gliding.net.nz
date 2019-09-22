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

		$event = Event::create($input);

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
