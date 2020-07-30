<style>
	.event-details .card-body {
		font-size: 120%;
	}
</style>

<template>
<div>
	

	<div v-if="!event">
		Loading...
	</div>
	<div v-if="entry">

		<h1><a href="/events">Events</a> &raquo; <a :href="'/events/' + entry.event.slug">{{entry.event.name}}</a> &raquo; Edit Entry</h1>

		<div class="alert alert-info" role="alert" v-if="!viewGNZMembers">
			Tip: <a href="/login">Login</a> or <a href="/register">register an account</a> to speed up entry of this form! And manage your entries later.
		</div>

		<ol>
			<li>
				Are you a GNZ member?
				<div class="form-group col-md-6">
					<label for="member_yes"><input type="radio" v-model="data.gnzMember" :value="true" id="member_yes" checked> Yes</label>
					<label for="member_no"><input type="radio" v-model="data.gnzMember" :value="false" id="member_no"> No</label>
				</div>
			</li>
			<li v-if="data.gnzMember">
				<div v-if="viewGNZMembers">
					GNZ Member:<br>
					<div class="form-group col-md-6">
						<member-selector v-model="data.memberId"></member-selector>
					</div>
				</div>
				<div v-if="!viewGNZMembers">
					GNZ Number<br>
					<div class="form-group col-md-6">
						<input type="text" v-model="data.gnzNumber" class="form-control" id="gnz_number" name="gnz_number">
					</div>
				</div>
				
			</li>
			<li v-if="!data.gnzMember">
				<div class="form-group col-md-6">
					<label for="first_name">First Name</label>
					<input type="text" v-model="data.first_name" class="form-control" id="first_name" name="first_name">
				</div>

				<div class="form-group col-md-6">
					<label for="last_name">Last Name</label> 
					<input type="text" v-model="data.last_name" class="form-control" id="last_name" name="last_name">
				</div>

				<div class="form-group col-md-6">
					<label for="email">Email</label> 
					<input type="text" v-model="data.email" class="form-control" id="email" name="email">
				</div>

			</li>
			<li>
				<div class="form-group col-md-6">
					<label for="mobile">Mobile</label> 
					<input type="text" v-model="data.mobile" class="form-control" id="mobile" name="mobile">
				</div>
			</li>
			<li>
				Entry Type
				<div class="form-group col-md-6">
					<label for="entry_type_pilot" class="mr-4"><input id="entry_type_pilot" type="radio" v-model="data.entry_type" value="pilot"> Pilot</label>
					<label for="entry_type_2nd_pilot" class="mr-4"><input id="entry_type_2nd_pilot" type="radio" v-model="data.entry_type" value="2nd_pilot"> Second Pilot</label>
					<label for="entry_type_towpilot" class="mr-4"><input id="entry_type_towpilot" type="radio" v-model="data.entry_type" value="towpilot"> Tow Pilot</label>
					<label for="entry_type_helper" class="mr-4"><input id="entry_type_helper" type="radio" v-model="data.entry_type" value="helper"> Helper</label>
				</div>
			</li>
			<li>
				<button class="btn btn-primary" v-on:click="createEntry()">Next...</button>
			</li>


			<!-- <li v-if="!viewGNZMembers && (data.entry_type=='pilot' || data.entry_type=='2nd_pilot')">
				<div class="form-group col-md-6">
					<label for="aircraft">Aircraft</label>
					<aircraft-selector v-model="data.aircraftId"></aircraft-selector>
				</div>

			</li>
			<li v-if="!viewGNZMembers && data.entry_type=='pilot'">
				<div class="form-group col-md-6">
					<label for="aircraft">Wingspan</label>
					<select name="wingspan" id="wingspan" class="form-control" v-model="data.wingspan">
						<option value="15">15m</option>
						<option value="18">18m</option>
						<option disabled>-------------</option>
						<option value="13.4">13.4m e.g. PW5</option>
						<option value="16">16m</option>
						<option value="16.6">16.6m e.g. ASW20BL/CL</option>
						<option value="17.5">17.5m</option>
						<option value="17.6">17.6m e.g. Ventus</option>
						<option value="20">20m</option>
						<option value="21">21m</option>
						<option value="22">22m</option>
						<option value="24">24m</option>
						<option value="25.6">25.6m e.g. ASH25</option>
						<option value="26">26m</option>
						<option value="26">26.5m e.g. Nimbus</option>
					</select>
				</div>
				<div class="form-group col-md-6">
					<label for="winglets"><input class="" type="checkbox" id="winglets" v-model="data.winglets" :value="true"> Winglets</label>
				</div>
			</li>
			<li>
				
				<div class="form-group col-md-6">
					<label for="emergency_contact">Emergency Contact Name</label>
					<input type="text" v-model="data.emergency_contact" class="form-control" id="emergency_contact" name="emergency_contact">
				</div>

				<div class="form-group col-md-6">
					<label for="emergency_mobile">Emergency Contact Mobile</label> 
					<input type="text" v-model="data.emergency_mobile" class="form-control" id="emergency_mobile" name="emergency_mobile">
				</div>

				<div class="form-group col-md-6">
					<label for="emergency_relationship">Emergency Contact Relationship to You</label> 
					<input type="text" v-model="data.emergency_relationship" class="form-control" id="emergency_relationship" name="emergency_relationship">
				</div>

			</li>
			<li v-if="data.entry_type=='pilot' || data.entry_type=='2nd_pilot'">
				
				<div class="form-group col-md-6">
					<label for="crew_name">Crew Name</label>
					<input type="text" v-model="data.crew_name" class="form-control" id="crew_name" name="crew_name">
				</div>

				<div class="form-group col-md-6">
					<label for="crew_mobile">Crew Mobile</label> 
					<input type="text" v-model="data.crew_mobile" class="form-control" id="crew_mobile" name="crew_mobile">
				</div>

				<div class="form-group col-md-6">
					<label for="car_plate">Retrieve Vehicle Plate Number</label> 
					<input type="text" v-model="data.car_plate" class="form-control" id="car_plate" name="car_plate">
				</div>
				<div class="form-group col-md-6">
					<label for="car_details">Retrieve Vehicle/Trailer Details</label> 
					<input type="text" v-model="data.car_details" class="form-control" id="car_details" name="car_details">
				</div>

			</li> -->


		</ol>
	</div>

</div>
</template>

<script>
import common from '../../mixins.js';
var marked = require('marked');
export default {
	mixins: [common],
	props: ['editcode'],
	data: function() {
		return {
			event: null,
			viewGNZMembers: false,
			data: {
				gnzMember: true,
				memberId: null,
				gnzNumber: null,
				first_name: '',
				last_name: '',
				mobile: '',
				email: '',
				aircraftId: null,
				wingspan: '15m',
				winglets: false,
				entry_type: 'pilot'
			}
			
		}
	},
	created: function() {
		this.load();
		console.log(this.currentMemberId);
		if (typeof this.currentMemberId!='undefined') {
			this.data.memberId = this.currentMemberId;
		}
	},
	mounted: function() {
		this.viewGNZMembers = window.Laravel.gnzMember;
	},
	methods: {
		load: function() {
			var that = this;
			window.axios.get('/api/entries/' + this.eventId).then(function (response) {
				that.event = response.data.data;
			});
		},
		createEntry: function() {
			window.axios.post('/api/v1/entries', this.data).then(function (response) {
				var entry = response.data.data;
				
			}).catch(function (error) {
				messages.$emit('error', error.response.data.error);
			});
		}
	}
}
</script>
