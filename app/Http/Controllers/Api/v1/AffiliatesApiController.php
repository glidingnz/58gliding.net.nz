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

		if (!$affiliate = Affiliate::find($id))
		{
			return $this->not_found();
		}

		if (Gate::denies('club-admin', $affiliate->org_id)) 
		{
			return $this->denied("Sorry you aren't admin for this organisation");
		}


		if ($request->has('join_date'))
		{
			$affiliate->join_date = $request->get('join_date');
		}
		if ($request->has('end_date'))
		{
			$affiliate->end_date = $request->get('end_date');
		}
		if ($request->has('resigned_comment'))
		{
			$affiliate->resigned_comment = $request->get('resigned_comment');
		}
		if ($request->has('member_type_id'))
		{
			$affiliate->member_type_id = $request->get('member_type_id');
		}
		if ($affiliate->isDirty()) {
			$affiliate->save();
			return $this->success('Updated');
		}
		return $this->success('No Changes');
	}

}