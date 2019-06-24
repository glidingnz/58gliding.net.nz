@extends('layouts.app')

@section('content')

<style>
.gnz-details-label {
	text-align: right;
}
</style>


<div class="container">

	<h1>My Account</h1>

	<form action="/update-account" method="post" class="form-horizontal">
		<?php /*
		<div class="form-group">
			<label class="col-sm-2 control-label" for="userUsername">Username</label>
			<div class="col-sm-5">
				<input class="form-control" type="text" id="userUsername" name="userUsername" value="">
			</div>
			<div class="col-sm-5 form-help">By default this is your email address. You can choose something shorter if desired.</div>
		</div>
		*/ ?>
		{{ csrf_field() }}
		<div class="form-group">
			<label class="col-sm-2 control-label" for="email">Email</label>
			<div class="col-sm-5">
				<input class="form-control" type="text" id="email" name="email" size="40" value="{{ old('email', $user->email) }}">
				</div>

				<div class="col-sm-offset-2 col-sm-5" style="margin-top: 10px;">
					<?php if ($user->activated) { ?>
						<span class="success"><i class="fa fa-check-square-o"></i> Email Validated</span>
					<?php } else { ?>
						<span class="error"><i class="fa fa-exclamation-circle"></i> Email Not Validated</span>
						<input value="Re-send Validation Email" name="resend-validation" type="submit" class="btn btn-outline-dark btn-sm">
					<?php }  ?>
				</div>
		</div>


		<div class="form-group">
			<label class="col-sm-2 control-label" for="gnz_id">GNZ Number</label>
			<div class="col-sm-5">
				<input class="form-control" type="text" id="gnz_id" name="gnz_id" value="{{ old('gnz_id', $user->gnz_id) }}">
			</div>

			<div class="col-sm-5 form-help">To validate your GNZ membership your email address must match what's in the GNZ database. Email <a href="mailto:membership@gliding.co.nz">membership@gliding.co.nz</a> if your email address has changed recently.</div>
			<div class="col-sm-offset-2 col-sm-5" style="margin-top: 10px;">
				<?php if ($user->activated) { ?>
					<?php if ($user->gnz_active) { ?>
						<span class="success"><i class="fa fa-check-square-o"></i> GNZ Number Validated</span>
					<?php } else { ?>
						<span class="warning"><i class="fa fa-exclamation-circle"></i> GNZ Number Not Validated
						<input value="Check Again" name="check-gnz" type="submit" class="btn btn-outline-dark btn-sm"></span>
					<?php } ?>
				<?php } else { ?>
					<span class="error"><i class="fa fa-exclamation-circle"></i> Validating your GNZ number requires a valid email address
				<?php } ?>
			</div>
		</div>

		<div class="form-group">
			<label class="col-sm-2 control-label" for="mobile">Mobile</label>
			<div class="col-sm-5">
				<input class="form-control" type="text" id="mobile" name="mobile" size="40" value="{{ old('mobile', $user->mobile) }}">
			</div>
			<div class="col-sm-5 form-help">Note this might be displayed publicly e.g. so public can contact instructor of the day</div>
		</div>

		<div class="form-group">
			<label class="col-sm-2 control-label" for="first_name">First Name</label>
			<div class="col-sm-5">
				<input class="form-control" type="text" id="first_name" name="first_name" size="40" value="{{ old('first_name', $user->first_name) }}">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="last_name">Last Name</label>
			<div class="col-sm-5">
				<input class="form-control" type="text" id="last_name" name="last_name" size="40" value="{{ old('last_name', $user->last_name) }}">
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-5">
				<input value="Update Account" name="update-account" type="submit" class="btn btn-primary">
			</div>
		</div>
	</form>

	<hr>

	<h2>Permissions</h2>

	<table class="table table-striped">
		<tr>
			<th>Role</th>
			<th>Permission</th>
		</tr>
		<?php /*
		<tr>
			<td>Root</td>
			<td>@role('root') Yes @else No @endrole</td>
		</tr>
		<tr>
			<td>Admin</td>
			<td>@role('admin') Yes @else No @endrole</td>
		</tr>
		*/ ?>
		<tr>
			<td>Site Admin</td>
			<td>
				@can('admin') 
					Yes 
				@else 
					No
				@endcan
			</td>
		</tr>
		<tr>
			<td>Club Admin</td>
			<td>
				@can('club-admin') 
					Yes 
				@else 
					No
				@endcan
			</td>
		</tr>
		<tr>
			<td>Club Member</td>
			<td>
				@can('club-member') 
					Yes 
				@else 
					No
				@endcan
			</td>
		</tr>
		<tr>
			<td>Verified GNZ Member</td>
			<td>
				@can('gnz-member') 
					Yes 
				@else 
					No
				@endcan
			</td>
		</tr>
		<tr>
			<td>Membership View Access</td>
			<td>
				@can('membership-view') 
					Yes 
				@else 
					No
				@endcan
			</td>
		</tr>
	</table>

	<hr>

	<?php if ($member) { ?>
		<h2>GNZ Details</h2>

		<div class="row gnz-details">
			<div class="col-sm-6 form-horizontal">
				<div class="form-group">
					<div class="col-xs-6 gnz-details-label"><label>Name</label></div>
					<div class="col-xs-6">{{ $member->first_name }} {{ $member->middle_name }} {{ $member->last_name }}</div>
				</div>
				<div class="form-group">
					<div class="col-xs-6 gnz-details-label"><label>Address</label></div>
					<div class="col-xs-6">
						<?php
						$details = Array();
						if ($member->address_1!='') $details[] = $member->address_1;
						if ($member->address_2!='') $details[] = $member->address_2;
						if ($member->city!='') $details[] = $member->city;
						if ($member->country!='') $details[] = $member->country;
						if ($member->zip_post!='') $details[] = $member->zip_post;
						echo implode($details, '<br>');
						?>
					</div>
				</div>
				<div class="form-group">
					<div class="col-xs-6 gnz-details-label"><label>Club</label></div>
					<div class="col-xs-6">{{ $member->club }}</div>
				</div>
				<div class="form-group">
					<div class="col-xs-6 gnz-details-label"><label>Home Phone</label></div>
					<div class="col-xs-6">{{ $member->home_phone }}</div>
				</div>
				<div class="form-group">
					<div class="col-xs-6 gnz-details-label"><label>Mobile Phone</label></div>
					<div class="col-xs-6">{{ $member->mobile_phone }}</div>
				</div>
				<div class="form-group">
					<div class="col-xs-6 gnz-details-label"><label>Business Phone</label></div>
					<div class="col-xs-6">{{ $member->business_phone }}</div>
				</div>
				<div class="form-group">
					<div class="col-xs-6 gnz-details-label"><label>Resigned</label></div>
					<div class="col-xs-6">{{ $member->resigned ? 'Yes' : 'No' }}</div>
				</div>
				<div class="form-group">
					<div class="col-xs-6 gnz-details-label"><label>Instructor</label></div>
					<div class="col-xs-6">{{ $member->instructor ? $member->instructor_rating . ' Cat' : 'No' }}</div>
				</div>
				<div class="form-group">
					<div class="col-xs-6 gnz-details-label"><label>Instructor</label></div>
					<div class="col-xs-6">{{ $member->instructor ? 'Yes' : 'No' }}</div>
				</div>
				<div class="form-group">
					<div class="col-xs-6 gnz-details-label"><label>aero_tow</label></div>
					<div class="col-xs-6">{{ $member->aero_tow }}</div>
				</div>
				<div class="form-group">
					<div class="col-xs-6 gnz-details-label"><label>winch_rating</label></div>
					<div class="col-xs-6">{{ $member->winch_rating }}</div>
				</div>
				<div class="form-group">
					<div class="col-xs-6 gnz-details-label"><label>self_launch</label></div>
					<div class="col-xs-6">{{ $member->self_launch }}</div>
				</div>
				<div class="form-group">
					<div class="col-xs-6 gnz-details-label"><label>insttrain</label></div>
					<div class="col-xs-6">{{ $member->insttrain }}</div>
				</div>
				<div class="form-group">
					<div class="col-xs-6 gnz-details-label"><label>observer_number</label></div>
					<div class="col-xs-6">{{ $member->observer_number }}</div>
				</div>
				<div class="form-group">
					<div class="col-xs-6 gnz-details-label"><label>tow_pilot</label></div>
					<div class="col-xs-6">{{ $member->tow_pilot }}</div>
				</div>
			</div>
			<div class="col-sm-6 form-horizontal">
				<div class="form-group">
					<div class="col-xs-6 gnz-details-label"><label>awards</label></div>
					<div class="col-xs-6">{{ $member->awards }}</div>
				</div>
				<div class="form-group">
					<div class="col-xs-6 gnz-details-label"><label>qgp_number</label></div>
					<div class="col-xs-6">{{ $member->qgp_number }}</div>
				</div>
				<div class="form-group">
					<div class="col-xs-6 gnz-details-label"><label>date_of_qgp</label></div>
					<div class="col-xs-6">{{ $member->date_of_qgp }}</div>
				</div>
				<div class="form-group">
					<div class="col-xs-6 gnz-details-label"><label>silver_certificate_number</label></div>
					<div class="col-xs-6">{{ $member->silver_certifica }}</div>
				</div>
				<div class="form-group">
					<div class="col-xs-6 gnz-details-label"><label>silver_duration</label></div>
					<div class="col-xs-6">{{ $member->silver_duration }}</div>
				</div>
				<div class="form-group">
					<div class="col-xs-6 gnz-details-label"><label>silver_distance</label></div>
					<div class="col-xs-6">{{ $member->silver_distance }}</div>
				</div>
				<div class="form-group">
					<div class="col-xs-6 gnz-details-label"><label>silver_height</label></div>
					<div class="col-xs-6">{{ $member->silver_height }}</div>
				</div>
				<div class="form-group">
					<div class="col-xs-6 gnz-details-label"><label>gold_badge_number</label></div>
					<div class="col-xs-6">{{ $member->gold_badge_numbe }}</div>
				</div>
				<div class="form-group">
					<div class="col-xs-6 gnz-details-label"><label>gold_distance</label></div>
					<div class="col-xs-6">{{ $member->gold_distance }}</div>
				</div>
				<div class="form-group">
					<div class="col-xs-6 gnz-details-label"><label>gold_height</label></div>
					<div class="col-xs-6">{{ $member->gold_height }}</div>
				</div>
				<div class="form-group">
					<div class="col-xs-6 gnz-details-label"><label>diamond_distance_number</label></div>
					<div class="col-xs-6">{{ $member->diamond_distance }}</div>
				</div>
				<div class="form-group">
					<div class="col-xs-6 gnz-details-label"><label>diamond_height_number</label></div>
					<div class="col-xs-6">{{ $member->diamond_height_n }}</div>
				</div>
				<div class="form-group">
					<div class="col-xs-6 gnz-details-label"><label>diamond_goal_number</label></div>
					<div class="col-xs-6">{{ $member->diamond_goal_num }}</div>
				</div>
				<div class="form-group">
					<div class="col-xs-6 gnz-details-label"><label>all_3_diamonds_number</label></div>
					<div class="col-xs-6">{{ $member->all_3_diamonds_n }}</div>
				</div>
				<div class="form-group">
					<div class="col-xs-6 gnz-details-label"><label>flight_1000km_number</label></div>
					<div class="col-xs-6">{{ $member->flight_1000km_nu }}</div>
				</div>
				<div class="form-group">
					<div class="col-xs-6 gnz-details-label"><label>flight_1250km_number</label></div>
					<div class="col-xs-6">{{ $member->flight_1250km_nu }}</div>
				</div>
				<div class="form-group">
					<div class="col-xs-6 gnz-details-label"><label>flight_1500km_number</label></div>
					<div class="col-xs-6">{{ $member->flight_1500km_nu }}</div>
				</div>
			</div>
		</div>
	<?php } ?>
</div>

@endsection

