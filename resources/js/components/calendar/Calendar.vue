<style>
.calendar-table .date {
	white-space: nowrap;
	font-weight: bold;
}

@media 
only screen and (max-width: 760px),
(min-device-width: 768px) and (max-device-width: 1024px)  {

	/* Force table to not be like tables anymore */
	table.collapsable, .collapsable thead, .collapsable tbody, .collapsable th, .collapsable td, .collapsable tr { 
		display: block; 
	}

	.calendar-table .date {
		font-size: 170%;
	}

	.calendar-table th {
		display: none !important;
	}
}
</style>

<template>
	<div>

		<calendar-nav active="calendar" title="Calendar"></calendar-nav>

		<div class="custom-modal" v-show="showCustomModal" v-on:click="closeCustomModal()" @keyup.esc="closeCustomModal()" tabindex="0" v-if="Laravel.clubAdmin==true">
			<div class="inner" v-on:click.stop="">

				<button v-on:click="closeCustomModal()" class="btn btn-outline-dark float-right">Cancel</button>
				
				<div class="form-group">
					<h2>Add Event</h2>
				</div>

				<div class="form-group">
					<label>Event Name</label> <span class="error" v-show="showNameRequired">Name is required</span>
					<input type="text" class="form-control" v-model="newEventName" ref="newName">

				</div>

				<div class="form-group">
					<label>Event Date</label>
					<v-date-picker v-model="newEventDate" :locale="{ id: 'nz', firstDayOfWeek: 2, masks: { weekdays: 'WW', L: 'DD/MM/YYYY' } }"></v-date-picker>
				</div>

				<div class="form-group">
					<button v-on:click="addEvent()" class="btn btn-outline-dark">Add Event</button>
				</div>

			</div>
		</div>

		
		<table class="table table-striped table-sm collapsable calendar-table">
			<tr>
				<th>Date</th>
				<th>Available</th>
				<th>Notes</th>
				<th class="center" v-if="Laravel.clubAdmin==true">Add Event</th>
			</tr>
			<tr v-for="day in days" v-bind:key="day.id" :row="day" :org-id="orgId" v-on:rowupdated="load()">
				<td>
					<b>{{renderDate(day.day_date)}}</b>
				</td>
				<td>
					
					<span v-if="day.winching"><span class="fa fa-check"></span> Winching</span>
					<span v-if="day.towing"><span class="fa fa-check"></span> Towing</span>
					<span v-if="day.training"><span class="fa fa-check"></span> Training</span>
					<span v-if="day.trialflights"><span class="fa fa-check"></span> Trial Flights</span>
				</td>
				<td>
					<div v-if="day.cancelled" class="bg-danger text-white px-2 py-1 rounded-lg">
						<span class="fa fa-exclamation-circle text-white"></span> Day Cancelled<span v-if="day.cancelled_reason">: {{day.cancelled_reason}}</span>
					</div>
					<span v-html="renderDescription(day.description)"></span>
				</td>
				<td class="center" v-if="Laravel.clubAdmin==true">
					<button class="btn compact-btn" v-on:click="openCustomModal(day.day_date)"><span class="fa fa-plus-square"></span></button>
				</td>
			</tr>
		</table>

	</div>
</template>


<script>
	import common from '../../mixins.js';
	//import VCalendar from 'v-calendar';
	import moment from 'moment';
	Vue.prototype.$moment = moment;

	export default {
		mixins: [common],
		props: ['orgId'],
		data() {
			return {
				days: [],
				events: [],
				showCustomModal: false,
				newEventName: '',
				newEventDate: null,
				showNameRequired: false
			}
		},
		mounted() {
			this.load();
		},
		watch: {
		},
		methods: {
			load: function() {
				var that = this;
				// select all days from today onwards
				window.axios.get('/api/days?org_id=' + this.orgId + '&start_date=' + this.$moment().format('YYYY-MM-DD')).then(function (response) {
					that.days=[];
					that.days = response.data.data;
				});
			},
			loadEvents: function() {
				var that = this;
				// select all events from today onwards
				window.axios.get('/api/events?org_id=' + this.orgId + '&start_date=' + this.$moment().format('YYYY-MM-DD')).then(function (response) {
					that.events=[];
					that.events = response.data.data;
				});
			},
			renderDate: function(date) {
				return this.$moment(date).format('ddd, MMM Do YY');
			},
			renderDescription: function(description) {
				if (description==null) return null;
				return description.replace(/(?:\r\n|\r|\n)/g, '<br>');
			},
			openCustomModal: function(day_date) {
				this.newEventDate = this.$moment(day_date).toDate();
				this.showCustomModal = true;
				this.$nextTick(() => this.$refs.newName.focus());
			},
			closeCustomModal: function() {
				this.showCustomModal = false;
			},
			addEvent: function() {
				var that = this;
				if (this.newEventName=='') {
					messages.$emit('error', 'A name is required');
					this.showNameRequired = true;
				}
				else
				{
					var data = {
						"name": this.newEventName,
						"start_date":  this.$moment(this.newEventDate).format('YYYY-MM-DD'),
						"org_id" : this.orgId
					}
					window.axios.post('/api/events', data).then(function (response) {
						messages.$emit('success', 'Event ' + that.newEventName + ' added');
						that.closeCustomModal();
					});
				}

				
			}
		}

	}
</script>