{!! Modal::start($modal) !!}
<div class="form-group row">
    <div class="col-sm-12">
        <label for="load_profile" class="col-sm-6 col-form-label">Log in to be able to save your Contest Profile:</label>
        <button type="button" class="btn btn-success" id="load_profile" onclick="loadData()"><i class="fa fa-check"></i>&nbsp;{{ 'Load Contest Profile' }}</button>
        <button type="button" class="btn btn-primary" id="save_profile" onclick="sendData()"><i class="fa fa-save"></i>&nbsp;{{ 'Save Profile for Next Time' }}</button>
    </div>
</div>
<div class="form-group row">
    <label for="input_contest_name" class="col-sm-4 col-form-label">Contest:</label>
    <div class="col-sm-6">
        <input type="hidden" class="form-control" id="input_id" name="contest_id" value="{{ isset($contest) ? $contest->id : old('contest_id')}}">
        <input type="text" class="form-control" id="input_contest_name" name="contest_name" disabled
            placeholder="Contest" value="{{ isset($contest) ? $contest->name : old('contest_name')}}">
    </div>
</div>
<div class="form-group row">
    <label for="input_classes_id" class="col-sm-4 col-form-label">Class:</label>
    <div class="col-sm-6">
        <select id="classes_id" name="classes_id" class="form-control" >
            @foreach ($contest->contestClass as $class)
            <option {{isset($contestEntry) ? $contestEntry->classes_id == $class['id'] ? 'selected' : '' : ''}} value="{{$class['id']}}">
                {{$class['name']}}
            </option>
            @endforeach
        </select>

    </div>
</div>
<div class="panel-group">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5 class="panel-title dropdown-toggle">
                <a data-toggle="collapse" href="#collapse1">Pilot Details</a>
            </h5>
        </div>
        <div id="collapse1" class="panel-collapse collapse">
            <div class="form-group row">
                <label for="input_first_name" class="col-sm-4 col-form-label">First Name:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="input_first_name" name="first_name"
                        placeholder="First Name" value="{{ isset($contestEntry) ? $contestEntry->first_name : old('first_name')}}">
                </div>
            </div>
            <div class="form-group row">
                <label for="input_last_name" class="col-sm-4 col-form-label">Last Name:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="input_last_name" name="last_name"
                        placeholder="Last name" value="{{ isset($contestEntry) ? $contestEntry->last_name : old('last_name')}}">
                </div>
            </div>
            <div class="form-group row">
                <label for="input_is_copilot" class="col-sm-4 col-form-label">Is Copilot Pilot for another Entry:</label>
                <div class="col-sm-6">
                    <input type="hidden" name="is_copilot" value="0">
                    <input type="checkbox" class="form-control" id="input_is_copilot" name="is_copilot"
                    placeholder="is 2nd Pilot" value="1" {{ old('is_copilot') ? 'checked' :''}}>
                </div>
            </div>
            <div class="form-group row">
                <label for="input_mobile" class="col-sm-4 col-form-label">Mobile:</label>
                <div class="col-sm-6">
                    <input type="tel" class="form-control" id="input_mobile" name="mobile"
                        placeholder="Mobile Phone No" value="{{ isset($contestEntry) ? $contestEntry->mobile : old('mobile')}}">
                </div>
            </div>
            <div class="form-group row">
                <label for="input_email" class="col-sm-4 col-form-label">email:</label>
                <div class="col-sm-6">
                    <input type="email" class="form-control" id="input_email" name="email"
                        placeholder="Email" value="{{ isset($contestEntry) ? $contestEntry->email : (isset($email) ? $email : old('email'))}}">
                </div>
            </div>
            <div class="form-group row">
                <label for="input_address_1" class="col-sm-4 col-form-label">Address:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="input_address_1" name="address_1"
                        placeholder="Address Line 1" value="{{ isset($contestEntry) ? $contestEntry->address_1 : old('address_1')}}">
                </div>
            </div>
            <div class="form-group row">
                <label for="input_address_2" class="col-sm-4 col-form-label"></label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="input_address_2" name="address_2"
                        placeholder="Address Line 2" value="{{ isset($contestEntry) ? $contestEntry->address_2 : old('address_2')}}">
                </div>
            </div>
            <div class="form-group row">
                <label for="input_address_3" class="col-sm-4 col-form-label"></label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="input_address_3" name="address_3"
                        placeholder="Address Line 3" value="{{ isset($contestEntry) ? $contestEntry->address_3 : old('address_3')}}">
                </div>
            </div>
            <div class="form-group row">
                <label for="input_club" class="col-sm-4 col-form-label">Club:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="input_club" name="club"
                        placeholder="Club" value="{{ isset($contestEntry) ? $contestEntry->club : old('club')}}">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="panel-group">
    <div class="panel panel-default ">
        <div class="panel-heading">
            <h5 class="panel-title  dropdown-toggle">
                <a data-toggle="collapse" href="#collapse2">Emergency Contact Details</a>
            </h5>
        </div>
        <div id="collapse2" class="panel-collapse collapse">
            <div class="form-group row">
                <label for="input_e_contact" class="col-sm-4 col-form-label">Emergency Contact:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="input_e_contact" name="e_contact"
                        placeholder="Emergency Contact Name" value="{{ isset($contestEntry) ? $contestEntry->e_contact : old('e_contact')}}">
                </div>
            </div>
            <div class="form-group row">
                <label for="input_e_mobile" class="col-sm-4 col-form-label">Mobile:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="input_e_mobile" name="e_mobile"
                        placeholder="Emergency Contact Mobile" value="{{ isset($contestEntry) ? $contestEntry->e_mobile : old('e_mobile')}}">
                </div>
            </div>
            <div class="form-group row">
                <label for="input_e_phone" class="col-sm-4 col-form-label">Phone:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="input_address_2" name="address_2"
                        placeholder="Emergency Contact Phone" value="{{ isset($contestEntry) ? $contestEntry->address_2 : old('address_2')}}">
                </div>
            </div>
            <div class="form-group row">
                <label for="input_e_email" class="col-sm-4 col-form-label">Email</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="input_e_email" name="e_email"
                        placeholder="Emergency Contact Email" value="{{ isset($contestEntry) ? $contestEntry->e_email : old('e_email')}}">
                </div>
            </div>
            <div class="form-group row">
                <label for="input_e_address_1" class="col-sm-4 col-form-label">Address:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="input_e_address_1" name="e_address_1"
                        placeholder="Address Line 1" value="{{ isset($contestEntry) ? $contestEntry->e_address_1 : old('e_address_1')}}">
                </div>
            </div>
            <div class="form-group row">
                <label for="input_e_address_2" class="col-sm-4 col-form-label"></label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="input_e_address_2" name="e_address_2"
                        placeholder="Address Line 2" value="{{ isset($contestEntry) ? $contestEntry->e_address_2 : old('e_address_2')}}">
                </div>
            </div>
            <div class="form-group row">
                <label for="input_e_address_3" class="col-sm-4 col-form-label"></label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="input_e_address_3" name="e_address_3"
                        placeholder="Address Line 3" value="{{ isset($contestEntry) ? $contestEntry->e_address_3 : old('e_address_3')}}">
                </div>
            </div>
            <div class="form-group row">
                <label for="input_e_relationship" class="col-sm-4 col-form-label">Relationship</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="input_e_relationship" name="e_relationship"
                        placeholder="Relationship to you" value="{{ isset($contestEntry) ? $contestEntry->e_relationship : old('e_relationship')}}">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="panel-group">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5 class="panel-title  dropdown-toggle">
                <a data-toggle="collapse" href="#collapse3">Glider Details</a>
            </h5>
        </div>
        <div id="collapse3" class="panel-collapse collapse">
            <div class="form-group row">
                <label for="input_glider" class="col-sm-4 col-form-label">Glider ID:</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control" id="input_glider" name="glider" maxlength="2"
                        placeholder="Registration ZK-G__" value="{{ isset($contestEntry) ? $contestEntry->glider : old('glider')}}">
                </div>
            </div>
            <div class="form-group row">
                <label for="input_type" class="col-sm-4 col-form-label">Type:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="input_type" name="type"
                        placeholder="Glider Type" value="{{ isset($contestEntry) ? $contestEntry->type : old('type')}}">
                </div>
            </div>
            <div class="form-group row">
                <label for="input_has_tracker" class="col-sm-4 col-form-label">Has Tracking Device (Flarm/SPOT/Mobile):</label>
                <div class="col-sm-6">
                    <input type="hidden" name="has_tracker" value="0">
                    <input type="checkbox" class="form-control" id="has_tracker" name="has_tracker"
                    placeholder="" value="1" {{ old('has_tracker') ? 'checked' :''}}>
                </div>
            </div>
            <div class="form-group row">
                <label for="input_wingspan" class="col-sm-4 col-form-label">Wingspan:</label>
                <div class="col-sm-6">
                    <input type="number" class="form-control" id="input_wingspan" name="wingspan" min="10.0" max="30" step="0.1"
                        placeholder="Wingspan (meters)" value="{{ isset($contestEntry) ? $contestEntry->wingspan : old('wingspan')}}" >
                </div>
            </div>
            <div class="form-group row">
                <label for="input_handicap" class="col-sm-4 col-form-label">Handicap:</label>
                <div class="col-sm-6">
                    <input type="number" class="form-control" id="input_handicap" name="handicap" min="0.5" max="1.5" step="0.005"
                        placeholder="Handicap Claimed" value="{{ isset($contestEntry) ? $contestEntry->handicap : old('handicap')}}">
                </div>
            </div>
            <div class="form-group row">
                <label for="input_winglets" class="col-sm-4 col-form-label">Winglets:</label>
                <div class="col-sm-6">
                    <input type="hidden" name="winglets" value="0">
                    <input type="checkbox" class="form-control" id="input_winglets" name="winglets"
                    placeholder="Winglets fitted" value="1" {{ old('winglets') ? 'checked' :''}}>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="panel-group">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5 class="panel-title dropdown-toggle">
                <a data-toggle="collapse" href="#collapse4">Car, Trailer and Crew</a>
            </h5>
        </div>
        <div id="collapse4" class="panel-collapse collapse">
            <div class="form-group row">
                <label for="input_crew_name" class="col-sm-4 col-form-label">Crew Name:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="input_crew_name" name="crew_name"
                        placeholder="Crew Name" value="{{ isset($contestEntry) ? $contestEntry->crew_name : old('crew_name')}}">
                </div>
            </div>
            <div class="form-group row">
                <label for="input_c_phone" class="col-sm-4 col-form-label">Crew Phone:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="input_c_phone" name="c_phone"
                        placeholder="Crew Phone" value="{{ isset($contestEntry) ? $contestEntry->c_phone : old('c_phone')}}">
                </div>
            </div>
            <div class="form-group row">
                <label for="input_car_type" class="col-sm-4 col-form-label">Car:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="input_car_type" name="car_type"
                        placeholder="Car Make and Model" value="{{ isset($contestEntry) ? $contestEntry->car_type : old('car_type')}}">
                </div>
            </div>
            <div class="form-group row">
                <label for="input_car_color" class="col-sm-4 col-form-label">Colour:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="input_car_color" name="car_color"
                        placeholder="Car Colour" value="{{ isset($contestEntry) ? $contestEntry->car_color : old('car_color')}}">
                </div>
            </div>
            <div class="form-group row">
                <label for="input_car_plate" class="col-sm-4 col-form-label">Number:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="input_car_plate" name="car_plate"
                        placeholder="Car Number Plate" value="{{ isset($contestEntry) ? $contestEntry->car_plate : old('car_plate')}}">
                </div>
            </div>
            <div class="form-group row">
                <label for="input_trailer_type" class="col-sm-4 col-form-label">Trailer:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="input_trailer_type" name="trailer_type"
                        placeholder="Trailer Make" value="{{ isset($contestEntry) ? $contestEntry->trailer_type : old('trailer_type')}}">
                </div>
            </div>
            <div class="form-group row">
                <label for="input_trailer_color" class="col-sm-4 col-form-label">Color:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="input_trailer_color" name="trailer_color"
                        placeholder="Trailer Colour" value="{{ isset($contestEntry) ? $contestEntry->trailer_color : old('trailer_color')}}">
                </div>
            </div>
            <div class="form-group row">
                <label for="input_trailer_plate" class="col-sm-4 col-form-label">Number:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="input_trailer_plate" name="trailer_plate"
                        placeholder="Trailer Number Plate" value="{{ isset($contestEntry) ? $contestEntry->trailer_plate : old('trailer_plate')}}">
                </div>
            </div>
            <div class="form-group row">
                <label for="input_crew_notes" class="col-sm-4 col-form-label">Crew Notes:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="input_crew_notes" name="crew_notes"
                        placeholder="Notes for your Crew" value="{{ isset($contestEntry) ? $contestEntry->crew_notes : old('crew_notes')}}">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="panel-group">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5 class="panel-title  dropdown-toggle">
                <a data-toggle="collapse" href="#collapse5">Contest Conditions of Entry</a>
            </h5>
        </div>
        <div id="collapse5" class="panel-collapse collapse">
            <textarea rows="30" cols="60" class="form-control" disabled id="contest_terms_id">
                {{ isset($contest) ? $contest->terms : old('terms')}}
            </textarea>
            <div class="form-group row">
                <label for="input_declaration" class="col-sm-4 col-form-label">I accept the Contest Entry Conditions:</label>
                <div class="col-sm-6">
                    <input type="hidden" name="declaration" value="0">
                    <input type="checkbox" class="form-control" id="input_declaration" name="declaration"
                    placeholder="declaration" value="1" {{ old('declaration') ? 'checked' :''}}>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal footer -->
<div class="modal-footer">
    <!--button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp;{{ 'Close' }}</button-->
    <button type="submit" class="btn btn-success" ><i class="fa fa-save"></i>&nbsp;{{ 'Enter Contest' }}</button>
</div>

</form>

<script type="text/javascript">

    function sendData() {

        $.ajax({
            url: "savedata",
            type: "POST",
            data: $('#modal_form').serialize(),
            cache: false,
            async: true,
            success: function (data) {
                alert(data);
            }
        });
    }

    function loadData() {

        $.ajax({
            url: "loaddata",
            type: "POST",
            data: {},
            dataType: "json",
            cache: false,
            async: true,
            success: function (data) {
                result = $.parseJSON(data);

                // reset form values from json object
                $.each(result, function(name, val){
                    var $el = $('[name="'+name+'"]'),
                    type = $el.attr('type');

                    switch(type){
                        case 'hidden':
                        case 'checkbox':
                            $el.attr('checked', 'checked');
                            break;
                        case 'radio':
                            $el.filter('[value="'+val+'"]').attr('checked', 'checked');
                            break;
                        default:
                            $el.val(val);
                    }
                });
                alert ('Data Loaded');

            }
        });


    }

</script>