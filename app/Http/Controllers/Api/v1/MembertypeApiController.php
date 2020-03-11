<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Api\ApiController;
use App\Models\Membertype;
use Auth;

class MembertypeApiController extends ApiController
{
	public function index(Request $request)
	{
		$query = Membertype::query()->orderBy('name', 'desc');

		// limit by organisation
		if ($request->has('org_id'))
		{
			$query->where('org_id','=',$request->input('org_id'));
		}

		if ($membertypes = $query->get())
		{
			return $this->success($membertypes);
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
		if ($membertype = Membertype::find($id))
		{
			return $this->success($membertype);
		}
		return $this->error();
	}



	/**
	* Destroy an a membertype.
	*
	* @param  int  $id
	* @return \Illuminate\Http\Response
	*/
	public function destroy(Request $request, $membertype_id)
	{
		if ($membertype = Membertype::find($membertype_id))
		{
			if ($membertype->delete())
			{
				return $this->success();
			}
		}
		return $this->error();
	}


	/**
	* Create a membertype.
	*
	* @param  int  $id
	* @return \Illuminate\Http\Response
	*/
	public function store(Request $request)
	{
		$input = $request->all();
		if (!$request->input('name')) return $this->error('Name is required');

		// create a slug if one doesn't exist. Will use 'sluggable' trait to set the slug
		$slug = $request->input('slug', $request->input('name', ''));

		$membertype = new Membertype;

		$membertype->name = $request->input('name');
		$membertype->slug = $slug;
		$membertype->org_id = $request->input('org_id', null);
		$membertype->save();

		return $this->success();
	}
}
