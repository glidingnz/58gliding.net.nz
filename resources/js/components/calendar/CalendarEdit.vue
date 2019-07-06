
<template>
	<div>

		<h1>Edit Club Calendar</h1>

		<v-date-picker :columns="4" mode="multiple" v-model="days" @dayclick="dayClicked" is-inline :min-date='new Date()' />

		<h2 class="mt-2">Selected Flying Days</h2>

		<table class="table table-striped">
			<tr>
				<th>Date</th>
				<th>Day Details</th>
				<th class="text-center">Tows</th>
				<th class="text-center">Winch</th>
				<th class="text-center">Trial Flights</th>
				<th class="text-center">Training</th>
				<th class="text-center">Comp</th>
				<th>Cancelled</th>
			</tr>
			<edit-calendar-row v-for="day in results" v-bind:key="day.id" :row="day" :org-id="orgId" ></edit-calendar-row>
		</table>


	</div>
</template>


<script>
	import common from '../../mixins.js';
	import VCalendar from 'v-calendar';
	import moment from 'moment';
	Vue.prototype.$moment = moment;

	export default {
		mixins: [common],
		props: ['orgId'],
		data() {
			return {
				results: [],
				days: [], // an array of the days this club has selected so far
				previousDays: [] // to be used to check what has changed since we clicked
			}
		},
		mounted() {
			this.load();
		},
		watch: {
			days: function(newDays, previousDays) {

			}
		},
		methods: {
			dayClicked: function(clickedDay) {

				// check if clicked day is in array or not.
				// The days array is not updated when this clicked function is called.
				if (this.days.find(item => {return item.getTime() == clickedDay.date.getTime()})) {
					// remove
					this.removeDay(clickedDay.date);
				} else {
					// insert
					this.addDay(clickedDay.date);
				}
			},
			load: function() {
				var that = this;
				// select all days from today onwards
				window.axios.get('/api/days?org_id=' + this.orgId + '&start_date=' + this.$moment().format('YYYY-MM-DD')).then(function (response) {
					that.results=[];
					that.results = response.data.data;

					// remove all existing days
					that.days = [];

					// update all calendar days from what's loaded
					for (var i=0; i<that.results.length; i++) {
						that.days.push(that.$moment(that.results[i].day_date, "YYYY-MM-DD").toDate());
					}
				});
			},
			addDay: function(date) {
				var that = this;
				// insert a day into the database
				var data = {
					org_id: this.orgId,
					day_date: this.$moment(date).format('YYYY-MM-DD') };
				window.axios.post('/api/days', data).then(function (response) {
					that.load();
				});
			},
			removeDay: function(date) {
				var that = this;
				var data = {
					org_id: this.orgId,
					day_date: this.$moment(date).format('YYYY-MM-DD')
					};
				window.axios.post('/api/days/deactivate', data).then(function (response) {
					that.load();
				});
			},
			renderDate: function(date) {
				return this.$moment(date).format('ddd, MMM Do YY');
			}
		}

	}
</script>