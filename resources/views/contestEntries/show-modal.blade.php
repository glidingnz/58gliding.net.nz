{!! Modal::start($modal) !!}
<div class="panel-group">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5 class="panel-title">
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
                <label for="input_is_copilot" class="col-sm-4 col-form-label">Is 2nd Pilot:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="input_is_copilot" name="is_copilot"
                        placeholder="is 2nd Pilot" value="{{ isset($contestEntry) ? $contestEntry->is_copilot : old('is_copilot')}}">
                </div>
            </div>
            <div class="form-group row">
                <label for="input_mobile" class="col-sm-4 col-form-label">Mobile:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="input_mobile" name="mobile"
                        placeholder="Mobile Phone No" value="{{ isset($contestEntry) ? $contestEntry->mobile : old('mobile')}}">
                </div>
            </div>
            <div class="form-group row">
                <label for="input_email" class="col-sm-4 col-form-label">email:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="input_email" name="email"
                        placeholder="Email" value="{{ isset($contestEntry) ? $contestEntry->email : old('email')}}">
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
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5 class="panel-title">
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
        </div>
    </div>
</div>
@can('contest-admin')
{!! Modal::end() !!}
@endcan