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

		if ($entries = $query->get())
		{
			return $this->success($entries);
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
		if ($entry = Entry::where('editcode', $editcode)->with('event')->first())
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

		if ($request->exists('memberId')) $entry->member_id = $input['memberId'];

		if (!($request->exists('memberId') || $request->exists('first_name') || $request->exists('gnzNumber')))
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
		if ($input['gnzNumber']) {
			if ($member = Member::where('nzga_number', $input['gnzNumber'])->first())
			{
				// use that ID instead of whatever was provided, as we shouldn't have got one from someone not logged in
				$entry->member_id = $member->id;
			}
			else
			{
				return $this->error('Member #' . $input['gnzNumber'] . ' Not Found');
			}
		}

		if ($request->has('mobile')) $entry->mobile = $input['mobile'];
		if ($request->has('entry_type')) $entry->entry_type = $input['entry_type'];
		if ($request->has('first_name')) $entry->first_name = $input['first_name'];
		if ($request->has('last_name')) $entry->last_name = $input['last_name'];
		if ($request->has('email')) $entry->email = $input['email'];
		if ($request->has('eventId')) $entry->event_id = $input['eventId'];


		if ($entry->save())
		{
			return $this->success($entry);
		}
		return $this->error();
	}
}
