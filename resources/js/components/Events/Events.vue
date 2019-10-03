<template>
<div>
	
	<nav class="nav-container container-fluid d-flex flex-wrap">

		<h1 class="mr-auto">Events</h1>

		<add-event-panel :org-id="orgId" :show="showAddPanel" @closeModal="showAddPanel=false" @eventAdded="eventAdded"></add-event-panel>

		<div class="btn-group ml-auto mr-2 " role="group" v-model="show">
			<button type="button" class="btn" v-bind:class="[ show=='all' ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="show='all'">All</button>
			<button type="button" class="btn" v-bind:class="[ show=='national' ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="show='national'">National</button>
			<button type="button" class="btn" v-bind:class="[ show=='gnz' ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="show='gnz'">GNZ</button>
			<button type="button" class="btn" v-bind:class="[ show=='orgs' ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="show='orgs'">Club:</button>
		</div>

		<org-selector :org-id="orgId" v-on:orgSelected="orgSelected" class="mr-2" :disabled="show!='orgs'"></org-selector>

		<div v-if="Laravel.clubAdmin">
			<button class="btn btn-outline-dark" v-on:click="showAddPanel=true">Add Event</button>
		</div>

	</nav>

	<table v-show="events.length>0" class="table table-striped table-sm collapsable calendar-table">
		<tr>
			<th>Event Name</th>
			<th>Organisation</th>
			<th>Starts</th>
			<th>Date</th>
			<th>Length</th>
			<th>Location</th>
			<th>Edit</th>
		</tr>
		<tr v-for="event in events">
			<td>{{event.name}}</td>
			<td>
				<span v-if="event.org">{{event.org.name}}</span>
				<span v-if="event.org==null">GNZ</span>
			</td>
			<td>{{dateToNow(event.start_date)}}</td>
			<td>{{formatDate(event.start_date)}}<span v-if="event.start_date!=event.end_date"> - {{formatDate(event.end_date)}}</span></td>
			<td>{{dateDiffDays(event.start_date, event.end_date)}}</td>
			<td>{{event.location}}</td>
			<td><a class="btn btn-xs btn-outline-dark" :href="'/events/' + event.slug + '/edit'" v-if="event.can_edit">Edit</a></td>
		</tr>
	</table>

	<p v-show="events.length==0">No events yet!</p>

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
			show: 'national',
			selectedOrg: {},
			showAddPanel: false
		}
	},
	watch: {
		show: function() {
			this.load();
		}
	},
	props: ['orgId', 'orgName', 'eventId'],
	created: function() {
		// only load on created if an org is not given
		if (!this.orgId) {
			this.load();
		}
	},
	methods: {
		load: function() {
			var that = this;

			var data = {'t':1}

			// check if we have selected an org. It might be null, and thus = all orgs
			if (this.show=='orgs') {
				if (this.selectedOrg) {
					data.org_id = this.selectedOrg.id;
				}
			}

			// check if we have selected to show all national events
			if (this.show=='national') {
				data.national = true;
			}

			// check if we have selected to show all national events
			if (this.show=='gnz') {
				data.org_id = 'gnz';
			}

			window.axios.get('/api/events/', {params: data}).then(function (response) {
				that.events = response.data.data;
			});
		},
		orgSelected: function(org) {
			this.selectedOrg = org;
			this.show='orgs';
			this.load();
		},
		eventAdded: function(event) {
			this.load();
		}
	}
}
</script>
