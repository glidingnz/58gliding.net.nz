<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Api\ApiController;
use App\Models\Entry2;

class EntriesApiController extends ApiController
{
	public function index(request $request)
	{
		$query = Entry2::query();

		if ($entries = $query->get())
		{
			return $this->success($entries);
		}
		return $this->error();
	}

	/**
	* Create the specified resource.
	*
	* @param  int  $id
	* @return \Illuminate\Http\Response
	*/
	public function create()
	{
		$input = $request->all();


		if (1)
		{
			return $this->success();
		}
		return $this->error();
	}
}
