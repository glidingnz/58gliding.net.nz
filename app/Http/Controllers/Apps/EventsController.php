<?php
namespace App\Http\Controllers\Apps;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Event;

class EventsController extends Controller
{
	public function index()
	{
		return view('events/events');
	}

	public function editEvent($slug)
	{
		// load the event from the slug
		if ($event = Event::where('slug', $slug)->first())
		{
			return view('events/event-edit', $event);
		}
		abort(404);
	}

	public function viewEvent($slug)
	{
		// load the event from the slug
		if ($event = Event::where('slug', $slug)->first())
		{
			return view('events/event-view', $event);
		}
		abort(404);
	}

}
