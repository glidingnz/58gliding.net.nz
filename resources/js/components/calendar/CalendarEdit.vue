<template>
	<div>

		<calendar-nav active="edit-calendar" title="Edit Flying Days"></calendar-nav>

		<v-date-picker mode="multiple" v-model="days" @dayclick="dayClicked" :columns="$screens({ default: 1, md:2, lg: 3, xl:4 })" is-inline :min-date="new Date()" />

		<h2 class="mt-2">Selected Flying Days</h2>

		<table class="table table-striped table-sm collapsable calendar-table">
			<tr>
				<th>Date</th>
				<th>Day Details</th>
				<th>Day Options</th>
				<th>Cancelled</th>
			</tr>
			<edit-calendar-row v-for="day in results" v-bind:key="day.id" :row="day" :org-id="orgId" v-on:rowupdated="rowUpdated()"></edit-calendar-row>
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
			rowUpdated: function() {
				messages.$emit('success', 'Row Saved');
				this.load();
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
					messages.$emit('success', 'Day Added');
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
					messages.$emit('success', 'Day Removed');
					that.load();
				});
			},
			renderDate: function(date) {
				return this.$moment(date).format('ddd, MMM Do YY');
			}
		}

	}
</script>