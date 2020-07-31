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
	<div v-if="event">

		<h1><a href="/events">Events</a> &raquo; <a :href="'/events/' + event.slug">{{event.name}}</a> &raquo; Entry Form</h1>

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
						<member-selector v-model="data.member_id"></member-selector>
					</div>
				</div>
				<div v-if="!viewGNZMembers">
					GNZ Number<br>
					<div class="form-group col-md-6">
						<input type="text" v-model="data.gnz_number" class="form-control" id="gnz_number" name="gnz_number">
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

		</ol>
	</div>

</div>
</template>

<script>
import common from '../../mixins.js';
var marked = require('marked');
export default {
	mixins: [common],
	props: ['currentMemberId', 'eventId'],
	data: function() {
		return {
			event: null,
			viewGNZMembers: false,
			data: {
				eventId: null,
				gnzMember: true,
				member_id: null,
				gnz_number: null,
				first_name: '',
				last_name: '',
				mobile: '',
				email: '',
				aircraftId: null,
				wingspan: '15m',
				winglets: false,
				entry_type: 'pilot',
			}
			
		}
	},
	created: function() {
		this.load();
		console.log(this.currentMemberId);
		if (typeof this.currentMemberId!='undefined') {
			this.data.member_id = this.currentMemberId;
		}
	},
	mounted: function() {
		this.viewGNZMembers = window.Laravel.gnzMember;
	},
	methods: {
		load: function() {
			var that = this;
			window.axios.get('/api/events/' + this.eventId).then(function (response) {
				that.event = response.data.data;
				that.data.eventId = that.event.id;
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
