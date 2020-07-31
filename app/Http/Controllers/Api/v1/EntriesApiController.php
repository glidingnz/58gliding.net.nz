<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Api\ApiController;
use App\Models\Entry;
use App\Models\Member;

class EntriesApiController extends ApiController
{
	public function index(request $request)
	{
		$query = Entry::query();

		if ($request->exists('event_id'))
		{
			$query->where('event_id', $request->input('event_id'));
		}

		if ($entries = $query->paginate($request->input('per-page', 50)))
		{
			return $this->success($entries, TRUE);
		}
		return $this->error();
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($editcode)
	{
		if ($entry = Entry::where('editcode', $editcode)->with('event')->with('aircraft')->first())
		{
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

		// if given a GNZ number, get the member ID from it
		if ($input['gnz_number']) {
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

		if ($request->has('aircraft_id')) $entry->aircraft_id = $input['aircraft_id'];
		if ($request->has('mobile')) $entry->mobile = $input['mobile'];
		if ($request->has('entry_type')) $entry->entry_type = $input['entry_type'];
		if ($request->has('first_name')) $entry->first_name = $input['first_name'];
		if ($request->has('last_name')) $entry->last_name = $input['last_name'];
		if ($request->has('email')) $entry->email = $input['email'];
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
	* @return \Illuminate\Http\Response
	*/
	public function store(request $request)
	{
		$input = $request->all();


		$entry = new Entry;
		$entry->editcode = randomkeys(12);

		if ($request->exists('member_id')) $entry->member_id = $input['member_id'];

		if (!($request->exists('member_id') || $request->exists('first_name') || $request->exists('gnz_number')))
		{
			return $this->error('A GNZ number, name or selected member is required');
		}
		if (!$request->exists('mobile')) {
			return $this->error('A mobile number is required');
		}
		if (!$request->exists('eventId')) {
			return $this->error('An eventId is required');
		}

		// if given a GNZ number, get the member ID from it
		if ($input['gnz_number']) {
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

		if ($request->has('mobile')) $entry->mobile = $input['mobile'];
		if ($request->has('entry_type')) $entry->entry_type = $input['entry_type'];
		if ($request->has('first_name')) $entry->first_name = $input['first_name'];
		if ($request->has('last_name')) $entry->last_name = $input['last_name'];
		if ($request->has('email')) $entry->email = $input['email'];
		if ($request->has('eventId')) $entry->event_id = $input['eventId'];
		$entry->entry_status='definite';
		$entry->catering_breakfasts='none';
		$entry->catering_lunches='none';
		$entry->catering_dinners='none';

		if ($entry->save())
		{
			return $this->success($entry);
		}
		return $this->error();
	}
}
