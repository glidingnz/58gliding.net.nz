<?php

namespace App\Http\Controllers\Apps;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Contest;
use App\Grids\ContestsGrid;
use App\Grids\ContestsGridInterface;
use App\Models\ContestClass;
use Form;
use Gate;

class ContestsController extends Controller
{
    public function index(Request $request)
    {
        $contests = Contest::class;

        return (new ContestsGrid(['contests' => $contests]))
        ->create(['query' => Contest::query(), 'request' => $request])
        ->renderOn('contests.index');
    }

    public function create(Request $request)
    {
        if (!Gate::allows('contest-admin')) return response()->json(['success' => false], 401);

        $contest = new Contest;
        $classes = ContestClass::pluck('name','id')->except($contest->contestClass->pluck('id'));

        $modal = [
            'model' => class_basename(Contest::class),
            'route' => route('contests.store'),
            'action' => 'create',
            'pjaxContainer' => $request->get('ref'),
        ];

        // modal
        return view('contests.show-modal', compact('modal','contest','classes'))->render();
    }

    public function show($id, Request $request)
    {
        $contest = Contest::query()->findOrFail($id);
        $classes = ContestClass::pluck('name','id')->except($contest->contestClass->pluck('id'));

        $modal = [
            'model' => class_basename(Contest::class),
            'route' => route('contests.show',['contest'=>$contest->id]),
            'method' => 'PATCH',
            'action' => 'View',
            'pjaxContainer' => $request->get('ref'),
        ];

        // modal
        return view('contests.show-modal', compact('modal','contest','classes'))->render();
    }

    public function store(Request $request)
    {
        if (!Gate::allows('contest-admin')) return response()->json(['success' => false], 401);

        $this->validate($request, [
            'name' => 'unique:contests,name',
        ]);

        $contest = Contest::create($request->except(['_method','_token','contestClass']));


        if ($contest->exists) {
            $contest->contestClass()->sync($request->contestClass);
            return response()->json(['success'=>true,'message'=>'Contest Created']);
        }
        return response()->json(['success' => false], 400);
    }

    public function update(Request $request, $id)
    {
        if (!Gate::allows('contest-admin')) return response()->json(['success' => false], 401);

        $this->validate($request, [
            //'name' => 'unique:contests,name',
        ]);

        $status = Contest::where('id',$id)->update($request->except(['_method','_token','contestClass']));
        $contest = Contest::find($id);
        $contest->contestClass()->sync($request->contestClass);

        if ($status) {
            return response()->json(['success'=>true,'message'=>'Contest Updated']);
        }
        return response()->json(['success' => false], 400);
    }

    public function destroy($id)
    {
        if (!Gate::allows('contest-admin')) return response()->json(['success' => false], 401);

        $status = Contest::query()->findOrFail($id)->delete();
        response()->json(['success'=>true,'message'=>'Contest Deleted']);
    }
}
