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
use App\Models\Upload;
use App\Models\Org;
use App\User;
use App\Models\RatingMember;
use Carbon\Carbon;
use App\Classes\GNZLogger;

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

		if (!$member_org = Org::where('gnz_code', $member->club)->first())
		{
			return $this->denied();
		}

		// only club members (and thus club admins or admins), awards officer or membership viewers can view ratings
		if(!(Gate::check('club-member', $member_org) || 
			Gate::check('edit-awards') || 
			Gate::check('membership-view')))
		{
			return $this->denied();
		}

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
	 * Get a single rating.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function get(Request $request, $member_id, $rating_member_id)
	{

		$rating_member = RatingMember::where('id', $rating_member_id)
			->with(['rating', 'member', 'uploads'])
			->first();

		if (!$member_org = Org::where('gnz_code', $rating_member->member->club)->first())
		{
			return $this->denied();
		}
		// only club admins, awards officer can view ratings including medical documents
		if(!(Gate::check('club-admin', $member_org) || 
			Gate::check('edit-awards')))
		{
			return $this->denied();
		}

		if ($auth_member = Member::where('id', $rating_member->authorising_member_id)->first())
		{
			$rating_member->auth_firstname = $auth_member->first_name;
			$rating_member->auth_lastname = $auth_member->last_name;
			$rating_member->nzga_number = $auth_member->nzga_number;
		}

		if ($added_user = User::where('id', $rating_member->granted_by_user_id)->first())
		{
			$rating_member->added_firstname = $added_user->first_name;
			$rating_member->added_lastname = $added_user->last_name;
		}

		if (!$rating_member)
		{
			return $this->error();
		}

		return $this->success($rating_member);
	}



	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$org = $request->get('_ORG');

		$user =  Auth::user();

		if (!$request->input('rating_id')) return $this->error("rating_id is required");
		if (!$request->input('member_id')) return $this->error("member_id is required");
		if (!$request->input('awarded')) return $this->error("awarded date is required");

		// handle uploading the files
		$path = $org->folder;

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

		if (!$member_org = Org::where('gnz_code', $member->club)->first())
		{
			return $this->denied();
		}


		// only club admins, awards officer can edit ratings including medical documents
		if(!(Gate::check('club-admin', $member_org) || 
			Gate::check('edit-awards')))
		{
			return $this->denied();
		}


		$ratingMember = new RatingMember;
		$ratingMember->expires = null;
		$ratingMember->revoked_by = null;
		$ratingMember->authorising_member_id = null;
		$ratingMember->number = null;

		// calculate expires date from months given if given
		if ($request->input('expires')) {
			if (!is_numeric($request->input('expires'))) {
				$ratingMember->expires=null;
			}
			else
			{
				$expires_date = new Carbon($request->input('awarded'));
				//$expires_date = DateTime::createFromFormat('Y-m-d', $request->input('awarded'));
				$expires_date->addMonths($request->input('expires'));
				//$expires_date->modify('+' . $request->input('expires') . ' month');
				$ratingMember->expires = $expires_date->toDateString();
			}
		}

		// if this is a numbered rating, get the max number in the database
		if ($rating->numbered) {
			$number = RatingMember::where('rating_id', $rating->id)->max('number');
			$number++;
			$ratingMember->number=$number;
		}

		$awarded = new Carbon($request->input('awarded'));

		$ratingMember->rating_id=$request->input('rating_id');
		$ratingMember->member_id=$request->input('member_id');
		$ratingMember->number=$request->input('number');
		$ratingMember->awarded= $awarded->toDateString();
		$ratingMember->notes=$request->input('notes', '');
		$ratingMember->authorising_member_id=$request->input('authorising_member_id');
		$ratingMember->granted_by_user_id = $user->id;


		// save the item if all OK!
		if ($ratingMember->save())
		{
			$gnz_logger = new GNZLogger();
			$gnz_logger->log($member, 'Rating Created', $rating->name, '', $ratingMember->badge_number);

			$this->upload_files($request, $ratingMember, $org);
			return $this->success($ratingMember);
		}
		return $this->error('Something went wrong sorry');
	}


	/**
	 * Upload files for a memberRating
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function upload(Request $request, $rating_member_id)
	{
		$org = $request->get('_ORG');
		$ratingMember = RatingMember::findOrFail($rating_member_id);

		$this->upload_files($request, $ratingMember, $org);
	}


	public function upload_files($request, $ratingMember, $org)
	{
		// get rating details
		$member = Member::findOrFail($ratingMember->member_id);
		$rating = Rating::findOrFail($ratingMember->rating_id);
		$user =  Auth::user();

		// process any files that were uploaded
		foreach ($request->allFiles('files') AS $files)
		{
			$counter = 0;
			foreach ($files as $file)
			{
				$upload = new Upload();
				$upload->user_id = $user->id; // the user that uploaded the file, not the pilot
				$upload->org_id = $org->id;
				// save into the DB so we can get the ID
				$upload->save();


				$filename = simple_string(strtolower($member->last_name)) . '-' . 
							simple_string(strtolower($rating->name)) . '-' .
							$ratingMember->id . '-' . 
							$upload->id . '.' . 
							$file->getClientOriginalExtension();

				// save the file
				$path =  $file->storeAs($org->folder . 'ratings', $filename);

				// put details into database
				$upload->filename = $filename;
				$upload->folder = $org->files_path . 'ratings';
				$upload->slug = simple_string(strtolower($filename));
				$upload->type = $file->getClientOriginalExtension();
				$upload->uploadable()->associate($ratingMember);
				$upload->save();

				$gnz_logger = new GNZLogger();
				$gnz_logger->log($member, 'Rating File Uploaded', $rating->name, '', $upload->filename);

				$counter++;
			}
			
		}
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
	 * @param  int  $member_id - keep in mind this might be faked, so don't trust it
	 * @param  int  $rating_member_id - the actual rating_member link we are deleting
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request, $member_id, $rating_member_id)
	{
		$ratingMember = RatingMember::findOrFail($rating_member_id);

		// get membership details of this rating member
		$member = Member::findOrFail($ratingMember->member_id);
		$rating = Member::findOrFail($ratingMember->rating_id);

		// get the org
		if (!$org = Org::where('gnz_code', '=', $member->club)->first())
		{
			return $this->denied();
		}

		// check we are club admin for the person's org we are editing
		if (Gate::denies('club-admin', $org)) return $this->denied();

		if ($ratingMember->delete())
		{
			$gnz_logger = new GNZLogger();
			$gnz_logger->log($member, 'Rating Deleted', $rating->name);

			return $this->success('Rating Deleted');
		}
		return $this->error(); 
	}


	public function destroyFile(Request $request, $member_id, $rating_member_id, $upload_id)
	{
		$member = Member::findOrFail($member_id);
		$ratingMember = RatingMember::findOrFail($rating_member_id);
		$rating = Member::findOrFail($ratingMember->rating_id);

		// get the org
		if (!$org = Org::where('gnz_code', '=', $member->club)->first()) return $this->denied();

		if (Gate::denies('club-admin', $org)) return $this->denied();

		$upload = Upload::findOrFail($upload_id);
		if ($upload->delete())
		{
			$gnz_logger = new GNZLogger();
			$gnz_logger->log($member, 'Rating File Deleted', $rating->name, $upload->filename);

			return $this->success('File Deleted');
		}
		return $this->error(); 
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
