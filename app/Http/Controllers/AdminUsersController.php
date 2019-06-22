<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use Gate;

class AdminUsersController extends Controller
{
	//
	function index()
	{
		return view('admin/admin-users');
	}

	function roles(User $user)
	{
		if (!Gate::allows('club-admin')) abort(403, 'Unauthorized action.');

		return view('admin/admin-user-roles', ['user'=>$user]);
	}
}
