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
<div class="form-group row">
    <div class="col-sm-6" id="used_classes">
    <label for="selected_class" class="col-sm-2 col-form-label">Classes:</label>
        <select id="selected_class" name="contestClass[]" class="form-control" multiple>
            @foreach ($contest->contestClass as $class)
            <option value="{{$class->id}}">
                {{$class->name}}
            </option>
            @endforeach
        </select>
    </div>
    @can('contest-admin')
    <div class="col-sm-6" id="unused_classes">
    <label for="available_class" class="col-sm-6 col-form-label">Available:</label>
        <select id="available_class" class="form-control" >
            @foreach ($classes as $key => $item)
            <option value="{{$key}}">
                {{$item}}
            </option>
            @endforeach
        </select>
    </div>
    @endcan
</div>
@can('contest-admin')
<div class="form-group row">
    <div class="col-sm-6 pull-right">
        <input type="button" class="btn btn-danger" value="Remove Class" onclick="RemoveClass();">
    </div>
    <div class="col-sm-6">
        <input type="button" class="btn btn-success" value="Add Class" onclick="AddClass();">
    </div>
</div>

</div>
<!-- Modal footer -->
<div class="modal-footer">
    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp;{{ 'Close' }}</button>
    <button type="submit" class="btn btn-success" id="modal_submit"><i class="fa fa-floppy-o"></i>&nbsp;{{ 'Save' }}</button>
</div>
</form>
@endcan

<script>
    $('#input_practice').daterangepicker({
        singleDatePicker: true,
        locale: {
            format: 'YYYY-MM-DD',
            separator: ' - ',
        }
    });
    $('#input_start').daterangepicker({
        singleDatePicker: true,
        locale: {
            format: 'YYYY-MM-DD',
            separator: ' - ',
        }
    });
    $('#input_end').daterangepicker({
        singleDatePicker: true,
        locale: {
            format: 'YYYY-MM-DD',
            separator: ' - ',
        }
    });

    $('#modal_form').on('click', '#modal_submit', function(e){
        $('#selected_class option').prop('selected', true);
        $('#available_class option').prop('selected', true);
    });

    function AddClass()
    {
        var selected = $('#available_class option:selected');
        selected.appendTo('#selected_class');
    }


    function RemoveClass()
    {
        var selected = $('#selected_class option:selected');
        selected.appendTo('#available_class');
    }

    function SelectAll()
    {
        $('#selected_class option').prop('selected', true);
    }

</script>