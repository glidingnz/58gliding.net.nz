<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use App\Http\Controllers\Api\ApiController;
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
use Gate;

class AdminApiController extends ApiController
{
	public function import_flarm()
	{
		if (Gate::denies('admin')) return abort(403);
		$aircraftLoader = new LoadAircraft();
		$aircraftLoader->load_flarm_from_glidernet();
		$aircraftLoader->import_flarm_db();

		return $this->success('Flarm imported');
	}

	public function import_aircraft_from_caa()
	{
		if (Gate::denies('admin')) return abort(403);
		
		$aircraftLoader = new LoadAircraft();
		$aircraftLoader->load_db_from_caa();
		$aircraftLoader->import_caa_db();

		return $this->success('CAA DB Imported');
	}


	public function email_address_changes() {
		$MemberUtilities = new MemberUtilities();
		$users = $MemberUtilities->get_address_changes();
		if (sizeof($users)>0) {

			// prepare an email
			$to = "laurie.kirkham@xtra.co.nz";
			$cc = "tim@pear.co.nz";
			Mail::to($to)->cc($cc)->send(new AddressChanges($users));
			return $this->success('Email Sent');
		} else {
			return $this->success('No users with changed addresses for today');
		}

	}

}