<?php

namespace App\Http\Controllers\Apps;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\ContestEntry;
use App\Grids\EntriesGrid;
use App\Grids\EntriesGridInterface;
use App\Models\Contest;

use Form;
use Gate;

class ContestEntriesController extends Controller
{
    public function index(Request $request)
    {
        $entries = ContestEntry::class;

        return (new EntriesGrid(['entries' => $entries]))
        ->create(['query' => ContestEntry::query(), 'request' => $request])
        ->renderOn('contestEntries.index');
    }

    public function create(Request $request)
    {
        if (!Gate::allows('contest-admin')) return response()->json(['success' => false], 401);

        $contestEntry = new ContestEntry;
        $contestList = Contest::where('end','>',date('Y-m-d',time()))->pluck('name','id');

        $modal = [
            'model' => 'Contest',
            'route' => route('contestentries.store'),
            'action' => 'Enter',
            'pjaxContainer' => $request->get('ref'),
        ];

        // modal
        return view('contestEntries.show-modal', compact('modal','contestEntry','contestList'))->render();
    }

    public function show($id, Request $request)
    {
        $contestEntry = ContestEntry::query()->findOrFail($id);
        $modal = [
            'model' => 'Contest Entry',
            'route' => route('contestentries.show',['contestclass'=>$contestEntry->id]),
            'method' => 'PATCH',
            'action' => 'Show',
            'pjaxContainer' => $request->get('ref'),
        ];

        // modal
        return view('contestEntries.show-modal', compact('modal','contestEntry'))->render();
    }

    public function store(Request $request)
    {
        if (!Gate::allows('contest-admin')) return response()->json(['success' => false], 401);

        $this->validate($request, [
            'name' => 'unique:classes,name',
        ]);

        $contestEntry = ContestEntry::create($request->except(['_method','_token']));

        if ($contestEntry->exists) {
            return response()->json(['success'=>true,'message'=>'Contest Entry Created']);
        }
        return response()->json(['success' => false], 400);
    }

    public function update(Request $request, $id)
    {
        if (!Gate::allows('contest-admin')) return response()->json(['success' => false], 401);

        $this->validate($request, [
            //'name' => 'unique:classes,name',
        ]);

        $status = ContestEntry::where('id',$id)->update($request->except(['_method','_token','code']));

        if ($status) {
            return response()->json(['success'=>true,'message'=>'Contest Entry Updated']);
        }
        return response()->json(['success' => false], 400);
    }

    public function destroy($id)
    {
        if (!Gate::allows('contest-admin')) return response()->json(['success' => false], 401);

        $status = ContestEntry::query()->findOrFail($id)->delete();
        response()->json(['success'=>true,'message'=>'Contest Entry Deleted']);
    }
}
