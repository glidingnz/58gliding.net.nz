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

        $query = Waypoint::query();

        if ($request->input('q'))
        {
            $s = '%' . $request->input('q') .'%';
            $query->where(function($waypointsQuery) use ($s) {
                $waypointsQuery->where('code','like',$s);
                $waypointsQuery->orWhere('name','like',$s);
                $waypointsQuery->orWhere('description','like',$s);
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
