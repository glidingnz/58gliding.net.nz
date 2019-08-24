<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use Gate;
use Auth;
use DB;
use DateTime;
use App\Models\Member;
use App\Models\Rating;
use App\Models\RatingMember;
use Carbon\Carbon;

class RatingMemberApiController extends ApiController
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request, $member_id)
	{
		// check the member exists first
		if (!$member = Member::where('id', $member_id)->first())
		{
			return $this->error('Member not found');
		}

		// get the member's ratings
		// $ratingQuery = RatingMember::query()->with(array(
		// 	'member' => function($query)
		// 	{
		// 		$query->select('first_name');
		// 	}), 'authorisingMember'
		// 	)->where('member_id', $member->id);
		//$ratingQuery = RatingMember::query()->with(array('member:first_name', 'authorisingMember:first_name'))->where('member_id', $member->id);
		$ratingQuery = DB::table('rating_member')
			->leftJoin('gnz_member AS authorising_member', 'authorising_member_id', '=', 'authorising_member.id')
			->leftJoin('ratings', 'rating_id', '=', 'ratings.id')
			->leftJoin('users', 'granted_by_user_id', '=', 'users.id')
			->select(
				'authorising_member.first_name AS auth_firstname', 
				'authorising_member.last_name AS auth_lastname',
				'authorising_member.nzga_number',
				'users.first_name',
				'users.last_name',
				'ratings.*',
				'rating_member.*'
			)->where('member_id', $member->id)->orderBy('rating_member.id', 'DESC');

		if ($ratings = $ratingQuery->get())
		{
			return $this->success($ratings);
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
		$user =  Auth::user();
		// check user has permission
		if (Gate::denies('club-admin')) return $this->denied();

		if (!$request->input('rating_id')) return $this->error("rating_id is required");
		if (!$request->input('member_id')) return $this->error("member_id is required");
		if (!$request->input('awarded')) return $this->error("awarded date is required");
		if (!$request->input('authorising_member_id')) return $this->error("authorising_member_id is required");



		// fetch the rating
		if (!$rating = Rating::where('id', $request->input('rating_id'))->first())
		{
			return $this->error('Rating not found');
		}

		// check the member exists first
		if (!$member = Member::where('id', $request->input('member_id'))->first())
		{
			return $this->error('Member not found');
		}

		$item = new RatingMember;
		$item->expires = null;
		$item->revoked_by = null;

		// calculate expires date from months given if given
		if ($request->input('expires')) {
			if (!is_numeric($request->input('expires'))) {
				$item->expires=null;
			}
			else
			{
				$expires_date = new Carbon($request->input('awarded'));
				//$expires_date = DateTime::createFromFormat('Y-m-d', $request->input('awarded'));
				$expires_date->addMonths($request->input('expires'));
				//$expires_date->modify('+' . $request->input('expires') . ' month');
				$item->expires = $expires_date->toDateString();
			}
			
		}

		$awarded = new Carbon($request->input('awarded'));

		$item->rating_id=$request->input('rating_id');
		$item->member_id=$request->input('member_id');
		$item->awarded= $awarded->toDateString();
		$item->notes=$request->input('notes', '');
		$item->authorising_member_id=$request->input('authorising_member_id');
		$item->granted_by_user_id = $user->id;

		if ($item->save())
		{
			return $this->success($item);
		}
		return $this->error('Something went wrong sorry');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		//
	}


	public function ratingsReport(Request $request)
	{
		$ratings = DB::select('SELECT 
			id, first_name, middle_name, last_name, nzga_number, instructor, date_of_birth, bfr.bfr_expires, medical.medical_expires, bfr.bfr_awarded, medical.medical_awarded, qgp.qgp_awarded,
				IF(DATE_SUB(medical.medical_awarded, INTERVAL 40 YEAR) < date_of_birth, medical.five_year_medical_expire, medical.two_year_medical_expire) AS medical_passenger_expires
			FROM gnz_member
			LEFT JOIN (SELECT MAX(expires) AS bfr_expires, member_id, MAX(awarded) AS bfr_awarded FROM rating_member AS bfr WHERE rating_id=2 AND expires IS NOT NULL GROUP BY member_id) AS bfr ON bfr.member_id=gnz_member.id
			LEFT JOIN (SELECT MAX(awarded) AS medical_awarded, member_id, MAX(expires) AS medical_expires, DATE_ADD(MAX(awarded), INTERVAL 2 YEAR) AS two_year_medical_expire, DATE_ADD(MAX(awarded), INTERVAL 5 YEAR) AS five_year_medical_expire  FROM rating_member AS medical WHERE rating_id>=3 AND rating_id<=7  GROUP BY member_id) AS medical ON medical.member_id=gnz_member.id
			LEFT JOIN (SELECT MAX(awarded) AS qgp_awarded, member_id FROM rating_member AS qgp WHERE rating_id=1 GROUP BY member_id) AS qgp ON qgp.member_id=gnz_member.id
			WHERE club=:org ORDER BY last_name', ['org' => $request->input('org')]);

		if ($ratings)
		{
			return $this->success($ratings);
		}
		return $this->error(); 
	}
}
