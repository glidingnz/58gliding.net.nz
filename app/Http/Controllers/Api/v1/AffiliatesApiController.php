<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests;
use App\Models\Org;
use App\Models\Member;
use App\Models\Affiliate;
use Gate;

class AffiliatesApiController extends ApiController
{

	
	/**
	 * Update an affiliate
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{

		if (!$affiliate = Affiliate::where('id', $id)->with('org')->first())
		{
			return $this->not_found();
		}

		if (Gate::denies('club-admin', $affiliate->org)) 
		{
			return $this->denied("Sorry you aren't admin for " . $affiliate->org->name);
		}


		if ($request->has('join_date'))
		{
			$affiliate->join_date = $request->get('join_date');
		}
		if ($request->has('end_date'))
		{
			$affiliate->end_date = $request->get('end_date');
			$affiliate->end_date!=null ? $affiliate->resigned=true : $affiliate->resigned=false;
		}
		if ($request->has('resigned'))
		{
			$affiliate->resigned=$request->get('resigned');
		}
		if ($request->has('resigned_comment'))
		{
			$affiliate->resigned_comment = $request->get('resigned_comment');
		}
		if ($request->has('membertype_id'))
		{
			$affiliate->membertype_id = $request->get('membertype_id');
		}
		if ($affiliate->isDirty()) {
			$affiliate->save();
			return $this->success('Updated');
		}
		return $this->success('No Changes');
	}

	

	public function store(Request $request)
	{

		$affiliate = new Affiliate();

		if ($request->has('org_id'))
		{
			$affiliate->org_id = $request->get('org_id');
		} else {
			return $this->error("An org_id is required");
		}

		if (Gate::denies('club-admin', $affiliate->org)) 
		{
			return $this->denied("Sorry you don't have permission to add a member to this organisation");
		}

		if ($request->has('member_id'))
		{
			$affiliate->member_id = $request->get('member_id');
		} else {
			return $this->error("A member_id is required");
		}

		// member type not required. Can be blank
		if ($request->has('membertype_id'))
		{
			$affiliate->membertype_id = $request->get('membertype_id');
		}

		if ($request->has('join_date'))
		{
			$affiliate->join_date = $request->get('join_date');
		}

		if ($affiliate->save())
		{
			return $this->success('Affiliate Created');
		}



		return $this->error('Something went wrong, affiliate not created');
	}


	public function delete(Request $request, $id)
	{

	}

}