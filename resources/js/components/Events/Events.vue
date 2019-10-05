<template>
<div>
	
	<nav class="nav-container container-fluid d-flex flex-wrap">

		<h1 class="mr-auto">Events</h1>

		<add-event-panel v-if="Laravel.clubAdmin==true || Laravel.admin" :org-id="orgId" :show="showAddPanel" @closeModal="showAddPanel=false" @eventAdded="eventAdded"></add-event-panel>

		<div class="btn-group ml-auto mr-2" role="group">
			<button type="button" class="btn btn-sm mb-2" v-bind:class="[ state.timerange=='past' ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="state.timerange='past'">Past</button>
			<button type="button" class="btn btn-sm mb-2" v-bind:class="[ state.timerange=='future' ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="state.timerange='future'">Upcoming</button>
		</div>

		<div class="btn-group mr-2" role="group">
			<button type="button" class="btn btn-sm mb-2" v-bind:class="[ state.show=='all' ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="state.show='all'">All</button>
			<button type="button" class="btn btn-sm mb-2" v-bind:class="[ state.show=='featured' ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="state.show='featured'">Featured</button>
			<button type="button" class="btn btn-sm mb-2" v-bind:class="[ state.show=='gnz' ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="state.show='gnz'">GNZ</button>
			<button type="button" class="btn btn-sm mb-2" v-bind:class="[ state.show=='orgs' ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="state.show='orgs'">Club:</button>
		</div>

		<org-selector :org-id="orgId" v-on:orgSelected="orgSelected" class="mr-2" :disabled="state.show!='orgs'"></org-selector>

		<div v-if="Laravel.clubAdmin || Laravel.admin">
			<button class="btn btn-outline-dark btn-sm mb-2" v-on:click="showAddPanel=true">Add Event</button>
		</div>


		<div class="btn-group mr-2 " role="group">
			<button type="button" class="btn btn-sm" v-bind:class="[ state.type=='all' ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="state.type='all'">All</button>
			<button v-for="eventType in eventTypes()" type="button" class="btn btn-sm" v-bind:class="[ state.type==eventType.code ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="state.type=eventType.code">{{eventType.shortname}}</button>
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
			<th v-if="Laravel.clubAdmin">Edit</th>
		</tr>
		<tr v-for="event in events">
			<td><a :href="'/events/' + event.slug">{{event.name}}</a></td>
			<td><span v-html="formatEventTypeIcon(event.type)"></span> {{formatEventType(event.type)}}</td>
			<td>
				<span v-if="event.org">{{event.org.name}}</span>
				<span v-if="event.org==null">GNZ</span>
			</td>
			<td>{{dateToNow(event.start_date)}}</td>
			<td>{{formatDate(event.start_date)}}<span v-if="event.end_date && event.start_date!=event.end_date"> - {{formatDate(event.end_date)}}</span></td>
			<td>{{dateDiffDays(event.start_date, event.end_date)}}</td>
			<td>{{event.location}}</td>
			<td v-if="Laravel.clubAdmin"><a class="btn btn-xs btn-outline-dark" :href="'/events/' + event.slug + '/edit'" v-if="event.can_edit">Edit</a></td>
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
			state: {
				show: 'orgs',
				type: 'all',
				timerange: 'future'
			},
			events: [],
			newDutyName: '',
			selectedOrg: {},
			showAddPanel: false,
			dont_reload: false
		}
	},
	watch: {
		'state': {
			handler: 'stateChanged',
			deep: true
		}
	},
	props: ['orgId', 'orgName', 'eventId'],
	created: function() {
		// only load on created if an org is not given
		if (!this.orgId) {
			this.state.show='featured';
			this.load();
		}
	},
	mounted: function() {
		var that=this;
		var State = History.getState();



		// load existing GET params
		if (this.get_url_param('show')) this.state.show = this.get_url_param('show');
		if (this.get_url_param('type')) this.state.type = this.get_url_param('type');
		if (this.get_url_param('timerange')) this.state.timerange = this.get_url_param('timerange');

		History.Adapter.bind(window, 'statechange', function() {
			var state = History.getState();
			that.state = state.data;
			if (!that.dont_reload) {
				that.load();
			}
			that.dont_reload=false;
		});

		this.dont_reload=true; // make sure we dont do a double load on page launch
		History.replaceState(this.state, null, "?show=" + this.state.show + "&type=" + this.state.type + "&timerange=" + this.state.timerange);
		this.load();

	},
	methods: {
		load: function() {
			var that = this;

			var data = {'timerange':this.state.timerange}

			// check if we have selected an org. It might be null, and thus = all orgs
			if (this.state.show=='orgs') {
				if (this.selectedOrg) {
					data.org_id = this.selectedOrg.id;
				}
			}

			// check if we have selected to show all national events
			if (this.state.show=='featured') {
				data.featured = true;
			}

			// check if we have selected to show all national events
			if (this.state.show=='gnz') {
				data.org_id = 'gnz';
			}

			// filter by event type
			if (this.state.type!='all') {
				data.type = this.state.type;
			}

			window.axios.get('/api/events/', {params: data}).then(function (response) {
				that.events = response.data.data;
			});
		},
		orgSelected: function(org) {
			this.selectedOrg = org;
			this.load();
		},
		stateChanged: function() {
			History.pushState(this.state, null, "?show=" + this.state.show + "&type=" + this.state.type + "&timerange=" + this.state.timerange);
		},
		eventAdded: function(event) {
			this.load();
			window.location.href = "/events/" + event.slug  + "/edit";
		}
	}
}
</script>
