<?php
namespace App\Http\Controllers\Apps;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Waypoint;
use App\Classes\WaypointsLibrary;

use App\Grids\WaypointsGrid;
use App\Grids\WaypointsGridInterface;

use Form;

class WaypointsController extends Controller
{
    //public function index()
    //{
    //    return view('waypoints/waypoints-list');
    //}

    public function index(Request $request)
    {
        $waypoints = Waypoint::class;

        return (new WaypointsGrid(['waypoints' => $waypoints]))
        ->create(['query' => Waypoint::query(), 'request' => $request])
        ->renderOn('waypoints.waypoints');
    }

    public function create(Request $request)
    {
        $waypoint = new Waypoint;
        $modal = [
            'model' => class_basename(Waypoint::class),
            'route' => route('waypoints.store'),
            'action' => 'create',
            'pjaxContainer' => $request->get('ref'),
        ];

        // modal
        return view('waypoints.waypoints-modal', compact('modal','waypoint'))->render();
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
        $waypoint = Waypoint::query()->findOrFail($id);
        $modal = [
            'model' => class_basename(Waypoint::class),
            'route' => route('waypoints.show',['waypoint'=>$waypoint->id]),
            'method' => 'PATCH',
            'action' => 'show',
            'pjaxContainer' => $request->get('ref'),
        ];

        // modal
        return view('waypoints.waypoints-modal', compact('modal','waypoint'))->render();
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
            'code' => 'unique:waypoints,code',
            'name' => 'min:3|max:80',
            'description' => 'min:3|max:80',
            'lat' => 'numeric',
            'long' => 'numeric',
            'elevation' => 'nullable|numeric',
            'direction' => 'nullable|numeric',
            'length' =>'nullable|numeric',
            'frequency'=>'nullable|numeric',
        ]);

        $waypoint = Waypoint::create($request->except(['_method','_token']));

        if ($waypoint->exists) {
            return response()->json(['success'=>true,'message'=>'Waypoint Created']);
        }
        return response()->json(['success' => false], 400);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            //'code' => 'unique:waypoints,code',
            'name' => 'min:3|max:80',
            'description' => 'min:3|max:80',
            'lat' => 'numeric',
            'long' => 'numeric',
            'elevation' => 'nullable|numeric',
            'direction' => 'nullable|numeric',
            'length' =>'nullable|numeric',
            'frequency'=>'nullable|numeric',
        ]);

        $status = Waypoint::where('id',$id)->update($request->except(['_method','_token','code']));

        if ($status) {
            return response()->json(['success'=>true,'message'=>'Waypoint Updated']);
        }
        return response()->json(['success' => false], 400);
    }

    public function destroy($id)
    {
        $status = Waypoint::query()->findOrFail($id)->delete();
        response()->json(['success'=>true,'message'=>'Waypoint Deleted']);
    }

    public function upload(Request $request)
    {

        $wp_lib = new WaypointsLibrary();

        $path = $request->file('waypoints')->store('waypoints');
        $waypoints = $wp_lib->process_cup_file($path);

        foreach ($waypoints AS $waypoint)
        {
            $waypoint->save();
        }

        return redirect('waypoints');
    }

}
