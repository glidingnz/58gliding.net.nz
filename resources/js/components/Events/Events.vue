<style>
	.custom_calendar .vc-day {
		border-right: 1px solid #AAA;
		border-bottom: 1px solid #AAA;
		padding: 3px;
	}
	.custom_calendar .on-top {
		border-top: 1px solid #AAA;
	}
	.custom_calendar .on-left {
		border-left: 1px solid #AAA;
	}
	.custom_calendar .event {
		word-wrap: break-word;
		word-break: break-all;
	}
	.custom_calendar .event-badge {
		white-space: normal;
		border-radius: 5px;
		margin-top: 2px;
		text-align: left;
	}
	.event-badge {
		color: #FFF;
	}
	.day-today {
		background-color: #222;
		color: #FFF;
		padding: 0px 3px;
		border-radius: 5px;
		width: 100%;
		display: block;
	}
	.day-today:after {
		content: ' : Today';
	}
</style>

<template>
<div>

	<nav class="nav-container container-fluid d-flex flex-wrap">

		<h1 class="mr-auto">{{orgName}} Events</h1>

		<add-event-panel v-if="Laravel.clubAdmin==true || Laravel.admin" :org-id="orgId" :show="showAddPanel" @closeModal="showAddPanel=false" @eventAdded="eventAdded"></add-event-panel>

		<!-- <org-selector :org-id="orgId" v-on:orgSelected="orgSelected" class="mr-2"></org-selector> -->


		<div>

			<div class="btn-group mr-2" role="group">
				<button type="button" class="btn btn-sm" v-bind:class="[ showCalendar==false ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="showCalendar=false; stateChanged()">View as List</button>
				<button type="button" class="btn btn-sm" v-bind:class="[ showCalendar==true ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="showCalendar=true; stateChanged()">Calendar</button>
			</div>

			<div class="btn-group mr-2" role="group">
				<button type="button" class="btn btn-sm" v-bind:class="[ state.timerange=='past' ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="state.timerange='past'; stateChanged()">Past</button>
				<button type="button" class="btn btn-sm" v-bind:class="[ state.timerange=='future' ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="state.timerange='future'; stateChanged()">Upcoming</button>
			</div>

			<div class="btn-group mr-2 " role="group">
				<button type="button" class="btn btn-sm" v-bind:class="[ state.type=='all' ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="state.type='all';  stateChanged()">All Types</button>
				<button v-if="eventType.filter" v-for="eventType in eventTypes()" type="button" class="btn btn-sm" v-bind:class="[ state.type==eventType.code ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="state.type=eventType.code;  stateChanged()">{{eventType.shortname}}</button>
			</div>


			<div class="btn-group mr-2 ml-auto" role="group">
				<button type="button" class="btn btn-sm" v-bind:class="[ state.other ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="state.other=true; stateChanged()">Show Shared Events</button>
				<button type="button" class="btn btn-sm" v-bind:class="[ !state.other ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="state.other=false; stateChanged()">Hide</button>
			</div>

			<div class="btn-group" v-if="Laravel.clubAdmin || Laravel.admin">
				<button class="btn btn-outline-dark btn-sm ml-auto" v-on:click="showAddPanel=true"><span class="fa fa-plus"></span> Add Event</button>
			</div>
		</div>

		

	</nav>

	<table v-show="events.length>0 && !showCalendar" class="table  collapsable calendar-table">
		<tr>
			<th>Event Name</th>
			<th>Type</th>
			<th>Organisation</th>
			<th>Starts</th>
			<th>Date</th>
			<th>Start</th>
			<th>Length</th>
			<th>Location</th>
			<th v-if="Laravel.clubAdmin">Edit</th>
		</tr>
		<tr v-for="event in events">
			<td><a :href="'/events/' + event.slug">{{event.name}}</a></td>
			<td class="text-nowrap"><span v-html="formatEventTypeIcon(event.type)"></span> {{formatEventType(event.type)}}</td>
			<td>
				<span v-if="event.org">{{event.org.name}}</span>
				<span v-if="event.org==null">GNZ</span>
			</td>
			<td>{{dateToNow(event.start_date)}}</td>
			<td>
				{{formatDate(event.start_date)}}<span v-if="event.end_date && event.start_date!=event.end_date"> - {{formatDate(event.end_date)}}</span>
			</td>
			<td><span v-if="event.start_time">{{formatTime(event.start_time)}}</span></td>
			<td>{{dateDiffDays(event.start_date, event.end_date)}}</td>
			<td>{{event.location}}</td>
			<td v-if="Laravel.clubAdmin"><a class="btn btn-xs btn-outline-dark" :href="'/events/' + event.slug + '/edit'" v-if="event.can_edit">Edit</a></td>
		</tr>
	</table>

	<p v-show="events.length==0">No events. Check your filters above.</p>

	<v-calendar :rows="6" v-if="events.length>0 && showCalendar" class="custom_calendar" style="max-width: 100%;" :first-day-of-week="2" ref="calendar" is-expanded  :attributes='attributes'>
		<template slot='day-content' slot-scope="props">
			<div class="day-cell" v-if="props.day.inMonth">
				<span :class="props.day.isToday ? 'day-today' : ''">{{props.day.day}}</span>
				<div v-for="dayEvent in props.attributes">
					<span class="event-badge badge badge-pill" :style="'background-color: ' + dayEvent.customData.colour ">
						<span :class="'fa fa-' + dayEvent.customData.icon"></span>
						<a :href="'/events/' + dayEvent.customData.slug ">
							<span v-if="dayEvent.customData.showOrg">({{dayEvent.customData.orgShortName}})</span>
							{{dayEvent.customData.name}}
						</a>
					</span>
				</div>
			</div>
		</template>
	</v-calendar>

	
	<div class="mt-2">
		<button class="btn btn-sm btn-outline-dark" v-on:click="showAddCalendar = !showAddCalendar"><span class="fa fa-calendar-plus"></span> Add to Calendar...</button>
		<div>
			<label v-show="showAddCalendar" for="slug" class="col-form-label">Copy/paste this iCal feed into your Calendar App:</label>
			<input v-show="showAddCalendar" type="text" class="form-control" v-model="ical_url">
		</div>
	</div>

</div>
</template>

<script>
import common from '../../mixins.js';
export default {
	mixins: [common],
	props: ['orgId', 'orgName', 'eventId'],
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
			dont_reload: false,
			showAddCalendar: false,
			attributes: [],
			showCalendar: true,
		}
	},
	computed: {
		ical_url: function() {
			return Laravel.BASE_URL + '/api/events' + "?ical=true&gnz=" + this.state.gnz + "&other=" + this.state.other + "&type=" + this.state.type + "&timerange=" + this.state.timerange + "&org_id=" + this.orgId;
		}
	},
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

		this.load();

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
			// if (this.selectedOrg) {
			// 	data.org_id = this.selectedOrg.id;
			// }
			if (this.orgId) {
				data.org_id = this.orgId;
			}

			window.axios.get('/api/events/', {params: data}).then(function (response) {
				that.events = response.data.data;
				that.attributes = [];

				// process the data for the calendar
				for (var i=0; i<that.events.length; i++)
				{
					if (that.events[i].end_date) {
						var dates = [{
								'start': that.events[i].start_date,
								'end': that.events[i].end_date,
							}];
					} else {
						var dates = [
							that.events[i].start_date
						];
					}

					// set up the basic custom data needed for the badges e.g. event name
					var custom_data = { 
							name: that.events[i].name,
							orgShortName: null,
							showOrg: false,
							icon: that.getEventType(that.events[i].type).icon,
							colour: that.getEventType(that.events[i].type).colour,
							slug: that.events[i].slug
						};

					// add the organisation name and details
					if (that.events[i].org) {
						custom_data.orgShortName = that.events[i].org.short_name;
						// save if we should be showing the org name at all. Don't bother if we're in the same org as the item.
						if (that.events[i].org.id != that.orgId) {
							custom_data.showOrg = true;
						}
					}

					// push to the array of data for the calendar
					that.attributes.push({
						dates: dates,
						customData: custom_data,
						order: 0
					});
				}

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
