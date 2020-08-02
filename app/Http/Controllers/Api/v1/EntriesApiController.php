<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use App\Http\Requests;
use App\Http\Controllers\Api\ApiController;
use App\Models\Entry;
use App\Models\Member;
use App\Models\Event;
use App\Mail\EnterEvent;
use Auth;

class EntriesApiController extends ApiController
{
	public function index(request $request)
	{
		$query = Entry::query();

		if ($request->exists('event_id'))
		{
			$query->where('event_id', $request->input('event_id'));
		}

		if ($request->exists('cancelled') && $request->input('cancelled')=='false')
		{
			$query->where('entry_status', '<>', 'cancelled');
			$query->where('entry_status', '<>', 'started');
		}


		$query->with('aircraft')->with('contestClass');

		if ($entries = $query->paginate($request->input('per-page', 50)))
		{
			foreach ($entries AS $entry) {
				if ($entry->canEdit()) {
					$entry->showDetails();
				}


			}
			return $this->success($entries, TRUE);
		}
		return $this->error();
	}

	/**
	 * Fetch the specified resource.
	 * If given an edit code
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function showCode($editcode)
	{
		if ($entry = Entry::where('editcode', $editcode)->with('event')->with('aircraft')->first())
		{
			// assume anyone with the edit code has permissions to see everything
			$entry->showDetails();

			return $this->success($entry);
		}
		return $this->error();
	}

	/**
	 * Fetch the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		if ($entry = Entry::where('id', $id)->with('event')->with('aircraft')->first())
		{
			// check this user can show the details
			if ($entry->canEdit())
			{
				$entry->showDetails();
			}

			return $this->success($entry);
		}
		return $this->error();
	}


	/**
	* Update the specified resource.
	*
	* @param  int  $id
	* @return \Illuminate\Http\Response
	*/
	public function update(request $request, $editcode)
	{
		$input = $request->all();

		if (!$entry = Entry::where('editcode', $editcode)->first())
		{
			return $this->not_found();
		}

		if ($request->exists('member_id')) $entry->member_id = $input['member_id'];
		if (!($request->exists('member_id') || $request->exists('first_name') || $request->exists('gnz_number')))
		{
			return $this->error('A GNZ number, name or selected member is required');
		}
		if (!$request->exists('mobile')) {
			return $this->error('A mobile number is required');
		}

		if ($request->has('first_name')) $entry->first_name = $input['first_name'];
		if ($request->has('last_name')) $entry->last_name = $input['last_name'];
		if ($request->has('email')) $entry->email = $input['email'];
		if ($request->has('mobile')) $entry->mobile = $input['mobile'];

		// if given a GNZ number, get the member ID from it
		if ($request->exists('gnz_number')) {
			if ($member = Member::where('nzga_number', $input['gnz_number'])->first())
			{
				// use that ID instead of whatever was provided, as we shouldn't have got one from someone not logged in
				$entry->member_id = $member->id;
			}
			else
			{
				return $this->error('Member #' . $input['gnz_number'] . ' Not Found');
			}
		}


		// get member details
		if ($request->has('member_id') && $request->input('gnz_member', false))
		{
			if ($member = Member::where('id', $request->input('member_id'))->first())
			{
				// copy basic details over so we don't have to get the member details that has permission issues.
				$entry->first_name = $member->first_name;
				$entry->last_name = $member->last_name;
				// $entry->mobile = $member->mobile; // can't get these for privacy reasons!
				// $entry->email = $member->email; // should be correct anyway...
			}
		}



		if ($request->has('gnz_member')) $entry->gnz_member = $input['gnz_member'];
		if ($request->has('aircraft_id')) $entry->aircraft_id = $input['aircraft_id'];
		if ($request->has('wingspan')) $entry->wingspan = $input['wingspan'];
		if ($request->has('winglets')) $entry->winglets = $input['winglets'];
		if ($request->has('handicap')) $entry->handicap = $input['handicap'];
		if ($request->has('class_id')) $entry->class_id = $input['class_id'];
		if ($request->has('entry_type')) $entry->entry_type = $input['entry_type'];
		if ($request->has('catering_breakfasts')) $entry->catering_breakfasts = $input['catering_breakfasts'];
		if ($request->has('catering_lunches')) $entry->catering_lunches = $input['catering_lunches'];
		if ($request->has('catering_dinners')) $entry->catering_dinners = $input['catering_dinners'];
		if ($request->has('crew_name')) $entry->crew_name = $input['crew_name'];
		if ($request->has('crew_mobile')) $entry->crew_mobile = $input['crew_mobile'];
		if ($request->has('car_plate')) $entry->car_plate = $input['car_plate'];
		if ($request->has('car_details')) $entry->car_details = $input['car_details'];
		if ($request->has('catering_final_dinner')) $entry->catering_final_dinner = $input['catering_final_dinner'];
		if ($request->has('catering_notes')) $entry->catering_notes = $input['catering_notes'];
		if ($request->has('signed')) $entry->signed = $input['signed'];
		if ($request->has('aircraft_id')) $entry->aircraft_id = $input['aircraft_id'];
		if ($request->has('entry_status')) $entry->entry_status = $input['entry_status'];
		if ($request->has('emergency_contact')) $entry->emergency_contact = $input['emergency_contact'];
		if ($request->has('emergency_mobile')) $entry->emergency_mobile = $input['emergency_mobile'];
		if ($request->has('emergency_phone')) $entry->emergency_phone = $input['emergency_phone'];
		if ($request->has('emergency_email')) $entry->emergency_email = $input['emergency_email'];
		if ($request->has('emergency_relationship')) $entry->emergency_relationship = $input['emergency_relationship'];

		if ($entry->save())
		{
			return $this->success($entry);
		}
		return $this->error();
	}


	/**
	* Create the specified resource.
	*
	* @param  int  $id
	* @return \Illuminate\ Http\Response
	*/
	public function store(request $request)
	{
		$input = $request->all();

		if (!($request->has('email') && $request->input('email')!=null)) return $this->error('Your email address is required so we can email you a link to edit your entry');

		$entry = new Entry;
		$entry->editcode = randomkeys(12);

		if ($request->has('entry_type')) $entry->entry_type = $input['entry_type'];
		if ($request->has('eventId')) $entry->event_id = $input['eventId'];
		if ($request->has('email')) $entry->email = $input['email'];
		$entry->entry_status = 'started';

		// set the member ID to be the currently logged in member if we are logged in
		if ($user = Auth::user())
		{
			$entry->user_id = $user->id;

			if ($user->gnz_id!=null)
			{
				// get the members mobile phoner
				if ($member = Member::where('nzga_number', $user->gnz_id)->first())
				{
					$entry->member_id = $member->id;
					$entry->mobile = $member->mobile_phone;
				}
			}
		}

		if ($entry->save())
		{
			$entry->showDetails(); // ensure we share all the details as this user just created it

			// send the email
			if ($event = Event::find($entry->event_id))
			{
				Mail::to($entry->email)->send(new EnterEvent($event, $entry));
			}

			return $this->success($entry);
		}
		return $this->error();
	}
}
