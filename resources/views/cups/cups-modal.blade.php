{!! Modal::start($modal) !!}
<div class="form-group row">
    <label for="input_name" class="col-sm-2 col-form-label">Name:</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="input_name" name="name"
            placeholder="Enter name" value="{{ isset($cup) ? $cup->name : old('name')}}">
    </div>
</div>
<div class="form-group row">
    <label for="input_description" class="col-sm-2 col-form-label">Description:</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="input_description" name="description"
            placeholder="Enter Description" value="{{ isset($cup) ? $cup->description : old('description')}}">
    </div>
</div>
<div class="panel-heading"><h5 class="panel-title">Turnpoints</h5></div>
<div class="panel panel-primary">
    <div class="list-group pre-scrollable" id="waypoint-list">
            @foreach ($cup->waypoints as $waypoint)
            <li class="list-group-item">{{$waypoint->code.'  -  '.$waypoint->name}}</li>
            @endforeach
    </div>
</div>
{!! Modal::end() !!}