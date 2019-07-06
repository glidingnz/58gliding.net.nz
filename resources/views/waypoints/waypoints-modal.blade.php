{!! Modal::start($modal) !!}
<div class="form-group row">
    <label for="input_code" class="col-sm-2 col-form-label">Code:</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="input_code" name="code"
            placeholder="Unique code" value="{{ isset($waypoint) ? $waypoint->code : old('code')}}">
    </div>
</div>
<div class="form-group row">
    <label for="input_name" class="col-sm-2 col-form-label">Name:</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="input_name" name="name"
            placeholder="Enter name" value="{{ isset($waypoint) ? $waypoint->name : old('name')}}">
    </div>
</div>
<div class="form-group row">
    <label for="input_description" class="col-sm-2 col-form-label">Description:</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="input_description" name="description"
            placeholder="Enter Description" value="{{ isset($waypoint) ? $waypoint->description : old('description')}}">
    </div>
</div>
<div class="form-group row">
    <label for="input_lat" class="col-sm-2 col-form-label">Latitude:</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="input_lat" name="lat"
            placeholder="Format DDDMM.MMM" value="{{ isset($waypoint) ? $waypoint->lat : old('lat')}}">
    </div>
</div>
<div class="form-group row">
    <label for="input_long" class="col-sm-2 col-form-label">Longitude:</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="input_long" name="long"
            placeholder="Format DDDMM.MMM" value="{{ isset($waypoint) ? $waypoint->long : old('long')}}">
    </div>
</div>
<div class="form-group row">
    <label for="input_elevation" class="col-sm-2 col-form-label">Elevation:</label>
    <div class="col-sm-10">
        <input type="integer" class="form-control" id="input_elevation" name="elevation"
            placeholder="Enter Elevation" value="{{ isset($waypoint) ? $waypoint->elevation : old('elevation')}}">
    </div>
</div>
<div class="form-group row">
    <label for="input_style" class="col-sm-2 col-form-label">Style:</label>
    <select name="style" id="input_style"  title="Style" class="col-sm-4 form-control"
        option="{{ isset($waypoint) ? $waypoint->style : old('style')}}">
        <option value="0" {{$waypoint->style==0 ? 'selected' : ''}}>Unknown</option>
        <option value="1" {{$waypoint->style==1 ? 'selected' : ''}}>Waypoint</option>
        <option value="2" {{$waypoint->style==2 ? 'selected' : ''}}>Grass Runway</option>
        <option value="3" {{$waypoint->style==3 ? 'selected' : ''}}>Outlanding</option>
        <option value="4" {{$waypoint->style==4 ? 'selected' : ''}}>Gliding Airfield</option>
        <option value="5" {{$waypoint->style==5 ? 'selected' : ''}}>Sealed Runway</option>
        <option value="6" {{$waypoint->style==6 ? 'selected' : ''}}>Mountain Pass</option>
        <option value="7" {{$waypoint->style==7 ? 'selected' : ''}}>Mountain Top</option>
        <option value="8" {{$waypoint->style==8 ? 'selected' : ''}}>Transmitter Mast</option>
        <option value="9" {{$waypoint->style==9 ? 'selected' : ''}}>VOR</option>
        <option value="10" {{$waypoint->style==10 ? 'selected' : ''}}>NDB</option>
        <option value="11" {{$waypoint->style==11 ? 'selected' : ''}}>Cooling Tower</option>
        <option value="12" {{$waypoint->style==12 ? 'selected' : ''}}>Dam</option>
        <option value="13" {{$waypoint->style==13 ? 'selected' : ''}}>Tunnel</option>
        <option value="14" {{$waypoint->style==14 ? 'selected' : ''}}>Bridge</option>
        <option value="15" {{$waypoint->style==15 ? 'selected' : ''}}>Power Plant</option>
        <option value="16" {{$waypoint->style==16 ? 'selected' : ''}}>Castle</option>
        <option value="17" {{$waypoint->style==17 ? 'selected' : ''}}>Intersection</option>
    </select>
</div>
<div class="form-group row">
    <label for="input_direction" class="col-sm-2 col-form-label">Rwy Direction:</label>
    <div class="col-sm-10">
        <input type="integer" class="form-control" id="input_direction" name="direction"
            placeholder="Runway Direction" value="{{ isset($waypoint) ? $waypoint->direction : old('direction')}}">
    </div>
</div>
<div class="form-group row">
    <label for="input_length" class="col-sm-2 col-form-label">Length:</label>
    <div class="col-sm-10">
        <input type="integer" class="form-control" id="input_length" name="length"
            placeholder="Runway Length" value="{{ isset($waypoint) ? $waypoint->length : old('length')}}">
    </div>
</div>
<div class="form-group row">
    <label for="input_frequency" class="col-sm-2 col-form-label">Frequency:</label>
    <div class="col-sm-10">
        <input type="integer" class="form-control" id="input_frequency" name="frequency"
            placeholder="Frequency" value="{{ isset($waypoint) ? $waypoint->frequency : old('frequency')}}">
    </div>
</div>
{!! Modal::end() !!}