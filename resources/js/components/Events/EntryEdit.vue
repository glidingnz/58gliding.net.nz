<style>
	.event-details .card-body {
		font-size: 120%;
	}
</style>

<template>
<div>
	

	<div v-if="!loaded">
		Loading...
	</div>
	<div v-if="loaded">

		<h1><a href="/events">Events</a> &raquo; <a :href="'/events/' + entry.event.slug">{{entry.event.name}}</a> &raquo; Edit Entry</h1>

		<div class="alert alert-info" role="alert" v-if="!viewGNZMembers">
			Tip: <a href="/login">Login</a> or <a href="/register">register an account</a> to speed up entry of this form! And manage your entries later.
		</div>

		<ol>
			<li>
				Entry Type
				<div class="form-group col-md-6">
					<label for="entry_type_pilot" class="mr-4"><input id="entry_type_pilot" type="radio" v-model="entry.entry_type" value="pilot"> Pilot</label>
					<label for="entry_type_2nd_pilot" class="mr-4"><input id="entry_type_2nd_pilot" type="radio" v-model="entry.entry_type" value="2nd_pilot"> Second Pilot</label>
					<label for="entry_type_towpilot" class="mr-4"><input id="entry_type_towpilot" type="radio" v-model="entry.entry_type" value="towpilot"> Tow Pilot</label>
					<label for="entry_type_helper" class="mr-4"><input id="entry_type_helper" type="radio" v-model="entry.entry_type" value="helper"> Helper</label>
				</div>
			</li>
			<li>
				Are you a GNZ member?
				<div class="form-group col-md-6">
					<label for="member_yes"><input type="radio" v-model="entry.gnz_member" :value="true" id="member_yes" checked> Yes</label>
					<label for="member_no"><input type="radio" v-model="entry.gnz_member" :value="false" id="member_no"> No</label>
				</div>
			</li>
			<li v-if="entry.gnz_member">
				<div v-if="viewGNZMembers">
					GNZ Member:<br>
					<div class="form-group col-md-6">
						<member-selector v-model="entry.member_id"></member-selector>
					</div>
				</div>
				<div v-if="!viewGNZMembers">
					GNZ Number<br>
					<div class="form-group col-md-6">
						<input type="text" v-model="entry.gnz_number" class="form-control" id="gnz_number" name="gnz_number">
					</div>
				</div>
				
			</li>
			<li v-if="!entry.gnz_member">
				<div class="form-group col-md-6">
					<label for="first_name">First Name</label>
					<input type="text" v-model="entry.first_name" class="form-control" id="first_name" name="first_name">
				</div>

				<div class="form-group col-md-6">
					<label for="last_name">Last Name</label> 
					<input type="text" v-model="entry.last_name" class="form-control" id="last_name" name="last_name">
				</div>

				<div class="form-group col-md-6">
					<label for="email">Email (K	ept private)</label> 
					<input type="text" v-model="entry.email" class="form-control" id="email" name="email">
				</div>

			</li>
			<li>
				<div class="form-group col-md-6">
					<label for="mobile">Mobile (Shown on event page)</label> 
					<input type="text" v-model="entry.mobile" class="form-control" id="mobile" name="mobile">
				</div>
			</li>
			<li>
				Entry Status
				<div class="form-group col-md-6">
					<label for="entry_status_entered" class="mr-4"><input id="entry_status_entered" type="radio" v-model="entry.entry_status" value="entered"> Entered</label>
					<label for="entry_status_tentative" class="mr-4"><input id="entry_status_tentative" type="radio" v-model="entry.entry_status" value="tentative"> Tentative</label>
					<label for="entry_status_cancelled" class="mr-4"><input id="entry_status_cancelled" type="radio" v-model="entry.entry_status" value="cancelled"> Cancelled</label>
				</div>
			</li>

			<li v-if="entry.entry_type=='helper'">
				<div class="form-group col-md-6">
					<label for="role">Event Role</label> 
					<input type="text" v-model="entry.role" class="form-control" id="role" name="role">
				</div>
			</li>

			<li v-if="selectedClasses.length>0 && entry.entry_type=='pilot'">
				Class
				<div class="form-group col-md-6">
					<span v-for="selectedClass in selectedClasses">
					<label :for="'entry_status_tentative_' + selectedClass.id" class="mr-4"><input :id="'entry_status_tentative_' + selectedClass.id" type="radio" v-model="entry.class_id" :value="selectedClass.contest_class.id"> {{selectedClass.contest_class.name}}</label>
					</span>
				</div>
			</li>

			<li v-if="entry.entry_type=='pilot' || entry.entry_type=='2nd_pilot'">
				<div class="form-group col-md-6">
					<label for="aircraft">Aircraft</label>
					<aircraft-selector v-model="entry.aircraft_id"></aircraft-selector>
				</div>

			</li>
			<li v-if="entry.entry_type=='pilot'">
				<div class="form-group col-md-6">
					<label for="wingspan">Wingspan</label>
					<select name="wingspan" id="wingspan" class="form-control" v-model="entry.wingspan">
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
					<label for="winglets"><input class="" type="checkbox" id="winglets" v-model="entry.winglets" :value="true"> Winglets</label>
				</div>
				<div class="form-group col-md-6 form-inline">
					<label for="handicap">Hanidcap Claimed</label>
					<input type="text" id="handicap" v-model="entry.handicap" size="5" class="form-control ml-4 mr-4" > e.g. 98.5 or 111
				</div>
			</li>
			<li>
				
				<div class="form-group col-md-6">
					<label for="emergency_contact">Emergency Contact Name</label>
					<input type="text" v-model="entry.emergency_contact" class="form-control" id="emergency_contact" name="emergency_contact">
				</div>

				<div class="form-group col-md-6">
					<label for="emergency_mobile">Emergency Contact Mobile</label> 
					<input type="text" v-model="entry.emergency_mobile" class="form-control" id="emergency_mobile" name="emergency_mobile">
				</div>

				<div class="form-group col-md-6">
					<label for="emergency_relationship">Emergency Contact Relationship to You</label> 
					<input type="text" v-model="entry.emergency_relationship" class="form-control" id="emergency_relationship" name="emergency_relationship">
				</div>

			</li>
			<li v-if="entry.entry_type=='pilot' || entry.entry_type=='2nd_pilot'">
				
				<div class="form-group col-md-6">
					<label for="crew_name">Crew Name</label>
					<input type="text" v-model="entry.crew_name" class="form-control" id="crew_name" name="crew_name">
				</div>

				<div class="form-group col-md-6">
					<label for="crew_mobile">Crew Mobile</label> 
					<input type="text" v-model="entry.crew_mobile" class="form-control" id="crew_mobile" name="crew_mobile">
				</div>

				<div class="form-group col-md-6">
					<label for="car_plate">Retrieve Vehicle Plate Number</label> 
					<input type="text" v-model="entry.car_plate" class="form-control" id="car_plate" name="car_plate">
				</div>
				<div class="form-group col-md-6">
					<label for="car_details">Retrieve Vehicle/Trailer Details</label> 
					<input type="text" v-model="entry.car_details" class="form-control" id="car_details" name="car_details">
				</div>

			</li> 

			<li v-if="entry.event.catering_breakfasts || entry.event.catering_lunches || entry.event.catering_dinners || entry.event.catering_final_dinner">
				<div v-show="entry.event.catering_breakfasts">
					Breakfasts?

					<div class="form-group col-md-6">
						<label for="catering_breakfasts_none" class="mr-4"><input id="catering_breakfasts_none" type="radio" v-model="entry.catering_breakfasts" value="none"> None</label>
						<label for="catering_breakfasts_some" class="mr-4"><input id="catering_breakfasts_some" type="radio" v-model="entry.catering_breakfasts" value="some"> Some</label>
						<label for="catering_breakfasts_all" class="mr-4"><input id="catering_breakfasts_all" type="radio" v-model="entry.catering_breakfasts" value="all"> All</label>
					</div>
				</div>

				<div v-show="entry.event.catering_lunches">
					Lunches?

					<div class="form-group col-md-6">
						<label for="catering_lunches_none" class="mr-4"><input id="catering_lunches_none" type="radio" v-model="entry.catering_lunches" value="none"> None</label>
						<label for="catering_lunches_some" class="mr-4"><input id="catering_lunches_some" type="radio" v-model="entry.catering_lunches" value="some"> Some</label>
						<label for="catering_lunches_all" class="mr-4"><input id="catering_lunches_all" type="radio" v-model="entry.catering_lunches" value="all"> All</label>
					</div>
				</div>

				<div v-show="entry.event.catering_dinners">
					Dinners?

					<div class="form-group col-md-6">
						<label for="catering_dinners_none" class="mr-4"><input id="catering_dinners_none" type="radio" v-model="entry.catering_dinners" value="none"> None</label>
						<label for="catering_dinners_some" class="mr-4"><input id="catering_dinners_some" type="radio" v-model="entry.catering_dinners" value="some"> Some</label>
						<label for="catering_dinners_all" class="mr-4"><input id="catering_dinners_all" type="radio" v-model="entry.catering_dinners" value="all"> All</label>
					</div>
				</div>

				<div v-show="entry.event.catering_final_dinner">
					Final Dinner?

					<div class="form-group col-md-6">
						<select name="catering_final_dinner" id="catering_final_dinner" v-model="entry.catering_final_dinner">
							<option :value="null">TBC</option>
							<option :value="0">Not Coming</option>
							<option :value="1">1 person</option>
							<option :value="2">2 people</option>
							<option :value="3">3 people</option>
							<option :value="4">4 people</option>
							<option :value="5">5 people</option>
						</select>
					</div>
				</div>

				<li>
					<div class="form-group col-md-6">
						<label for="catering_notes">Any notes for the chef? e.g. Vegeterian, Keto or Don't like brussell sprouts</label> 
						<input type="text" v-model="entry.catering_notes" class="form-control" id="catering_notes" name="catering_notes">
					</div>
				</li>
			</li>

			<li v-if="entry.event.terms">
				<div class="card">
					<div class="card-body">
						<div v-html="entryMarkdown"></div>
					</div>
				</div>
				
				<label for="signed" class="mr-4"><input id="signed" type="checkbox" v-model="entry.signed" :value="true"> I agree to these terms and conditions</label>

			</li>

			<li>
				<button class="btn btn-primary" v-on:click="updateEntry()">Save Changes</button>
			</li>

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
			viewGNZMembers: false,
			loaded: false,
			selectedClasses: [],
			entry: {
				id: null,
				editcode: null,
				gnz_member: true,
				member_id: null,
				gnz_number: null,
				first_name: '',
				last_name: '',
				mobile: '',
				email: '',
				aircraft_id: null,
				wingspan: '15',
				winglets: false,
				entry_type: 'pilot',
				catering_breakfasts: 'none',
				catering_lunches: 'none',
				catering_dinners: 'none',
			}
			
		}
	},
	created: function() {
		this.loadEntry();
	},
	mounted: function() {
		// figure out if we have permissions to view GNZ members
		this.viewGNZMembers = window.Laravel.gnzMember;
	},
	computed: {
		entryMarkdown: function () {
			return marked(this.entry.event.terms, { sanitize: true })
		},
	},
	methods: {
		loadEntry: function() {
			var that = this;
			window.axios.get('/api/v1/entries/code/' + this.editcode).then(function (response) {
				that.entry = response.data.data;
				// once we have loaded the entry details, load the classes for it
				that.loadSelectedClasses();
				that.loaded=true;
			});
		},
		loadSelectedClasses: function() {
			var that = this;
			window.axios.get('/api/v1/events/' + this.entry.event.id + '/classes').then(function (response) {
				that.selectedClasses = response.data.data;
			});
		},
		updateEntry: function() {
			window.axios.post('/api/v1/entries/' + this.editcode, this.entry).then(function (response) {
				var entry = response.data.data;
				messages.$emit('success', 'Entry Saved. Thanks for entering, see you there!');
			}).catch(function (error) {
				messages.$emit('error', error.response.data.error);
			});
		}
	}
}
</script>
