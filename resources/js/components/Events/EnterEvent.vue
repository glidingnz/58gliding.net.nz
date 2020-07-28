<style>
	.event-details .card-body {
		font-size: 120%;
	}
</style>

<template>
<div>
	
	<h1><a href="/events">Events</a> &raquo; <a :href="'/events/' + event.slug">{{event.name}}</a> &raquo; Entry Form</h1>

	<div class="alert alert-info" role="alert" v-if="!viewGNZMembers">
		Tip: Register an account and/or login to speed up entry of this form! And let you manage your entries later.
	</div>


	<form >
	<ol>
		<li>
			<h2>Are you a GNZ member?</h2>
				<label for="member_yes"><input type="radio" v-model="gnzMember" :value="true" id="member_yes" checked> Yes</label>
				<label for="member_no"><input type="radio" v-model="gnzMember" :value="false" id="member_no"> No</label>
		</li>
		<li v-if="gnzMember">
			<div v-if="viewGNZMembers">
				Find Member:<br>
				<member-selector></member-selector>
			</div>
			<div v-if="!viewGNZMembers">
				GNZ Number<br>
				<input type="text" v-model="gnzNumber" class="form-control" id="gnz_number" name="gnz_number">
			</div>
			
		</li>
		<li v-if="!gnzMember">
			<div class="form-group">
				<label for="first_name">First Name</label>
				<input type="text"  class="form-control" id="first_name" name="first_name">
			</div>

			<div class="form-group">
				<label for="last_name">Last Name</label> 
				<input type="text"  class="form-control" id="last_name" name="last_name">
			</div>

			<div class="form-group">
				<label for="mobile">Mobile</label> 
				<input type="text"  class="form-control" id="mobile" name="mobile">
			</div>

			<div class="form-group">
				<label for="email">Email</label> 
				<input type="text"  class="form-control" id="email" name="email">
			</div>

		</li>
		<li>
			Aircraft
			<aircraft-selector></aircraft-selector>
		</li>
	</ol>
	</form>

</div>
</template>

<script>
import common from '../../mixins.js';
var marked = require('marked');
export default {
	mixins: [common],
	data: function() {
		return {
			event: [],
			attributes: [],
			calendar: null,
			viewGNZMembers: false,
			gnzMember: true,
			memberId: null,
			gnzNumber: null
		}
	},
	props: ['orgId', 'eventId'],
	created: function() {
		this.load();
	},
	mounted: function() {
		this.viewGNZMembers = window.Laravel.gnzMember;
	},
	methods: {
		load: function() {
			var that = this;
			window.axios.get('/api/events/' + this.eventId).then(function (response) {
				that.event = response.data.data;
				
			});
		}
	}
}
</script>
