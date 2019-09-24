<template>
<div>
	
	<h1><a href="/events">Events</a> » <a :href="'/events/' + event.slug">{{event.name}}</a> » Edit</h1>

	<form>
		<div class="row">

			<div class="form-group col-md-6">
				<label for="name" class="col-xs-6 col-form-label">Name</label>
				<div class="col-xs-6">
					<input type="text" class="form-control" id="name"  v-model="event.name">
				</div>
			</div>

			<div class="form-group col-md-6">
				<label for="slug" class="col-xs-6 col-form-label">Slug</label>
				<div class="col-xs-6">
					<input type="text" class="form-control" id="slug" v-model="event.slug">
				</div>
			</div>

		</div>
		<div class="row">

			<div class="form-group col-md-6">
				<label for="start_date" class="col-xs-6 col-form-label">Start Date</label>
				<div class="col-xs-6">
					<v-date-picker v-model="event.start_date" :locale="{ id: 'start_date', firstDayOfWeek: 2, masks: { weekdays: 'WW', L: 'DD/MM/YYYY' } }" :popover="{ visibility: 'click' }"></v-date-picker>
				</div>
			</div>
			<div class="form-group col-md-6">
				<label for="start_date" class="col-xs-6 col-form-label">End Date {{dateDiffDays(event.start_date, event.end_date)}}</label>
				<div class="col-xs-6">
					<v-date-picker v-model="event.end_date" :locale="{ id: 'end_date', firstDayOfWeek: 2, masks: { weekdays: 'WW', L: 'DD/MM/YYYY' } }" :popover="{ visibility: 'click' }"></v-date-picker>
				</div>
			</div>

		</div>


	</form>


</div>
</template>

<script>
import common from '../../mixins.js';
export default {
	mixins: [common],
	data: function() {
		return {
			event: [],
			newDutyName: ''
		}
	},
	props: ['orgId', 'eventId'],
	created: function() {
		this.load();
	},
	methods: {
		load: function() {
			var that = this;
			window.axios.get('/api/events/' + this.eventId).then(function (response) {
				that.event = response.data.data;
				that.event.start_date = that.$moment(that.event.start_date).toDate();
				that.event.end_date = that.$moment(that.event.end_date).toDate();
			});
		}
	}
}
</script>
