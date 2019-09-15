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
            if (isset($user->is_root) && $user->is_root==true) return true;

            if ($role = Role::where('slug','root')->first())
            {
                $userRole = RoleUser::where('role_id', $role->id)->where('user_id', $user->id)->first();

                if ($userRole) {
                    $user->is_root = true;
                    return true;
                }
            }

            return false;
        });


        Gate::define('admin', function ($user) {

            // check if we've already approved this
            if (isset($user->is_admin) && $user->is_admin==true) return true;

            if (Gate::allows('root')) return true; // check above first!

            if ($role = Role::where('slug','admin')->first())
            {
                $userRole = RoleUser::where('role_id', $role->id)->where('user_id', $user->id)->first();
                if ($userRole) {
                    $user->is_admin = true;
                    return true;
                }
            }
            return false;
        });

        // optionally pass in a specific organisation object. Otherwise it uses the currently viewed organisation.
        Gate::define('club-admin', function ($user, $org=NULL) {

            if (Gate::allows('admin')) return true; // check above first!

            // check if we are current in an org
            if ($org==NULL && $current_org = Request::get('_ORG'))
            {
                $org = $current_org;
            }

            if (!$org) return false;

            if ($role = Role::where('slug','club-admin')->first())
            {

                $userRole = RoleUser::where('role_id', $role->id)->where('user_id', $user->id)->where('org_id', $org->id)->first();

                if ($userRole) {
                    return true;
                }
            }

            return false;
        });


        // optionally pass in a specific organisation object. Otherwise it uses the currently viewed organisation.
        Gate::define('club-member', function ($user, $org=NULL) {

            if (Gate::allows('club-admin', $org)) return true; // check above first!

            // check if we are current in an org
            if ($org==NULL && $current_org = Request::get('_ORG'))
            {
                $org = $current_org;
            }

            if (!$org) return false;

            // check if we're a normal club member type user
            if ($role = Role::where('slug','club-member')->first())
            {

                // check if a role has been specified
                $userRole = RoleUser::where('role_id', $role->id)->where('user_id', $user->id)->where('org_id', $org->id)->first();

                if ($userRole) {
                    $user->is_club_member = true;
                    return true;
                }

                // check if this user is in the GNZ DB as a member
                $member = Member::where('club', $org->gnz_code)->where('nzga_number', $user->gnz_id)->first();
                if ($member) {
                    return true;
                }
            }

            return false;
        });


        /* only confirmed GNZ members can do certain things e.g. view other member phone numbers */
        Gate::define('gnz-member', function ($user) {
            // check if we've already approved this
            if (isset($user->is_gnz_member) && $user->is_gnz_member==true) return true;

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
            return false;
        });


        // Only the awards officer is allowed to edit awards.
        Gate::define('edit-awards', function($user) {
            // check if we've already approved this
            if (isset($user->can_edit_awards) && $user->can_edit_awards==true) return true;

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

            return false;
        });


        // Admins and GNZ members that are coaches are allowed to edit achievements
        Gate::define('edit-achievements', function($user) {
            if (isset($user->can_edit_achievements) && $user->can_edit_achievements==true) return true;

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


        Gate::define('membership-view', function($user) {
            if ($role = Role::where('slug','view-membership')->first())
            {
                $userRole = RoleUser::where('role_id', $role->id)->where('user_id', $user->id)->first();

                if ($userRole) {
                    $user->can_edit_awards = true;
                    return true;
                }
            }
            return false;
        });

        Gate::define('waypoint-admin', function($user) {

            // check if we've already approved this
            if (isset($user->is_admin) && $user->is_admin==true) return true;

            if (Gate::allows('root')) return true; // check above first!

            if ($role = Role::where('slug','waypoint-admin')->first())
            {
                $userRole = RoleUser::where('role_id', $role->id)->where('user_id', $user->id)->first();

                if ($userRole) {
                    return true;
                }
            }
            return false;
        });

        Gate::define('contest-admin', function($user) {

            // check if we've already approved this
            if (isset($user->is_admin) && $user->is_admin==true) return true;

            if (Gate::allows('root')) return true; // check above first!

            if ($role = Role::where('slug','contest-admin')->first())
            {
                $userRole = RoleUser::where('role_id', $role->id)->where('user_id', $user->id)->first();

                if ($userRole) {
                    return true;
                }
            }
            return false;
        });

    }
}
