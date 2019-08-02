{!! Modal::start($modal) !!}
<div class="form-group row">
    <label for="input_name" class="col-sm-2 col-form-label">Name:</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="input_name" name="name"
            placeholder="Contest Name" value="{{ isset($contest) ? $contest->name : old('name')}}">
    </div>
</div>
<div class="form-group row">
    <label for="input_description" class="col-sm-2 col-form-label">Description:</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="input_description" name="description"
            placeholder="Contest Description" value="{{ isset($contest) ? $contest->description : old('description')}}">
    </div>
</div>
<div class="form-group row">
    <label for="input_location" class="col-sm-2 col-form-label">Location:</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="input_location" name="location"
            placeholder="Contest Location" value="{{ isset($contest) ? $contest->location : old('location')}}">
    </div>
</div>
<div class="form-group row">
    <label for="input_practice" class="col-sm-2 col-form-label">Practice:</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="input_practice" name="practice"
            placeholder="Practice Date" value="{{ isset($contest) ? $contest->practice : old('practice')}}">
    </div>
</div>
<div class="form-group row">
    <label for="input_start" class="col-sm-2 col-form-label">Start:</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="input_start" name="start"
            placeholder="Start Date" value="{{ isset($contest) ? $contest->start : old('start')}}">
    </div>
</div>
<div class="form-group row">
    <label for="input_end" class="col-sm-2 col-form-label">End:</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="input_end" name="end"
            placeholder="End Date" value="{{ isset($contest) ? $contest->end : old('end')}}">
    </div>
</div>
@can('contest-admin')
{!! Modal::end() !!}
@endcan
<script>
    $( "#input_practice" ).daterangepicker({
        singleDatePicker: true,
        locale: {
            format: "YYYY-MM-DD",
            separator: " - ",
        }
    });
    $( "#input_start" ).daterangepicker({
        singleDatePicker: true,
        locale: {
            format: "YYYY-MM-DD",
            separator: " - ",
        }
    });
    $( "#input_end" ).daterangepicker({
        singleDatePicker: true,
        locale: {
            format: "YYYY-MM-DD",
            separator: " - ",
        }
    });
</script>