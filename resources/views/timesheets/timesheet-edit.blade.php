@extends('layouts.app')

@section('page-scripts')
<link href="{{ asset('/css/timesheet-edit.css')}}" rel='stylesheet' />
@endsection

@section('content')


<div class="container-flex" id="timesheet-edit">
    <form autocomplete="off">
        <div class="form-row" style="padding:1em">
            <div class="form-group col-4 col-sm-2 col-lg-1">
                <label for="inputGlider">Glider</label>
                <input type="text" class="form-control" id="inputGlider" maxlength="3" data-lpignore="true"
                    onkeyup="this.value = this.value.toUpperCase();">
            </div>
            <div class="form-group col-4 col-sm-2 col-lg-1">
                <label for="inputVector">Vector</label>
                <input type="text" class="form-control" id="inputVector"  maxlength="3" data-lpignore="true"
                    onkeyup="this.value = this.value.toUpperCase();">
            </div>
            <div class="form-group col-4 col-sm-2 col-lg-1">
                <label for="inputLaunch">Type</label>
                <input type="text" class="form-control" id="inputLaunch"  data-lpignore="true"
                    onkeyup="this.value = this.value.toUpperCase();">
            </div>
            <div class="form-group col-12 col-sm-6 col-lg-3">
                <label for="inputPIC">PIC</label>
                <input type="text" class="form-control" id="inputPIC"  data-lpignore="true">
            </div>
            <div class="form-group col-12 col-sm-6 col-lg-3">
                <label for="inputP2">P2</label>
                <input type="text" class="form-control" id="inputP2"  data-lpignore="true">
            </div>
            <div class="form-group col-12 col-sm-6 col-lg-3">
                <label for="inputTowPilotWinchDriver">Tow Pilot / Winch Driver</label>
                <input type="text" class="form-control" id="inputTowPilotWinchDriver"  data-lpignore="true">
            </div>
            <div class="form-group col-4 col-sm-2 col-lg-1">
                <label for="inputStart">Start</label>
                <input type="text" class="form-control" id="inputStart" maxlength="5" data-lpignore="true"
                    onkeyup="this.value = this.value.toUpperCase();">
            </div>
            <div class="form-group col-4 col-sm-2 col-lg-1">
                <label for="inputLand">Land</label>
                <input type="text" class="form-control" id="inputLand"  maxlength="5" data-lpignore="true"
                    onkeyup="this.value = this.value.toUpperCase();">
            </div>
            <div class="form-group col-4 col-sm-2 col-lg-1">
                <label for="inputHeight">Time</label>
                <input type="text" class="form-control" id="inputLaunch"  maxlength="5" data-lpignore="true"
                    onkeyup="this.value = this.value.toUpperCase();">
            </div>
            <div class="form-group col-8 col-sm-4 col-lg-3">
                <label for="inputBilling">Billing</label>
                <input type="text" class="form-control" id="inputBilling"  data-lpignore="true">
            </div>
            <div class="form-group col-4 col-sm-2 col-lg-1">
                <label for="inputHeight">Height</label>
                <input type="text" class="form-control" id="inputLaunch"  maxlength="5" data-lpignore="true"
                    onkeyup="this.value = this.value.toUpperCase();">
            </div>
            <div class="form-group col-12 col-sm-12 col-lg-5">
                <label for="inputComments">Comments</label>
                <input type="text" class="form-control" id="inputComments"  data-lpignore="true">
            </div>
            <div class="form-group col">
                <button type="submit" class="btn btn-primary">Close</button>
            </div>
        </div>
    </form>
</div>

@endsection
