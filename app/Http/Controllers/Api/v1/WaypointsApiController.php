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
        //     case 'first_name':
        //     case 'last_name':
        //     case 'email':
        //     case 'gnz_id':
        //     case 'gnz_active':
        //     case 'gnz_confirmed':
        //     case 'activated':
        //         $sort = $request->input('sort');
        //         break;
        //     default:
        //         $sort = 'email';
        //         break;
        // }

        // if ($request->input('direction')=='asc') $direction="ASC";
        // else $direction = "DESC";

        $query = Waypoint::query();

        if ($request->input('q'))
        {
            $s = '%' . $request->input('q') .'%';
            $query->where(function($usersQuery) use ($s) {
                $usersQuery->where('code','like',$s);
                $usersQuery->orWhere('name','like',$s);
                $usersQuery->orWhere('description','like',$s);
            });
        }


        if ($waypoints = $query->get())
        {
            return $this->success($waypoints);
        }
        return $this->error();
    }

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function show($id)
    {
        if ($turnpoint = Turnpoint::find($id))
        {
            return $this->success($turnpoint);
        }
        return $this->error();
    }
}
