<div class="modal-header">
    <h4 class="modal-title">Attach Turnpoints to List</h4>
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

@can('waypoint-admin')
<div class="panel-heading"><h5 class="panel-title">Select Turnpoints</h5></div>
<div class="form-group row">
    <label for="waypoint" class="col-sm-4 col-form-label">Select Waypoint</label>
    <div class="col-sm-4">
        <select multiple='multiple' name="waypoint_id[]" id="waypoint" class="form-control" size=12>
            @foreach ($waypoints as $waypoint)
            <option value="{{$waypoint->id}}">
                {{$waypoint->code}}
            </option>
            @endforeach
        </select>
    </div>
</div>
@endcan

@can('waypoint-admin')
{!! Modal::end() !!}
@endcan