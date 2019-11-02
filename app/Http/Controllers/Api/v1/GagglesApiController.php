<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Api\ApiController;
use App\Models\Gaggle;
use Auth;

class GagglesApiController extends ApiController
{
	public function index(Request $request)
	{
		$query = Gaggle::query()->orderBy('created_at', 'desc');

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

		if ($gaggles = $query->get())
		{
			return $this->success($gaggles);
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
		if ($gaggle = Gaggle::find($id))
		{
			return $this->success($gaggle);
		}
		return $this->error();
	}


	/**
	* Create a gaggle.
	*
	* @param  int  $id
	* @return \Illuminate\Http\Response
	*/
	public function store(Request $request)
	{
		$input = $request->all();

		// create a slug if one doesn't exist
		$slug = $request->input('slug', $request->input('name', ''));

		$gaggle = Gaggle::create($input);

		$gaggle->slug = $slug;
		$gaggle->user_id = Auth::user()->id;
		$gaggle->org_id = $request->input('org_id', null);
		$gaggle->save();

		return $this->success($gaggle);
	}
}
