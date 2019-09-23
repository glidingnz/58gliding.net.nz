<template>
<div>
	
	<nav class="nav-container container-fluid d-flex flex-wrap">

		<h1 class="mr-auto">Events</h1>

		<div class="btn-group ml-auto mr-2 " role="group" v-model="show">
			<button type="button" class="btn" v-bind:class="[ show=='national' ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="show='national'">National Events</button>
			<button type="button" class="btn" v-bind:class="[ show=='orgs' ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="show='orgs'">Club Events:</button>
		</div>

		<org-selector :org-id="orgId" v-on:orgSelected="orgSelected" class="mr-2" :disabled="show!='orgs'"></org-selector>

		<div  v-if="Laravel.clubAdmin==true">
			<button class="btn btn-outline-dark">Add Event</button>
		</div>

	</nav>

	<table class="table table-striped table-sm collapsable calendar-table">
		<tr>
			<th>Event Name</th>
			<th>Start Date</th>
			<th>End Date</th>
			<th>Length</th>
		</tr>
		<tr v-for="event in events">
			<td>{{event.name}}</td>
			<td>{{formatDate(event.start_date)}}</td>
			<td>{{formatDate(event.end_date)}}</td>
			<td>{{dateDiffDays(event.start_date, event.end_date)}}</td>
		</tr>
	</table>

</div>
</template>

<script>
import common from '../../mixins.js';
export default {
	mixins: [common],
	data: function() {
		return {
			events: [],
			newDutyName: '',
			show: 'national'
		}
	},
	props: ['orgId', 'orgName', 'eventId'],
	created: function() {
		this.load();
	},
	methods: {
		load: function() {
			var that = this;
			window.axios.get('/api/events/').then(function (response) {
				that.events = response.data.data;
			});
		},
		orgSelected: function(org) {
			console.log('org ');
			console.log(org);
		}
	}
}
</script>
