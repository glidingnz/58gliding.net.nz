<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Messages;
use Gate;

class AdminController extends Controller
{
	/**
	 * Main controller for all Admin pages. The page maps to a specific vue controller to load from the URL.
	 * All admin pages have the same side menu.
	 *
	 *  Main admin
	 *  	Import Buttons
	 *  	Manage Users
	 *  	OAuth Clients
	 */
	public function index(Request $request, $page=null)
	{
		//if (Gate::denies('admin')) return abort(403);
		switch ($page) {
			case 'club-admin': $tag='club-admin'; break;
			case 'users': $tag='users'; break;
			default: $tag='test'; break;
		}

		return view('admin/admin', Array('page'=>$page, 'tag'=>$tag));
	}

	public function club_admin()
	{
		if (Gate::denies('club-admin')) return abort(403);
		return view('admin/club-admin');
	}

	public function club_member_types()
	{
		if (Gate::denies('club-admin')) return abort(403);
		return view('admin/club-member-types');
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
