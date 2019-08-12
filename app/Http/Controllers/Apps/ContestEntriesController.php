<?php

namespace App\Http\Controllers\Apps;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use App\Models\ContestEntry;
use App\Grids\EntriesGrid;
use App\Grids\EntriesGridInterface;
use App\Models\Contest;
use App\Models\ContestProfile;

use Form;
use Gate;

class ContestEntriesController extends Controller
{
    public function index(Request $request)
    {
        $entries = ContestEntry::class;

        return (new EntriesGrid(['entries' => $entries]))
        ->create(['query' => ContestEntry::query()->with(['contestClass','contest']), 'request' => $request])
        ->renderOn('contestEntries.index');
    }

    public function create(Request $request)
    {
        $contest = Contest::with('contestClass')->find($request->id);

        $modal = [
            'model' => 'Contest',
            'route' => route('contestentries.store'),
            'action' => 'Enter',
            'pjaxContainer' => $request->get('ref'),
        ];

        // modal
        return view('contestEntries.show', compact('modal','contest'))->render();
    }

    public function show($id, Request $request)
    {
        $contestEntry = ContestEntry::query()->findOrFail($id);
        $contest = Contest::with('contestClass')->find($contestEntry->contest_id);

        $modal = [
            'model' => 'Contest Entry',
            'route' => route('contestentries.show',['contestclass'=>$contestEntry->id]),
            'method' => 'PATCH',
            'action' => 'Show',
            'pjaxContainer' => $request->get('ref'),
        ];

        // modal
        return view('contestEntries.show-modal', compact('modal','contestEntry','contest'))->render();
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(),[
            'contest_name'=> 'required',
            'contest_class'=> 'required',
            'first_name' => 'required',
            'last_name'  => 'required',
            'is_copilot' => 'boolean',
            'mobile' => 'required|length:14|regex:/([+(\d]{1})(([\d+() -.]){5,16})([+(\d]{1})/',
            'email'=>'email',
            'address_1' =>'required',
            'club' => 'required',
            'e_contact' => 'required|length:14',
            'e_mobile' => 'required|regex:/([+(\d]{1})(([\d+() -.]){5,16})([+(\d]{1})/',
            'e_address_1' => 'required',
            'glider' => 'required_unless:is_copilot,1',
            'handicap' => 'numeric|required_unless:is_copilot,1',
            'wingspan' => 'numeric|required_unless:is_copilot,1',
        ]);

        if ($validator->fails()) {
            return redirect(route('contestentries.create',['id'=>$request->contest_id]))
            ->withErrors($validator)
            ->withInput();
        }

        $contestEntry = ContestEntry::create($request->except(['_method','_token','contest_name']));

        if ($contestEntry->exists) {
            return response()->json(['success'=>true,'message'=>'Contest Entry Created']);
        }
        return response()->json(['success' => false], 400);
    }

    public function update(Request $request, $id)
    {

        $this->validate($request,[
            'contest_name'=> 'required',
            //'contest_class'=> 'required',
            'first_name' => 'required',
            'last_name'  => 'required',
            'is_copilot' => 'boolean',
            'mobile' => 'required|regex:/([+(\d]{1})(([\d+() -.]){5,16})([+(\d]{1})/',
            'email'=>'email',
            'address_1' =>'required',
            'club' => 'required',
            'e_contact' => 'required',
            'e_mobile' => 'required|regex:/([+(\d]{1})(([\d+() -.]){5,16})([+(\d]{1})/',
            'e_address_1' => 'required',
            'glider' => 'required_unless:is_copilot,1',
            'handicap' => 'numeric|required_unless:is_copilot,1',
            'wingspan' => 'numeric|required_unless:is_copilot,1',
        ]);

        $status = ContestEntry::where('id',$id)->update($request->except(['_method','_token','contest_name']));

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

    public function savedata(Request $request)
    {
        $status = ContestProfile::updateOrCreate(['id'=>auth()->user()->id],$request->except(['token','contest_name','contest_class']));
        return ( $status ?  'Data Saved' : 'Cannot Save Data. No Profile');
    }

    public function loaddata()
    {
        $data = ContestProfile::find(auth()->user()->id);
        if ($data) $data = json_encode($data->toarray());
        return json_encode($data);
    }
}
