<style>

</style>

<template>
	<div>

		<h1>Edit Club Calendar</h1>

		<v-date-picker :columns="3" mode="multiple" v-model="days" @input="test" is-inline />

		<p>Select active flying days above</p>


		{{days}}


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
				days: [] // an array of the days this club has selected so far
			}
		},
		mounted() {
			this.load();
		},
		watch: {
			days: function(newDays, previousDays) {

				// work out if we've added or removed a day
				var addedDays = newDays.filter(function(val) {
					return previousDays.indexOf(val) == -1;
				});

				var removedDays = previousDays.filter(function(val) {
					return newDays.indexOf(val) == -1;
				});

				// if we have a day in the addedDays array, we can insert it
				if (addedDays.length>0) {
					this.addDay(addedDays[0]);
				}

				// if we have a day in the addedDays array, we can disable it
				if (removedDays.length>0) {
					this.removeDay(removedDays[0]);
				}

			}
		},
		methods: {
			test: function(selecteddate) {
				//console.log(selecteddate);
			},
			load: function() {
				// var that = this;
				// window.axios.get('/api/v1/achievements?member_id=' + this.memberId).then(function (response) {
				// 	that.results = response.data.data;
				// });
			},
			addDay: function(date) {
				// insert a day into the database
					console.log(this);
				var data = {
					org_id: this.orgId,
					day_date: this.$moment(date).format('YYYY-MM-DD')
					};
				window.axios.post('/api/days', data).then(function (response) {
						console.log(response);
					});
			},
			removeDay: function(date) {
				// insert a day into the database
				// window.axios.delete('/api/v1/days/' + , {}).then(function (response) {
				// 		console.log(response);
				// 	});
			}
		}

	}
</script>