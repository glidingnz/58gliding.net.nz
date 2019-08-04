<?php

namespace App\Http\Controllers\Apps;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\ContestClass;
use App\Grids\ClassesGrid;
use App\Grids\ClassesGridInterface;

use Form;
use Gate;

class ContestClassesController extends Controller
{
    public function index(Request $request)
    {
        $contests = ContestClass::class;

        return (new ClassesGrid(['contests' => $contests]))
        ->create(['query' => ContestClass::query(), 'request' => $request])
        ->renderOn('contestClasses.index');
    }

    public function create(Request $request)
    {
        if (!Gate::allows('contest-admin')) return response()->json(['success' => false], 401);

        $contestClass = new ContestClass;
        $modal = [
            'model' => 'Contest Class',
            'route' => route('contestclasses.store'),
            'action' => 'Create',
            'pjaxContainer' => $request->get('ref'),
        ];

        // modal
        return view('contestClasses.show-modal', compact('modal','contestClass'))->render();
    }

    public function show($id, Request $request)
    {
        $contestClass = ContestClass::query()->findOrFail($id);
        $modal = [
            'model' => 'Contest Class',
            'route' => route('contestclasses.show',['contestclass'=>$contestClass->id]),
            'method' => 'PATCH',
            'action' => 'Show',
            'pjaxContainer' => $request->get('ref'),
        ];

        // modal
        return view('contestClasses.show-modal', compact('modal','contestClass'))->render();
    }

    public function store(Request $request)
    {
        if (!Gate::allows('contest-admin')) return response()->json(['success' => false], 401);

        $this->validate($request, [
            'name' => 'unique:classes,name',
        ]);

        $contestClass = ContestClass::create($request->except(['_method','_token']));

        if ($contestClass->exists) {
            return response()->json(['success'=>true,'message'=>'Contest Class Created']);
        }
        return response()->json(['success' => false], 400);
    }

    public function update(Request $request, $id)
    {
        if (!Gate::allows('contest-admin')) return response()->json(['success' => false], 401);

        $this->validate($request, [
            //'name' => 'unique:classes,name',
        ]);

        $status = ContestClass::where('id',$id)->update($request->except(['_method','_token','code']));

        if ($status) {
            return response()->json(['success'=>true,'message'=>'Contest Class Updated']);
        }
        return response()->json(['success' => false], 400);
    }

    public function destroy($id)
    {
        if (!Gate::allows('contest-admin')) return response()->json(['success' => false], 401);

        $status = ContestClass::query()->findOrFail($id)->delete();
        response()->json(['success'=>true,'message'=>'Contest Class Deleted']);
    }
}
