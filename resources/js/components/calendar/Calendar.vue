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

	td:nth-of-type(4):before { content: "Day Cancelled "; }

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

		<table class="table table-striped table-sm collapsable calendar-table">
			<tr>
				<th>Date</th>
				<th>Available</th>
				<th>Notes</th>
			</tr>
			<tr v-for="day in results" v-bind:key="day.id" :row="day" :org-id="orgId" v-on:rowupdated="load()">
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
				results: []
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
					that.results=[];
					that.results = response.data.data;
				});
			},
			renderDate: function(date) {
				return this.$moment(date).format('ddd, MMM Do YY');
			},
			renderDescription: function(description) {
				if (description==null) return null;
				return description.replace(/(?:\r\n|\r|\n)/g, '<br>');
			}
		}

	}
</script>