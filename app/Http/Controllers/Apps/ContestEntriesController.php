<?php

namespace App\Http\Controllers\Apps;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Redirector;

use App\Models\ContestEntry;
use App\Grids\EntriesGrid;
use App\Grids\EntriesGridInterface;
use App\Models\Contest;
use App\Models\ContestProfile;

Use App\Http\Requests\ContestEntryRequest;

use Form;
use Gate;
use Auth;

class ContestEntriesController extends Controller
{
    public function index(Request $request)
    {
        //Check User is Logged In

        if(!Auth::check())
        {
            return redirect('login')->withInput()->with('errmessage', 'Please Login to access your Contest Entries');
        }
        $entries = ContestEntry::class;

        if (Gate::allows('contest-admin')) {
            // Return All Contest Entries
            return (new EntriesGrid(['entries' => $entries]))
            ->create(['query' => ContestEntry::query()->with(['contestClass','contest']), 'request' => $request])
            ->renderOn('contestEntries.index');
        }
        else {
            // Return Only Contest Entries for Logged in User
            return (new EntriesGrid(['entries' => $entries]))
            ->create(['query' => ContestEntry::query()->with(['contestClass','contest'])->where('email','=',auth()->user()->email), 'request' => $request])
            ->renderOn('contestEntries.index');
        }

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

    public function store(ContestEntryRequest $request)
    {
        $validated = $request->validated();

        $contestEntry = ContestEntry::create($request->except(['_method','_token','contest_name']));

        if ($contestEntry->exists) {
            return redirect('contestentries');
        }
        return redirect(null,500);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validated();

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

        if(!Auth::check()) {
            return 'Cant Save Data. Not Logged In';
        }
        $status = ContestProfile::updateOrCreate(['id'=>auth()->user()->id],$request->except(['token','contest_id','contest_name','classes_id']));
        return 'Data Saved';
    }

    public function loaddata()
    {
        $data = ContestProfile::find(auth()->user()->id);
        if ($data) $data = json_encode($data->toarray());
        return json_encode($data);
    }
}
