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
use stdClass;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;

class AuthServiceProvider extends ServiceProvider
{

	protected $is_root; 
	protected $is_admin;
	protected $is_gnz_member;
	protected $is_club_admin = Array();
	protected $can_see_experimental_features;
	protected $is_contest_admin;
	protected $is_waypoint_admin;
	protected $can_view_membership;

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
			if (isset($this->is_root)) return $this->is_root;

			if ($role = Role::where('slug','root')->first())
			{
				$userRole = RoleUser::where('role_id', $role->id)->where('user_id', $user->id)->first();

				if ($userRole) {
					$this->is_root = true;
					return true;
				}
			}

			$user->is_root = false;
			return false;
		});


		Gate::define('admin', function ($user) {

			if (isset($this->is_admin)) return $this->is_admin;

			if (Gate::allows('root')) {  // check above first!
				$this->is_admin = true;
				return true;
			}

			if ($role = Role::where('slug','admin')->first())
			{
				$userRole = RoleUser::where('role_id', $role->id)->where('user_id', $user->id)->first();
				if ($userRole) {
					echo ' setting true ';
					$this->is_admin = true;
					return true;
				}
			}
			$this->is_admin = false;
			return false;
		});


		// optionally pass in a specific organisation object. Otherwise it uses the currently viewed organisation.
		Gate::define('club-admin', function ($user, $org=NULL) {

			// check if the given org is an ID or an object
			if (is_integer($org)) {
				// then just use that, not load the org from the DB again
				$org_id = $org;
				$org = new stdClass(); // create an empty org
				$org->id = $org_id; // give it ONLY the ID attribute, as that's all we use below...
			}

			// check if we are current in an org
			if ($org==NULL && $current_org = Request::get('_ORG'))
			{
				$org = $current_org;
			}
			if (!$org) return false;
			if (!isset($org->id)) return false;

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

			// check if we've already approved this
			if (isset($this->club_member[$org->id])) return $this->club_member[$org->id];

			if (Gate::allows('club-admin', $org)) return true; // check above first!


			// check if we're a normal club member type user
			if ($role = Role::where('slug','club-member')->first())
			{

				// check if a role has been specified
				$userRole = RoleUser::where('role_id', $role->id)->where('user_id', $user->id)->where('org_id', $org->id)->first();

				if ($userRole) {
					$this->club_member[$org->id] = true;
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

			$this->club_member[$org->id]=false;
			return false;
		});


		/* only confirmed GNZ members can do certain things e.g. view other member phone numbers */
		Gate::define('gnz-member', function ($user) {

			// check if we've already approved this
			if (isset($this->is_gnz_member)) return $this->is_gnz_member;

			if (Gate::allows('admin')) return true; // always allow admin access

			if ($user->gnz_id!=null && $user->gnz_active==1)
			{
				$member = Member::where('nzga_number', $user->gnz_id)->first();
				if ($member)
				{
					$this->is_gnz_member=true;
					return true;
				}
			}

			$this->is_gnz_member=false;
			return false;
		});


		// Only the awards officer is allowed to edit FAI awards.
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


		// Admins, GNZ members that are coaches and club admins are allowed to edit non FAI achievements
		// User is the current user
		// Member is the user we want to edit achievements of
		Gate::define('edit-achievements', function($user, $member) {
			if (isset($user->can_edit_achievements)) return $user->can_edit_achievements;

			if (Gate::allows('admin')) return true; // check if admin first!
			if (Gate::allows('edit-awards')) return true; // check if awards officer

			// get the target member's club
			$org = Org::where('gnz_code', $member->club)->first();

			// if the current user is a club admin for our target member
			if (Gate::allows('club-admin', $org)) {
				$user->can_edit_achievements=true;
				return true;
			}

			// ensure the user is a GNZ coach
			if ($user->gnz_id>0 && $user->gnz_active==1)
			{
				// load the current user membership details and current organisation
				$user_member = Member::where('nzga_number', $user->gnz_id)->first();


				// if this user is a coach, all OK
				if ($user_member->coach==true)
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
			if (Gate::allows('edit-awards', $member)) return true;

			// load all the affiliates that we are still a member of
			// or have been in the past
			if ($affiliates = Affiliate::where('member_id', $member->id)->with('org')->get())
			{
				foreach ($affiliates AS $affiliate)
				{
					if (Gate::allows('club-admin', $affiliate->org)) {
						return true;
					}
				}
			}

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


		// can see all GNZ membership details, but not edit
		Gate::define('membership-view', function($user) {
			if (isset($this->can_view_membership) && $this->can_view_membership==true) return true;

			if ($role = Role::where('slug','view-membership')->first())
			{
				$userRole = RoleUser::where('role_id', $role->id)->where('user_id', $user->id)->first();

				if ($userRole) {
					$this->can_view_membership = true;
					return true;
				}
			}
			return false;
		});


		Gate::define('waypoint-admin', function($user) {

			// check if we've already approved this
			if (isset($this->is_waypoint_admin)) return $this->is_waypoint_admin;

			if (Gate::allows('admin')) return true; // check above first!

			if ($role = Role::where('slug','waypoint-admin')->first())
			{
				$userRole = RoleUser::where('role_id', $role->id)->where('user_id', $user->id)->first();

				if ($userRole) {
					$this->is_waypoint_admin==true;
					return true;
				}
			}

			$this->is_waypoint_admin==false;
			return false;
		});

		Gate::define('contest-admin', function($user) {

			// check if we've already approved this
			if (isset($this->is_contest_admin)) return $this->is_contest_admin;

			if (Gate::allows('admin')) return true; // check above first!

			if ($role = Role::where('slug','contest-admin')->first())
			{
				$userRole = RoleUser::where('role_id', $role->id)->where('user_id', $user->id)->first();

				if ($userRole) {
					$this->is_contest_admin==true;
					return true;
				}
			}

			$this->is_contest_admin==false;
			return false;
		});

		Gate::define('experimental-features', function($user) {

			// check if we've already approved this
			if (isset($this->can_see_experimental_features)) return $this->can_see_experimental_features;

			if (Gate::allows('admin')) return true; // check above first!

			$role = Role::where('slug','experimental-features')->first();

			if ($role)
			{
				$userRole = RoleUser::where('role_id', $role->id)->where('user_id', $user->id)->first();

				if ($userRole) {
					$this->can_see_experimental_features = true;
					return true;
				}
			}

			$this->can_see_experimental_features = false;
			return false;
		});
	}
}
