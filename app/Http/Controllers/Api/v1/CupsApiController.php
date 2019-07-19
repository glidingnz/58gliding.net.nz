<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Api\ApiController;
use App\Models\Cup;

class CupsApiController extends ApiController
{
    public function index(request $request)
    {

        $query = Cup::query();

        if ($request->input('q'))
        {
            $s = '%' . $request->input('q') .'%';
            $query->where(function($waypointsQuery) use ($s) {
                $waypointsQuery->where('name','like',$s);
                $waypointsQuery->orWhere('description','like',$s);
            });
        }


        if ($cups = $query->get())
        {
            return $this->success($cups);
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
        if ($cup = Cup::with('waypoints')->find($id))
        {
            return $this->success($cup);
        }
        return $this->error();
    }
}
