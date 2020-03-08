<template><div>

<h1>Join {{orgName}}</h1>

<p>Your application form will be submitted to the club secratary for confirmation for approval at the next committee meeting.</p>


<form>
	<div class="form-group">
		<label for="exampleInputEmail1">First Name</label>
		<input type="text" class="form-control" id="first_name">
		
	</div>
	<div class="form-group">
		<label for="exampleInputEmail1">Middle Name</label>
		<input type="text" class="form-control" id="middle_name">
	</div>
	<div class="form-group">
		<label for="exampleInputEmail1">Last Name</label>
		<input type="text" class="form-control" id="last_name">
		
	</div>
	<div class="form-group">
		<label for="exampleInputEmail1">Gender</label>

		<div class="form-check">
			<input type="radio" name="gender" class="form-check-input" id="gender_null" value="" checked>
			<label class="form-check-label" for="gender_null">Not Specified</label>
		</div>

		<div class="form-check">
			<input type="radio" name="gender" class="form-check-input" id="gender_male" value="male">
			<label class="form-check-label" for="gender_male">Male</label>
		</div>

		<div class="form-check">
			<input type="radio" name="gender" class="form-check-input" id="gender_female" value="female">
			<label class="form-check-label" for="gender_female">Female</label>
		</div>

	</div>

	<div class="form-group">
		<label for="exampleInputEmail1">Type of Application</label>

		<div class="form-check">
			<input type="radio" name="application_type" class="form-check-input" id="new_pilot" value="new_pilot" checked>
			<label class="form-check-label" for="new_pilot">New Glider Pilot</label>
		</div>

		<div class="form-check">
			<input type="radio" name="application_type" class="form-check-input" id="new_pilot" value="new_pilot" >
			<label class="form-check-label" for="">New Tow Pilot</label>
		</div>

		<div class="form-check">
			<input type="radio" name="application_type" class="form-check-input" id="new_pilot" value="new_pilot" >
			<label class="form-check-label" for="">Second Club (Already a member of a New Zealand club)</label>
		</div>


		<div class="form-check">
			<input type="radio" name="application_type" class="form-check-input" id="new_pilot" value="new_pilot" >
			<label class="form-check-label" for="">Associate Member (non-flying)</label>
		</div>

		<div class="form-check">
			<input type="radio" name="application_type" class="form-check-input" id="new_pilot" value="visiting" >
			<label class="form-check-label" for="">Visiting Pilot (Short Term &lt;3 months)</label>
		</div>

	</div>
	<div class="form-group">
		<label for="exampleInputEmail1">Email</label>
		<input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
		
	</div>
	<div class="form-group">
		<label for="exampleInputEmail1">Mobile Number</label>
		<input type="text" class="form-control" id="mobile_phone">
	</div>
	<div class="form-group">
		<label for="exampleInputEmail1">Home Phone Number</label>
		<input type="text" class="form-control" id="home_phone">
	</div>
	<div class="form-group">
		<label for="exampleInputEmail1">Work Phone Number</label>
		<input type="text" class="form-control" id="work_phone">
	</div>
	<div class="form-group">
		<label for="exampleInputEmail1">Work Phone Number</label>
		<input type="text" class="form-control" id="work_phone">
	</div>
	<div class="form-group">
		<label for="exampleInputEmail1">Date of Birth</label>
		<input type="text" class="form-control" id="date_of_birth">
	</div>
	<div class="form-group">
		<label for="exampleInputEmail1">Address</label>
		<input type="text" class="form-control" id="address_1">
		<input type="text" class="form-control" id="address_2">
	</div>
	<div class="form-group">
		<label for="exampleInputEmail1">City</label>
		<input type="text" class="form-control" id="city">
	</div>
	<div class="form-group">
		<label for="exampleInputEmail1">Country</label>
		<input type="text" class="form-control" id="country">
	</div>
	<div class="form-group form-check">
		<input type="checkbox" class="form-check-input" id="private">
		<label class="form-check-label" for="private">Keep my contact details private from other GNZ members</label>
	</div>
	<button type="submit" class="btn btn-primary">Submit</button>
</form>


</div></template>

<script>
	import common from '../../mixins.js';

	export default {
		mixins: [common],
		props: ['orgCode', 'orgName'],
		data() {
			return {
			}
		},
		mounted() {
		},
		methods: {
		}
	}
</script>