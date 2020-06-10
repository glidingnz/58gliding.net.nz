<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Api\ApiController;
use App\Models\Member;
use App\Models\BadgeMember;
use App\Classes\BadgeImporter;
use App\Models\Badge;

use Gate;
use Auth;

class AchievementsApiController extends ApiController
{

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{

		// Get the member
		if ($request->has('member_id'))
		{
			$member = Member::where('id', $request->input('member_id'))->first();
		}

		if (!isset($member)) return $this->not_found();

		$query = BadgeMember::query();
		$query->with('badge');
		$query->leftJoin('badges', 'badges.id', '=', 'badge_member.badge_id');

		// Filter to member if given
		if ($request->has('member_id'))
		{
			$query->where(function($query) use ($request, $member)  {
				$query->where('member_id','=',$member->id);
			});
		}

		$query->orderBy('badges.type');
		$query->orderBy('badges.slug');

		if ($results = $query->select('badge_member.*')->paginate($request->input('per-page', 50)))
		{
			return $this->success($results, TRUE);
		}
		return $this->error();
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		// check user has permission
		if (Gate::denies('edit-achievements')) return $this->denied();

		if (!$request->input('member_id')) return $this->error("member_id is required");
		if (!$request->input('badge_id')) return $this->error("badge_id is required");

		$item = new BadgeMember;
		$item->member_id=$request->input('member_id');
		$item->badge_id=$request->input('badge_id');
		$item->comments='';
		if ($request->input('awarded_date')) $item->awarded_date=$request->input('awarded_date');
		if ($request->input('badge_number')) $item->badge_number=$request->input('badge_number');
		if ($item->save())
		{
			return $this->success($item);
		}
		return $this->error();
	}



	public function update(Request $request, $id)
	{
		// check user has permission
		if (Gate::denies('edit-achievements')) return $this->denied();

		if (!$request->input('member_id')) return $this->error("member_id is required");
		if (!$request->input('badge_id')) return $this->error("badge_id is required");

		// check member exists
		if (!$item = BadgeMember::find($id))
		{
			return $this->not_found();
		}

		$item->member_id=$request->input('member_id');
		$item->badge_id=$request->input('badge_id');
		if ($request->input('awarded_date')) $item->awarded_date=$request->input('awarded_date');
		if ($item->save())
		{
			return $this->success($item);
		}
		return $this->error();
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		// check user has permission
		if (Gate::denies('edit-achievements')) return $this->denied();

		// check member exists
		if (!$item = BadgeMember::find($id))
		{
			return $this->not_found();
		}

		if (!$badge = Badge::find($item->badge_id))
		{
			return  $this->not_found();
		}

		if ($badge->type=='FAI Awards' || $badge->type=='FAI Badges')
		{
			if (Gate::denies('edit-awards')) return $this->denied();
		}


		if ($item->delete())
		{
			return $this->success();
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
		if ($result = BadgeMember::find($id))
		{
			return $this->success($result);
		}
		return $this->error();
	}

}
