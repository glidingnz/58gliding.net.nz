<?php
namespace App\Http\Controllers\Apps;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;

use App\Models\Waypoint;
use App\Models\Cup;
use App\Classes\WaypointsLibrary;

use App\Grids\WaypointsGrid;
use App\Grids\WaypointsGridInterface;

use Form;
use Gate;

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
        ->renderOn('waypoints.index');
    }

    public function create(Request $request)
    {

        if (!Gate::allows('waypoint-admin')) return response()->json(['success' => false], 401);

        $waypoint = new Waypoint;
        $modal = [
            'model' => class_basename(Waypoint::class),
            'route' => route('waypoints.store'),
            'action' => 'create',
            'pjaxContainer' => $request->get('ref'),
        ];

        // modal
        return view('waypoints.show-modal', compact('modal','waypoint'))->render();
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
        return view('waypoints.show-modal', compact('modal','waypoint'))->render();
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request $request
    * @return JsonResponse|\Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        if (!Gate::allows('waypoint-admin')) return response()->json(['success' => false], 401);

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
        if (!Gate::allows('waypoint-admin')) return response()->json(['success' => false], 401);

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
        if (!Gate::allows('waypoint-admin')) return response()->json(['success' => false], 401);

        $status = Waypoint::query()->findOrFail($id)->delete();
        response()->json(['success'=>true,'message'=>'Waypoint Deleted']);
    }

    public function upload(Request $request){

        if (!Gate::allows('waypoint-admin')) return response()->json(['success' => false], 401);

        if ($request->input('_method')=='POST') {

            $wp_lib = new WaypointsLibrary();

            $path = $request->file('waypoints')->store('waypoints');
            $imports = $wp_lib->process_cup_file($path);

            foreach ($imports AS $import)
            {
                $waypoint = Waypoint::updateOrCreate(['code'=>$import['code']],
                    [
                        'name' => $import['name'],
                        'code' => $import['code'],
                        'country' => $import['country'],
                        'lat' => $import['lat'],
                        'long' => $import['long'],
                        'style' => $import['style'],
                        'elevation' => $import['elevation'],
                        'length' => $import['length'],
                        'direction' => $import['direction'],
                        'frequency' => $import['frequency'],
                        'description' => $import['description'],
                ]);
                $waypoint->save();
            }
            // Cannot redirect to modal form that uses a "File" because of pJax bug
            return Redirect::back();
        }

        $modal = [
            'model' => 'Waypoints',
            'route' => route('waypoints.upload'),
            'method' => 'POST',
            'action' => 'Upload',
            'pjaxContainer' => $request->get('ref'),
        ];

        // modal
        return view('waypoints.upload-modal',compact('modal'))->render();

    }

    public function download(Request $request){


        $headers = [
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Content-type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=All-Waypoints.cup",
            'Expires'             => '0',
            'Pragma'              => 'public',
        ];

        //Get a list of all waypoints and write them to the export file
        $waypoints = Waypoint::query();
        $list = $waypoints->select('name','code','country','lat','long','elevation','style','direction','length','frequency','description')->get()->toArray();

        # add headers for each column in the CSV download
        array_unshift($list, array_keys($list[0]));

        $callback = function() use ($list)
        {

            $FH = fopen('php://output', 'w');
            fputcsv($FH, ['name','code','country','lat','lon','elev','style','rwdir','rwlen','freq','desc']);
            unset($list[0]);
            foreach ($list as $row) {
                // add the code to the name
                if ($row['code']!='' && $row['code']!=null) $row['name'] = $row['code'] . ' ' . $row['name'];
        
                $row['elevation'] .= 'ft';
                $row['lat'] = $row['lat']  < 0 ? sprintf("%08.3f",abs($row['lat']))."S" : sprintf("%08.3f",$row['lat'])."N";
                $row['long'] = $row['long'] < 0 ? sprintf("%09.3f",abs($row['long']))."W" : sprintf("%09.3f",$row['long'])."E";
                fputcsv($FH, $row);
            }

            //Get a list of all Waypoint Groups and add them to the export file as a SeeYou Task

            fputcsv($FH, ['-----Related Tasks-----']);

            $cups = Cup::all();

            foreach ($cups as $cup){
                $list = $cup->waypoints()->pluck('name')->toArray();
                array_unshift($list,$cup->name,'???');
                array_push($list,'???');
                fputcsv($FH, $list);
            }
            fclose($FH);
        };

        return Response::stream($callback, 200, $headers);


        return Redirect::back();
    }

}
