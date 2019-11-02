<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Api\ApiController;
use App\Models\Fleet;
use App\Models\Aircraft;
use Auth;

class FleetsApiController extends ApiController
{
	public function index(Request $request)
	{
		$query = Fleet::query()->with('org')->orderBy('created_at', 'desc');

		// limit by organisation
		if ($request->has('org_id'))
		{
			$query->where('org_id','=',$request->input('org_id'));
		}
		

		if ($fleets = $query->get())
		{
			return $this->success($fleets);
		}
		return $this->error();
	}


	/**
	* Display the specified resource.
	*
	* @param  int  $id
	* @return \Illuminate\Http\Response
	*/
	public function show($id)
	{
		if ($fleet = Fleet::with('aircraft')->find($id))
		{
			return $this->success($fleet);
		}
		return $this->error();
	}


	/**
	* Add an aircraft to a fleet.
	*
	* @param  int  $id
	* @return \Illuminate\Http\Response
	*/
	public function add(Request $request, $fleet_id)
	{
		if ($fleet = Fleet::find($fleet_id))
		{
			// check the aircraft exists
			if ($request->has('aircraft_id'))
			{
				$aircraft = Aircraft::find($request->input('aircraft_id'));
				if ($fleet->aircraft()->save($aircraft))
				{
					return $this->success();
				}
			}
		}
		return $this->error();
	}


	/**
	* Detatch an aircraft from a fleet.
	*
	* @param  int  $id
	* @return \Illuminate\Http\Response
	*/
	public function remove(Request $request, $fleet_id)
	{
		if ($fleet = Fleet::find($fleet_id))
		{
			// check the aircraft exists
			if ($request->has('aircraft_id'))
			{
				$aircraft = Aircraft::find($request->input('aircraft_id'));
				if ($fleet->aircraft()->detach($aircraft))
				{
					return $this->success();
				}
			}
		}
		return $this->error();
	}


	/**
	* Create a fleet.
	*
	* @param  int  $id
	* @return \Illuminate\Http\Response
	*/
	public function store(Request $request)
	{
		$input = $request->all();

		// create a slug if one doesn't exist
		$slug = $request->input('slug', $request->input('name', ''));

		$fleet = Fleet::create($input);

		$fleet->slug = $slug;
		$fleet->user_id = Auth::user()->id;
		$fleet->org_id = $request->input('org_id', null);
		$fleet->save();

		return $this->success($fleet);
	}
}
