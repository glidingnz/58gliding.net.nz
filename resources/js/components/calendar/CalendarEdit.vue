<style>

</style>

<template>
	<div>

		<h1>Edit Club Calendar</h1>

		<v-date-picker :columns="3" mode="multiple" v-model="days" @dayclick="dayClicked" is-inline />

		<p>Select active flying days above</p>



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


				// check if clicked day is in array or not
				if (this.days.includes(clickedDay.date)) {
					// remove
					this.removeDay(clickedDay.date);
				} else {
					// insert
					this.addDay(clickedDay.date);
				}
			},
			load: function() {
				var that = this;
				window.axios.get('/api/days?org_id=' + this.orgId).then(function (response) {
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
				// insert a day into the database
				var data = {
					org_id: this.orgId,
					day_date: this.$moment(date).format('YYYY-MM-DD')
					};
				window.axios.post('/api/days', data).then(function (response) {
						console.log(response);
					});
			},
			removeDay: function(date) {

				var data = {
					org_id: this.orgId,
					day_date: this.$moment(date).format('YYYY-MM-DD')
					};
				window.axios.post('/api/days/deactivate', data).then(function (response) {
						console.log(response);
					});


				// insert a day into the database
				// window.axios.delete('/api/v1/days/' + , {}).then(function (response) {
				// 		console.log(response);
				// 	});
			}
		}

	}
</script>