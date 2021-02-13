<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Gate;
use File;
use App\Models\Member;
use App\Models\Org;
use App\Models\Rating;
use App\Models\RatingMember;

class MembersController extends Controller
{
	public function index()
	{
		return view('members/members-list');
	}

	public function add()
	{

		// check if the current logged in user is an admin of the club
		if (!Gate::allows('club-admin')) {
			abort(403);
		}
		return view('members/add');
	}


	public function email()
	{
		return view('members/send-email');
	}

	public function view($id)
	{
		$data = Array('member_id'=>$id);
		$data['allows_edit']=false;

		$member = Member::findOrFail($id);
		if (Gate::allows('edit-member', $member)) {
			$data['allows_edit']=true;
		}

		return view('members/member-view', $data);
	}

	public function log($id)
	{
		$data = Array('member_id'=>$id);
		$member = Member::findOrFail($id);
		if (Gate::allows('edit-member', $member)) {
			$data['allows_edit']=true;
		}

		return view('members/log', $data);
	}

	public function edit($id)
	{
		$member = Member::findOrFail($id);

		if (Gate::denies('edit-member', $member)) {
			abort(403);
		}

		return view('members/member-edit', Array('member_id'=>$id, 'member'=>$member));
	}

	public function edit_affiliates($id)
	{
		$member = Member::findOrFail($id);

		if (Gate::denies('edit-member', $member)) {
			abort(403);
		}

		return view('members/member-edit-affiliates', Array('member_id'=>$id, 'member'=>$member));
	}

	public function achievements($id)
	{
		$data['member_id']=$id;
		$data['allows_edit']=false;

		$data['member'] = Member::findOrFail($id);

		if (Gate::allows('edit-achievements', $data['member'])) {
			$data['allows_edit']=true;
		}

		return view('members/achievements-view', $data);
	}


	public function edit_achievements($id)
	{
		$data['member'] = Member::findOrFail($id);
		$data['allows_edit']=false;

		if (Gate::denies('edit-achievements', $data['member'])) {
			abort(403);
		}

		return view('members/achievements-edit', $data);
	}

	/**
	 * View the users ratings
	 */
	public function ratings($id)
	{
		$data['member_id']=$id;
		$data['allows_edit']=false;

		// get the club this member is a member of
		$data['member'] = Member::findOrFail($id);
		if (!$data['members_org'] = Org::where('gnz_code', $data['member']->club)->first())
		{
			abort(403);
		}

		// check if the current logged in user is an admin of the club
		if (Gate::allows('club-admin', $data['members_org']) || Gate::allows('edit-awards')) {
			$data['allows_edit']=true;
		}

		return view('members/ratings', $data);
	}

	/**
	 * View a single rating on a user
	 */
	public function rating(Request $request, $member_id, $rating_member_id)
	{
		$data['member_id']=$member_id;
		$data['rating_member_id']=$rating_member_id;
		$data['allows_edit']=false;

		//echo $rating_member_id; exit();

		// check this member has this rating
		if (!$ratingMember = RatingMember::findOrFail($rating_member_id))
		{
			abort(404);
		}

		// get the club this member is a member of
		$data['member'] = Member::findOrFail($member_id);
		if (!$members_org = Org::where('gnz_code', $data['member']->club)->first())
		{
			abort(403);
		}

		$data['rating'] = Rating::findOrFail($ratingMember->rating_id);

		// check if the current logged in user is an admin of the club
		if (Gate::allows('club-admin', $members_org)) {
			$data['allows_edit']=true;
		}

		return view('members/rating-view', $data);
	}



	public function ratingsReport(Request $request)
	{
		$data['allows_edit']=false;

		// check if the current logged in user is a club admin of the current club
		if (Gate::allows('club-admin')) {

			$data['allows_edit']=true;
		}

		return view('members/ratings-report', $data);
	}




	// Download, then delete the temporary exported file
	public function download($filename)
	{
		// simple_string() ensures the string only has letters and numbers and dots
		$file_path = storage_path('exports/') . safe_filename($filename, ".");
		if (file_exists($file_path))
		{
			$file_extension = File::extension($file_path);

			switch ($file_extension)
			{
				case 'csv':
					header("Content-type: text/csv");
					break;
				case 'xls':
					header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
					break;
			}
			header("Content-Disposition: attachment; filename={$filename}");
			header("Pragma: no-cache");
			header("Expires: 0");

			readfile($file_path);

			// delete the file after outputting it
			unlink($file_path);
		}
	}






}
