<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Api\ApiController;
use App\Models\Gaggle;

class GagglesApiController extends ApiController
{
	public function index(request $request)
	{
		$query = Gaggle::query();

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
	public function post($id)
	{
		$input = $request->all();

		// create a slug if one doesn't exist
		$slug = $request->input('slug', $request->input('name', ''));

		$event = Event::create($input);

		$event->slug = $this->create_unique_slug($slug);

		// default the end date to the start date unless given otherwise
		$event->end_date = $request->input('end_date', $request->input('start_date', null));
		$event->type = $request->input('type', $request->input('type', 'other'));
		if ($request->input('org_id')==null) {
			$event->org_id = $slug;
			$gnz_org = Org::where('slug', 'gnz')->first();
			$event->org_id = $gnz_org->id;
		}
		$event->creator_user_id = Auth::user()->id;
		$event->save();

		return $this->sendResponse($event->toArray(), 'Event Created');
	}
}
