<template>
<div>
	
	<nav class="nav-container container-fluid d-flex flex-wrap">

		<h1 class="mr-auto">Events</h1>

		<add-event-panel v-if="Laravel.clubAdmin==true || Laravel.admin" :org-id="orgId" :show="showAddPanel" @closeModal="showAddPanel=false" @eventAdded="eventAdded"></add-event-panel>

		<div class="btn-group ml-auto mr-2" role="group">
			<button type="button" class="btn btn-sm mb-2" v-bind:class="[ state.timerange=='past' ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="state.timerange='past'; stateChanged()">Past</button>
			<button type="button" class="btn btn-sm mb-2" v-bind:class="[ state.timerange=='future' ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="state.timerange='future'; stateChanged()">Upcoming</button>
		</div>

		
		<span class="mt-1 mr-2">GNZ Events:</span>
		<div class="btn-group mr-2" role="group">
			<button type="button" class="btn btn-sm mb-2" v-bind:class="[ state.gnz ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="state.gnz=true; stateChanged()">Show</button>
			<button type="button" class="btn btn-sm mb-2" v-bind:class="[ !state.gnz ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="state.gnz=false; stateChanged()">Hide</button>
		</div>
		
		<span class="mt-1 mr-2">Other Clubs Events:</span>
		<div class="btn-group mr-2" role="group">
			<button type="button" class="btn btn-sm mb-2" v-bind:class="[ state.other ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="state.other=true; stateChanged()">Show</button>
			<button type="button" class="btn btn-sm mb-2" v-bind:class="[ !state.other ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="state.other=false; stateChanged()">Hide</button>
		</div>

		<org-selector :org-id="orgId" v-on:orgSelected="orgSelected" class="mr-2"></org-selector>

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

	
	<div class="form-group col-sm-6">
		<label for="slug" class="col-form-label"><span class="fa fa-calendar-plus"></span> Add what you see above to your calendar with this iCal feed:</label>
		<input type="text" class="form-control" v-model="ical_url">
	</div>
	

</div>
</template>

<script>
import common from '../../mixins.js';
export default {
	mixins: [common],
	data: function() {
		return {
			state: {
				gnz: true,
				other: true, // show other clubs or not
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
	computed: {
		ical_url: function() {
			return Laravel.BASE_URL + '/api/events' + "?ical=true&gnz=" + this.state.gnz + "&other=" + this.state.other + "&type=" + this.state.type + "&timerange=" + this.state.timerange + "&org_id=" + this.orgId;
		}
	},
	props: ['orgId', 'orgName', 'eventId'],
	created: function() {
		// only load on created if an org is not given
		if (!this.orgId) {
			//this.state.show='featured';
			this.load();
		}
	},
	mounted: function() {
		var that=this;
		var State = History.getState();

		// load existing GET params
		if (this.get_url_param('gnz')!='') {
			if (this.get_url_param('gnz')=='true') this.state.gnz=true; else this.state.gnz=false; 
		}
		if (this.get_url_param('other')!='') {
			if (this.get_url_param('other')=='true') this.state.other=true; else this.state.other=false;
		}
		if (this.get_url_param('type')) this.state.type = this.get_url_param('type');
		if (this.get_url_param('timerange')) this.state.timerange = this.get_url_param('timerange');

		History.Adapter.bind(window, 'statechange', function() {
			var state = History.getState();
			that.state = state.data;
		});

		//this.dont_reload=true; // make sure we dont do a double load on page launch

		// Set up the initial state in the URL
		History.replaceState(this.state, null, "?gnz=" + this.state.gnz + "&other=" + this.state.other + "&type=" + this.state.type + "&timerange=" + this.state.timerange);

	},
	methods: {
		load: function() {
			var that = this;

			var data = {
				'timerange':this.state.timerange,
				'gnz': this.state.gnz,
				'other': this.state.other,
				'type': this.state.type,
			}

			//check if we have selected an org. It might be null, and thus = all orgs
			if (this.selectedOrg) {
				data.org_id = this.selectedOrg.id;
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
			History.pushState(this.state, null, "?gnz=" + this.state.gnz + "&other=" + this.state.other + "&type=" + this.state.type + "&timerange=" + this.state.timerange);
			this.load();
		},
		eventAdded: function(event) {
			this.load();
			window.location.href = "/events/" + event.slug  + "/edit";
		}
	}
}
</script>
