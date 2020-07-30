<?php
namespace App\Http\Controllers\Apps;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Event;
use App\Models\Entry;
use App\Models\Member;
use Auth;

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

	public function enterEvent($slug)
	{
		$user = Auth::user();

		// load the member ID so we can automatically select the current GNZ member if available
		$member_id = null;
		if ($user && $member = Member::where('nzga_number', $user->gnz_id)->first())
		{
			//echo $member->id; exit();
			$member_id = $member->id;
		}

		// load the event from the slug
		if ($event = Event::where('slug', $slug)->first())
		{
			return view('events/event-enter', array('event_id'=>$event->id, 'member_id'=>$member_id));
		}
		abort(404);
	}


	public function editEntry($editcode)
	{
		// load the event from the slug
		if ($entry = Entry::where('editcode', $editcode)->with('event')->first())
		{
			return view('events/entry-edit', array('entry'=>$entry));
		}
		abort(404);
	}
}
