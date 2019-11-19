<?php

namespace App\Http\Controllers\Apps;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;

use App\Models\Cup;
use App\Models\Waypoint;
use App\Grids\CupsGrid;
use App\Grids\CupsGridInterface;

use Gate;

class CupsController extends Controller
{

    public function index(Request $request)
    {
        $cups = Cup::class;

        return (new CupsGrid(['cups' => $cups]))
        ->create(['query' => Cup::query(), 'request' => $request])
        ->renderOn('cups.index');
    }

    public function create(Request $request){
        if (!Gate::allows('waypoint-admin')) return response()->json(['success' => false], 401);

        $cup = new Cup;
        $modal = [
            'model' => class_basename(Cup::class),
            'route' => route('cups.store'),
            'action' => 'create',
            'pjaxContainer' => $request->get('ref'),
        ];

        // modal
        return view('cups.show-modal', compact('modal','cup'))->render();
    }

    /**
    * Display the specified resource.
    *
    * @param  int $id
    * @param Request $request
    * @return \Illuminate\Http\Response
    * @throws \Throwable
    */
    public function show($id, Request $request){
        $cup = Cup::query()->findOrFail($id);

        $modal = [
            'model' => class_basename(Cup::class),
            'route' => route('cups.show',['cup'=>$cup->id]),
            'method' => 'PATCH',
            'action' => 'show',
            'pjaxContainer' => $request->get('ref'),
        ];

        // modal
        return view('cups.show-modal', compact('modal','cup'))->render();
    }

    public function attach(Request $request, $id){

        $cup = Cup::query()->findOrFail($id);

        if ($request->input('_method')=='PATCH') {
            foreach($request->input('waypoint_id') as $waypoint)$cup->waypoints()->attach($waypoint);
            return response()->json(['success'=>true,'message'=>'Waypoint List Updated']);
        }

        $waypoints = Waypoint::select()->whereNotIn('id',$cup->waypoints()->pluck('waypoint_id'))->get();

        $modal = [
            'model' => class_basename(Cup::class),
            'route' => "/cups/attach/$cup->id",
            'method' => 'PATCH',
            'action' => 'cup.attach',
            'pjaxContainer' => $request->get('ref'),
        ];

        // modal
        return view('cups.attach-modal', compact('modal','cup','waypoints'))->render();
    }

    public function detach(Request $request, $id){

        $cup = Cup::query()->findOrFail($id);

        if ($request->input('_method')=='PATCH') {
            foreach($request->input('waypoint_id') as $waypoint)$cup->waypoints()->detach($waypoint);
            return response()->json(['success'=>true,'message'=>'Waypoint List Updated']);
        }

        $waypoints = $cup->waypoints()->get();

        $modal = [
            'model' => class_basename(Cup::class),
            'route' => "/cups/detach/$cup->id",
            'method' => 'PATCH',
            'action' => 'cup.detach',
            'pjaxContainer' => $request->get('ref'),
        ];

        // modal
        return view('cups.detach-modal', compact('modal','cup','waypoints'))->render();
    }
    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request $request
    * @return JsonResponse|\Illuminate\Http\Response
    */
    public function store(Request $request){
        if (!Gate::allows('waypoint-admin')) return response()->json(['success' => false], 401);

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

    public function update(Request $request, $id){
        if (!Gate::allows('waypoint-admin')) return response()->json(['success' => false], 401);

        $this->validate($request, [
            'name' => 'min:3|max:80',
            'description' => 'min:3|max:140',
        ]);

        $status = Cup::where('id',$id)->update($request->except(['_method','_token','code','waypoint_id']));

        if ($status) {
            //if (isset($request->input('waypoint_id')))
            //    Cup::where('id',$id)->waypoints()->attach($request->input('waypoint_id'));

            return response()->json(['success'=>true,'message'=>'Waypoint List Updated']);
        }
        return response()->json(['success' => false], 400);
    }

    public function download(Request $request, $id){

        $cup = Cup::findOrFail($id);

        $headers = [
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Content-type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename={$cup->name}.cup",
            'Expires'             => '0',
            'Pragma'              => 'public',
        ];

        $list = $cup->waypoints()->select('name','code','country','lat','long','elevation','style','direction','length','frequency','description')->get()->toArray();

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
            fclose($FH);
        };

        return Response::stream($callback, 200, $headers);


        return Redirect::back();

    }

    public function airspace($id) {
        $cup = Cup::query()->findOrFail($id);
        return NULL != $cup->airspace
            ? response()->download(public_path('airspace/'.$cup->airspace))
            : Redirect::back();
    }

    public function destroy($id){
        if (!Gate::allows('waypoint-admin')) return response()->json(['success' => false], 401);

        $status = Cup::query()->findOrFail($id)->delete();
        response()->json(['success'=>true,'message'=>'Waypoint List Deleted']);
    }

}
