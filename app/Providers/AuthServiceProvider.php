<?php

namespace App\Providers;

use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Request;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use App\Models\Org;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\Member;
use App\Models\Affiliate;

use Auth;

class AuthServiceProvider extends ServiceProvider
{
	/**
	* The policy mappings for the application.
	*
	* @var array
	*/
	protected $policies = [
		'App\Model' => 'App\Policies\ModelPolicy',
	];

	/**
	* Register any authentication / authorization services.
	*
	* @return void
	*/
	/**
	* Register any authentication / authorization services.
	*
	* @return void
	*/
	public function boot()
	{
		$this->registerPolicies();

		Passport::routes();



		Gate::define('root', function ($user) {

			// check if we've already approved this
			if (isset($user->is_root)) return $user->is_root;

			if ($role = Role::where('slug','root')->first())
			{
				$userRole = RoleUser::where('role_id', $role->id)->where('user_id', $user->id)->first();

				if ($userRole) {
					$user->is_root = true;
					return true;
				}
			}

			$user->is_root = false;
			return false;
		});


		Gate::define('admin', function ($user) {

			if (isset($user->is_admin)) return $user->is_admin;

			if (Gate::allows('root')) return true; // check above first!

			if ($role = Role::where('slug','admin')->first())
			{
				$userRole = RoleUser::where('role_id', $role->id)->where('user_id', $user->id)->first();
				if ($userRole) {
					$user->is_admin = true;
					return true;
				}
			}
			$user->is_admin = false;
			return false;
		});

		// optionally pass in a specific organisation object. Otherwise it uses the currently viewed organisation.
		Gate::define('club-admin', function ($user, $org=NULL) {

			// check if we are current in an org
			if ($org==NULL && $current_org = Request::get('_ORG'))
			{
				$org = $current_org;
			}
			if (!$org) return false;

			$variable_name = 'is_club_admin_' . $org->id;

			// check if we've already approved this
			if (isset($user->$variable_name)) return $user->$variable_name;

			if (Gate::allows('admin')) return true; // check above first!


			if (!$org) return false;

			if ($role = Role::where('slug','club-admin')->first())
			{

				$userRole = RoleUser::where('role_id', $role->id)->where('user_id', $user->id)->where('org_id', $org->id)->first();

				if ($userRole) {
					$user->$variable_name=true;
					return true;
				}
			}

			$user->$variable_name=false;
			return false;
		});


		// optionally pass in a specific organisation object. Otherwise it uses the currently viewed organisation.
		Gate::define('club-member', function ($user, $org=NULL) {

			// check if we are current in an org
			if ($org==NULL && $current_org = Request::get('_ORG'))
			{
				$org = $current_org;
			}
			if (!$org) return false;

			$variable_name = 'is_club_member_' . $org->id;

			// check if we've already approved this
			if (isset($user->$variable_name)) return $user->$variable_name;

			if (Gate::allows('club-admin', $org)) return true; // check above first!


			// check if we're a normal club member type user
			if ($role = Role::where('slug','club-member')->first())
			{

				// check if a role has been specified
				$userRole = RoleUser::where('role_id', $role->id)->where('user_id', $user->id)->where('org_id', $org->id)->first();

				if ($userRole) {
					$user->$variable_name = true;
					return true;
				}

				// check if this user is in the GNZ DB as a current member
				// first get the member ID
				if ($member = Member::where('nzga_number', $user->gnz_id)->first())
				{
					// get the affiliate details
					$affiliate = Affiliate::where('org_id', $org->id)->where('member_id', $member->id)->whereNotNull('end_date')->first();

					if ($affiliate) {
						$user->variable_name = true;
						return true;
					}
				}
			}

			$user->$variable_name=false;
			return false;
		});


		/* only confirmed GNZ members can do certain things e.g. view other member phone numbers */
		Gate::define('gnz-member', function (&$user) {

			// check if we've already approved this
			if (isset($user->is_gnz_member)) return $user->is_gnz_member;

			if (Gate::allows('admin')) return true; // always allow admin access

			if ($user->gnz_id!=null && $user->gnz_active==1)
			{
				$member = Member::where('nzga_number', $user->gnz_id)->first();
				if ($member)
				{
					$user->is_gnz_member=true;
					return true;
				}
			}

			$user->is_gnz_member=false;
			return false;
		});


		// Only the awards officer is allowed to edit awards.
		Gate::define('edit-awards', function($user) {
			// check if we've already approved this
			if (isset($user->can_edit_awards)) return $user->can_edit_awards;

			if (Gate::allows('admin')) return true; // check if admin first!

			$role = Role::where('slug','awards-officer')->first();

			if ($role)
			{
				$userRole = RoleUser::where('role_id', $role->id)->where('user_id', $user->id)->first();

				if ($userRole) {
					$user->can_edit_awards = true;
					return true;
				}
			}

			$user->can_edit_awards = false;
			return false;
		});


		// Admins and GNZ members that are coaches are allowed to edit achievements
		Gate::define('edit-achievements', function(&$user) {
			if (isset($user->can_edit_achievements)) return $user->can_edit_achievements;

			if (Gate::allows('admin')) return true; // check if admin first!

			// ensure the user is a GNZ coach
			if ($user->gnz_id>0 && $user->gnz_active==1)
			{
				$member = Member::where('nzga_number', $user->gnz_id)->where('coach', true)->first();
				if ($member)
				{
					$user->can_edit_achievements=true;
					return true;
				}
			}

			$user->can_edit_achievements=false;
			return false;
		});


		/**
		* Params:
		* user - the current user
		* member - the member we are wanting to edit
		*/
		Gate::define('edit-member', function($user, $member) {
			// if noraml admin, editing allowed
			if (Gate::allows('admin')) return true;
			if (Gate::allows('edit-self', $member)) return true;

			// load the club of the member we're trying to edit.
			$org = Org::where('gnz_code', $member->club)->first();

			if ($org)
			{
				// if the current user is a club admin for the member we're trying to edit, allow
				if (Gate::allows('club-admin', $org)) {
					return true;
				}
			}

			return false;
		});

		Gate::define('edit-self', function($user, $member) {
			// if you are editing yourself, and you've confirmed your GNZ ID, allow
			if ($member->nzga_number == $user->gnz_id && $user->gnz_active) return true;

			return false;
		});

		Gate::define('logged-in', function($user) {
			return Auth::check();
		});


		Gate::define('membership-view', function(&$user) {
			if (isset($user->can_view_membership) && $user->can_view_membership==true) return true;

			if ($role = Role::where('slug','view-membership')->first())
			{
				$userRole = RoleUser::where('role_id', $role->id)->where('user_id', $user->id)->first();

				if ($userRole) {
					$user->can_view_membership = true;
					return true;
				}
			}
			return false;
		});

		Gate::define('waypoint-admin', function(&$user) {

			// check if we've already approved this
			if (isset($user->is_waypoint_admin)) return $user->is_waypoint_admin;

			if (Gate::allows('root')) return true; // check above first!

			if ($role = Role::where('slug','waypoint-admin')->first())
			{
				$userRole = RoleUser::where('role_id', $role->id)->where('user_id', $user->id)->first();

				if ($userRole) {
					$user->is_waypoint_admin==true;
					return true;
				}
			}

			$user->is_waypoint_admin==false;
			return false;
		});

		Gate::define('contest-admin', function(&$user) {

			// check if we've already approved this
			if (isset($user->is_contest_admin)) return $user->is_contest_admin;

			if (Gate::allows('root')) return true; // check above first!

			if ($role = Role::where('slug','contest-admin')->first())
			{
				$userRole = RoleUser::where('role_id', $role->id)->where('user_id', $user->id)->first();

				if ($userRole) {
					$user->is_contest_admin==true;
					return true;
				}
			}
			
			$user->is_contest_admin==false;
			return false;
		});

	}
}
