<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Classes\LoadAircraft;
use App\Classes\BadgeImporter;
use App\Classes\MemberUtilities;
use App\Models\Member;
use App\Models\RatingMember;
use App\Models\BadgeMember;
use App\Models\Badge;
use Illuminate\Support\Facades\Mail;
use App\Mail\AddressChanges;

use Messages;
use Gate;

class AdminController extends Controller
{
	public function index()
	{
		if (Gate::denies('admin')) return abort(403);
		return view('admin/admin');
	}

	public function import_flarm()
	{
		if (Gate::denies('admin')) return abort(403);
		$aircraftLoader = new LoadAircraft();
		$aircraftLoader->load_flarm_from_glidernet();
		$aircraftLoader->import_flarm_db();

		Messages::success('Flarm imported');
		return view('admin/admin');
	}

	public function import_badges()
	{
		if (Gate::denies('admin')) return abort(403);
		$badgeImporter = new BadgeImporter();
		$badgeImporter->import_all_member_badges();

		Messages::success('Badges imported');
		return view('admin/admin');
	}

	// import QGPs from the membership DB into the ratings table
	public function import_qgps()
	{
		if (Gate::denies('admin')) return abort(403);
		// get all QGPs from membership DB
		if ($members = Member::whereNotNull('qgp_number')->get())
		{
			foreach ($members AS $member)
			{
				// check if we have a QGP entry for this member
				if (!$rating = RatingMember::where('member_id', $member->id)->where('rating_id', 1)->first())
				{
					$newRating = new RatingMember;
					$newRating->member_id =  $member->id;
					$newRating->rating_id =  1; //1=QGP
					$newRating->awarded = $member->date_of_qgp;
					$newRating->authorising_member_id = 0; // no member
					$newRating->granted_by_user_id = 0; // root
					//$newRating->notes = 'Imported from GNZ membership database';
					$newRating->save();
				}
			}
		}

		Messages::success('QGPs imported');
		return view('admin/admin');
	}

	// import QGPs from the membership DB into the ratings table
	public function sync_qgps()
	{
		if (Gate::denies('admin')) return abort(403);
		$qgp_badge = Badge::where('slug', 'qgp')->first();

		// get all QGPs from ratings_member table
		if ($ratings = RatingMember::where('rating_id', 1)->get())
		{
			foreach ($ratings AS $rating)
			{
				// check if we have a QGP badge for this member
				if (!$badge = BadgeMember::where('member_id', $rating->member_id)->where('badge_id', $qgp_badge->id)->first())
				{
					$newBadge = new BadgeMember;
					$newBadge->member_id = $rating->member_id;
					$newBadge->badge_id = $qgp_badge->id;
					$newBadge->awarded_date = $rating->awarded;
					//$newBadge->comments = 'Imported from Ratings Table';
					$newBadge->save();

					//echo 'found one: ' . $rating->member_id . '<br>';
				}
			}
		}

		Messages::success('QGPs imported');
		return view('admin/admin');
	}



	public function import_aircraft_from_caa()
	{
		if (Gate::denies('admin')) return abort(403);
		
		$aircraftLoader = new LoadAircraft();
		$aircraftLoader->load_db_from_caa();
		$aircraftLoader->import_caa_db();
		Messages::success('CAA DB Imported');
		return view('admin/admin');
	}


	public function email_address_changes() {
		$MemberUtilities = new MemberUtilities();
		$users = $MemberUtilities->get_address_changes();
		if (sizeof($users)>0) {

			// prepare an email
			$to = "laurie.kirkham@xtra.co.nz";
			$cc = "tim@pear.co.nz";
			Mail::to($to)->cc($cc)->send(new AddressChanges($users));
			Messages::success('Email Sent');
		} else {
			Messages::success('No users with changed addresses for today');
		}

		return view('admin/admin');
	}
}
