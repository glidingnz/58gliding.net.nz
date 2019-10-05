<template>
<div>
	
	<nav class="nav-container container-fluid d-flex flex-wrap">

		<h1 class="mr-auto">Events</h1>

		<add-event-panel v-if="Laravel.clubAdmin==true || Laravel.admin" :org-id="orgId" :show="showAddPanel" @closeModal="showAddPanel=false" @eventAdded="eventAdded"></add-event-panel>

		<div class="btn-group ml-auto mr-2 " role="group" v-model="timerange">
			<button type="button" class="btn" v-bind:class="[ timerange=='past' ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="timerange='past'">Past</button>
			<button type="button" class="btn" v-bind:class="[ timerange=='future' ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="timerange='future'">Future</button>
		</div>

		<div class="btn-group mr-2 " role="group" v-model="show">
			<button type="button" class="btn" v-bind:class="[ show=='all' ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="show='all'">All</button>
			<button type="button" class="btn" v-bind:class="[ show=='featured' ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="show='featured'">Featured</button>
			<button type="button" class="btn" v-bind:class="[ show=='gnz' ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="show='gnz'">GNZ</button>
			<button type="button" class="btn" v-bind:class="[ show=='orgs' ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="show='orgs'">Club:</button>
		</div>


		<org-selector :org-id="orgId" v-on:orgSelected="orgSelected" class="mr-2" :disabled="show!='orgs'"></org-selector>

		<div v-if="Laravel.clubAdmin || Laravel.admin">
			<button class="btn btn-outline-dark" v-on:click="showAddPanel=true">Add Event</button>
		</div>

	</nav>

	<table v-show="events.length>0" class="table table-striped table-sm collapsable calendar-table">
		<tr>
			<th>Event Name</th>
			<th>Type</th>
			<th>Organisation</th>
			<th>Starts</th>
			<th>Date</th>
			<th>Length</th>
			<th>Location</th>
			<th>Edit</th>
		</tr>
		<tr v-for="event in events">
			<td><a :href="'/events/' + event.slug">{{event.name}}</a></td>
			<td>{{formatEventType(event.type)}}</td>
			<td>
				<span v-if="event.org">{{event.org.name}}</span>
				<span v-if="event.org==null">GNZ</span>
			</td>
			<td>{{dateToNow(event.start_date)}}</td>
			<td>{{formatDate(event.start_date)}}<span v-if="event.end_date && event.start_date!=event.end_date"> - {{formatDate(event.end_date)}}</span></td>
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
			show: 'featured',
			selectedOrg: {},
			showAddPanel: false,
			timerange: 'future'
		}
	},
	watch: {
		show: function() {
			this.load();
		},
		timerange: function() {
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

			var data = {'timerange':this.timerange}

			// check if we have selected an org. It might be null, and thus = all orgs
			if (this.show=='orgs') {
				if (this.selectedOrg) {
					data.org_id = this.selectedOrg.id;
				}
			}

			// check if we have selected to show all national events
			if (this.show=='featured') {
				data.featured = true;
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
			window.location.href = "/events/" + event.slug  + "/edit";
		}
	}
}
</script>
