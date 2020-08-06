<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Api\ApiController;
use App\Models\ContestClass;
use App\Models\ClassEvent; // links a contestclass to an event

class ClassesApiController extends ApiController
{
	/**
	 * Get list of classes available to all competitions
	 */
	public function index(Request $request)
	{
		$query = ContestClass::query();
		$query->orderBy('name');

		if ($classes = $query->get())
		{
			return $this->success($classes);
		}
		return $this->error();
	}



	/**
	 * Fetch class/event Links for an event
	 */
	public function event(Request $request, $event_id)
	{
		$query;

		if ($class_events = ClassEvent::where('event_id', $event_id)->with('contestClass')->get())
		{
			return $this->success($class_events);
		}
		return $this->error();
	}


	/**
	 * Link a class to an event
	 */
	public function link(Request $request, $class_id)
	{
		$class_event = new ClassEvent();
		$class_event->class_id = (integer)$class_id;
		$class_event->event_id = $request->input('id');

		if ($class_event->save())
		{
			return $this->success($class_event);
		}
		return $this->error();
	}


	/**
	 * Unlink a class to an event
	 */
	public function unlink(Request $request, $class_id)
	{
		if (ClassEvent::where('class_id', (integer)$class_id)->where('event_id', $request->input('id'))->delete())
		{
			return $this->success();
		}
		return $this->error();
	}

}
