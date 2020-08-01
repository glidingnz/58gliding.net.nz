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
			Or clone a previous entry to save time.
		</div>

		<ol>
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
			data: {
				eventId: null,
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
