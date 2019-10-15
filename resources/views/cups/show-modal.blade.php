<div class="modal-header">
    <h4 class="modal-title">Turnpoint List</h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<form accept-charset="UTF-8" action="{{ $modal['route'] }}" id="modal_form"
    data-pjax-target="#{{ $modal['pjaxContainer'] ?? null }}" method="POST">
<div class="modal-body">
<div id="modal-notification"></div>
@if(isset($modal['method']) && $modal['method'] != 'post')
<input type="hidden" name="_method" value="{{ $modal['method'] }}">
@endif
{!! csrf_field() !!}
<div class="form-group row">
    <label for="input_name" class="col-sm-2 col-form-label">Name:</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="input_name" name="name" maxlength="80"
            placeholder="Enter name" value="{{ isset($cup) ? $cup->name : old('name')}}">
    </div>
</div>
<div class="form-group row">
    <label for="input_description" class="col-sm-2 col-form-label">Description:</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="input_description" name="description" maxlength="140"
            placeholder="Enter Description" value="{{ isset($cup) ? $cup->description : old('description')}}">
    </div>
</div>
<div class="form-group row">
    <label for="input_airspace" class="col-sm-2 col-form-label">Airspace File:</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="input_airspace" name="airspace" maxlength="255"
            placeholder="Enter File Name" value="{{ isset($cup) ? $cup->airspace : old('airspace')}}">
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
@can('waypoint-admin')
{!! Modal::end() !!}
@endcan