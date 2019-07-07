<style>
.date {
	font-size: 170%;
	font-weight: bold;
}
</style>

<template>
	<div class="">

		<h1><a href="/calendar/">Calendar</a> &gt; Edit Roster</h1>

		<table class="table table-striped table-sm collapsable">
			<tr>
				<th>Date</th>
				<th>Available</th>
			</tr>
			<template v-for="day in results" >
				<tr >
					<td>
						<b>{{renderDate(day.day_date)}}</b>
					</td>
					<td>
						<span v-if="day.winching"><span class="fa fa-check"></span> Winching</span>
						<span v-if="day.towing"><span class="fa fa-check"></span> Towing</span>
						<span v-if="day.training"><span class="fa fa-check"></span> Training</span>
						<span v-if="day.trialflights"><span class="fa fa-check"></span> Trial Flights</span>
					</td>
				</tr>
				<tr v-if="day.description">
					<td colspan="2">
						<span v-html="renderDescription(day.description)"></span>
					</td>
				</tr>
			</template>
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
			this.loadDays();
		},
		watch: {
		},
		methods: {
			loadDays: function() {
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