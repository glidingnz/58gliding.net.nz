<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;

use App\Http\Requests;
use App\Models\RoleUser;
use App\Models\Role;
use App\User;
use Auth;

class RolesApiController extends ApiController
{
	public function index()
	{
		// get the current user
		$user = Auth::user();

		// load all roles
		if ($roles = Role::orderBy('roles.club', 'asc')->orderBy('roles.name', 'asc')->get())
		{
			return $this->success($roles);
		}
		return $this->error(); 
	}


	public function user_roles($userID)
	{
		$user_roles = User::find($userID)->roles()->orderBy('roles.club', 'asc')->orderBy('roles.name', 'asc')->get();
		return $this->success($user_roles);
	}


	// must be limited to user
	public function add_user_role(Request $request, $userID)
	{
		$user = User::find($userID);
		$user->roles()->attach($request->roleID);

		return $this->success();
	}

	// must be limited to user
	public function delete_user_role($roleUserID)
	{
		$roleUser = RoleUser::find($roleUserID);
		$roleUser->delete();

		return $this->success();
	}

	// must be limited to user
	public function update_user_role(Request $request, $roleUserID)
	{
		$this->data['roleUserID'] = $roleUserID;
		if ($roleUser = RoleUser::find($roleUserID))
		{
			$roleUser->org_id = $request->orgID;
			$roleUser->update();
			return $this->success();
		}
		
		return $this->not_found();
	}
}
