<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Api\ApiController;
use App\Models\Fleet;
use Auth;

class FleetsApiController extends ApiController
{
	public function index(Request $request)
	{
		$query = Fleet::query()->orderBy('created_at', 'desc');

		// limit by organisation
		if ($request->has('org_id'))
		{
			$query->where('org_id','=',$request->input('org_id'));
		}
		
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
		if ($fleet = Fleet::find($id))
		{
			return $this->success($fleet);
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
