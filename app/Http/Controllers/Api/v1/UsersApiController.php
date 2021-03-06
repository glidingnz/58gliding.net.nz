<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Api\ApiController;
use App\User;

class UsersApiController extends ApiController
{
	public function index(request $request)
	{

		switch ($request->input('sort'))
		{
			case 'first_name':
			case 'last_name':
			case 'email':
			case 'gnz_id':
			case 'gnz_active':
			case 'gnz_confirmed':
			case 'activated':
				$sort = $request->input('sort');
				break;
			default:
				$sort = 'email';
				break;
		}

		if ($request->input('direction')=='asc') $direction="ASC";
		else $direction = "DESC";

		$usersQuery = User::query()->orderBy($sort, $direction);

		if ($request->input('q'))
		{
			$s = '%' . $request->input('q') .'%';
			$usersQuery->where(function($usersQuery) use ($s) {
				$usersQuery->where('email','like',$s);
				$usersQuery->orWhere('first_name','like',$s);
				$usersQuery->orWhere('last_name','like',$s);
				$usersQuery->orWhere('gnz_id','like',$s);
			});
		}


		// if ($request->input('org'))
		// {
		// 	$usersQuery->where('rego','like',$s);
		// }
		//$usersQuery->where('activated',false);

		if ($users = $usersQuery->paginate($request->input('per-page', 100)))
		{
			return $this->success($users, TRUE);
		}
		return $this->error(); 
	}

}
