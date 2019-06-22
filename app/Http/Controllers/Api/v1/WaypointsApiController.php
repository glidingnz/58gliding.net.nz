<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Api\ApiController;
use App\Models\Waypoint;

class WaypointsApiController extends ApiController
{
	public function index(request $request)
	{

		// switch ($request->input('sort'))
		// {
		// 	case 'first_name':
		// 	case 'last_name':
		// 	case 'email':
		// 	case 'gnz_id':
		// 	case 'gnz_active':
		// 	case 'gnz_confirmed':
		// 	case 'activated':
		// 		$sort = $request->input('sort');
		// 		break;
		// 	default:
		// 		$sort = 'email';
		// 		break;
		// }

		// if ($request->input('direction')=='asc') $direction="ASC";
		// else $direction = "DESC";

		$query = Waypoint::query();

		if ($request->input('q'))
		{
			$s = '%' . $request->input('q') .'%';
			$query->where(function($usersQuery) use ($s) {
				$usersQuery->where('email','like',$s);
				$usersQuery->orWhere('first_name','like',$s);
				$usersQuery->orWhere('last_name','like',$s);
				$usersQuery->orWhere('gnz_id','like',$s);
			});
		}


		if ($waypoints = $query->get())
		{
			return $this->success($waypoints);
		}
		return $this->error(); 
	}

}
