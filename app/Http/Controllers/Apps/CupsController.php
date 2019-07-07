<?php

namespace App\Http\Controllers\Apps;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Cup;
use App\Models\Waypoint;


use App\Grids\CupsGrid;
use App\Grids\CupsGridInterface;

class CupsController extends Controller
{

    public function index(Request $request)
    {
        $cups = Cup::class;

        return (new CupsGrid(['cups' => $cups]))
        ->create(['query' => Cup::query(), 'request' => $request])
        ->renderOn('cups.index');
    }

    public function create(Request $request)
    {
        $cup = new Cup;
        $modal = [
            'model' => class_basename(Cup::class),
            'route' => route('cups.store'),
            'action' => 'create',
            'pjaxContainer' => $request->get('ref'),
        ];

        // modal
        return view('cups.cups-modal', compact('modal','cup'))->render();
    }

    /**
    * Display the specified resource.
    *
    * @param  int $id
    * @param Request $request
    * @return \Illuminate\Http\Response
    * @throws \Throwable
    */
    public function show($id, Request $request)
    {
        $cup = Cup::query()->findOrFail($id);
        $modal = [
            'model' => class_basename(Cup::class),
            'route' => route('cups.show',['cup'=>$cup->id]),
            'method' => 'PATCH',
            'action' => 'show',
            'pjaxContainer' => $request->get('ref'),
        ];

        // modal
        return view('cups.cups-modal', compact('modal','cup'))->render();
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request $request
    * @return JsonResponse|\Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'min:3|max:80',
            'description' => 'min:3|max:140',
        ]);

        $cup = Cup::create($request->except(['_method','_token']));

        if ($cup->exists) {
            return response()->json(['success'=>true,'message'=>'Waypoint List Created']);
        }
        return response()->json(['success' => false], 400);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'min:3|max:80',
            'description' => 'min:3|max:140',
        ]);

        $status = Cup::where('id',$id)->update($request->except(['_method','_token','code']));

        if ($status) {
            return response()->json(['success'=>true,'message'=>'Waypoint List Updated']);
        }
        return response()->json(['success' => false], 400);
    }

    public function destroy($id)
    {
        $status = Cup::query()->findOrFail($id)->delete();
        response()->json(['success'=>true,'message'=>'Waypoint List Deleted']);
    }

}
