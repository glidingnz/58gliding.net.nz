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
				<label for="slug" class="col-xs-6 col-form-label">Slug (web page address)</label>
				<div class="col-xs-6 form-inline">
					/events/<input type="text" class="form-control inline" id="slug" v-model="event.slug">
				</div>
			</div>

		</div>
		<div class="row">

			<div class="form-group col-md-3">
				<label for="start_date" class="col-xs-6 col-form-label">Start Date</label>
				<div class="col-xs-3">
					<v-date-picker v-model="event.start_date" :locale="{ id: 'start_date', firstDayOfWeek: 2, masks: { weekdays: 'WW', L: 'DD/MM/YYYY' } }" :popover="{ visibility: 'click' }"></v-date-picker>
				</div>
			</div>
			<div class="form-group col-md-3">
				<label for="start_date" class="col-xs-6 col-form-label">End Date ({{dateDiffDays(event.start_date, event.end_date)}})</label>
				<div class="col-xs-3">
					<v-date-picker v-model="event.end_date" :locale="{ id: 'end_date', firstDayOfWeek: 2, masks: { weekdays: 'WW', L: 'DD/MM/YYYY' } }" :popover="{ visibility: 'click' }"></v-date-picker>
				</div>
			</div>

			<div class="form-group col-md-6">
				<label for="location" class="col-xs-6 col-form-label">Location e.g. Matamata</label>
				<div class="col-xs-6">
					<input type="text" class="form-control" id="location" v-model="event.location">
				</div>
			</div>

		</div>

		<div class="row">

			<div class="form-group col-md-6">
				<label for="event_type" class="col-xs-6 col-form-label">Event Type</label>
				<div class="col-xs-6">
					
					<select v-model="event.type" id="event_type" class="form-control">
						<option :value="null">Select a type of event...</option>
						<option value="competition">Competition</option>
						<option value="training">Training</option>
						<option value="xcountry">Cross Country Course</option>
						<option value="dinner">Dinner</option>
						<option value="working-bee">Working Bee</option>
						<option value="cadets">Cadets</option>
						<option value="school-group">School Group</option>
					</select>

				</div>
			</div>

			<div class="form-group col-md-6">
				<label for="organiser" class="col-xs-6 col-form-label">Organiser</label>
				<div class="col-xs-6">
					
					<member-selector :org-id="orgId" :member-id="event.organiser_member_id"></member-selector>

				</div>
			</div>

		</div>

		<div class="row">

			<div class="form-group col-md-6">
				<label for="details" class="col-xs-6 col-form-label">Details (Markdown available)</label>
				<div class="col-xs-6">
					<autosize-textarea>
						<textarea type="text" class="form-control" id="details" v-model="event.details" rows="5"></textarea>
					</autosize-textarea>
				</div>
			</div>
			<div class="form-group col-md-6">
				<label for="start_date" class="col-xs-6 col-form-label" v-show="event.details">Page Preview</label>
				<div class="col-xs-6">

					<div class="card" v-if="event.details">
						<div class="card-body" v-html="compiledMarkdown"></div>
					</div>

				</div>
			</div>

		</div>



	</form>


</div>
</template>

<script>
import common from '../../mixins.js';
var marked = require('marked');
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
	computed: {
		compiledMarkdown: function () {
			return marked(this.event.details, { sanitize: true })
		}
	},
	methods: {
		load: function() {
			var that = this;
			window.axios.get('/api/events/' + this.eventId).then(function (response) {
				that.event = response.data.data;
				that.event.start_date = that.$moment(that.event.start_date).toDate();
				that.event.end_date = that.$moment(that.event.end_date).toDate();
			});
		},
		save: function() {
			window.axios.post('/api/events/' + this.eventId).then(function (response) {
				that.event = response.data.data;
				messages.$emit('success', 'Event ' + that.newEventName + ' Updated');
			});
		}
	}
}
</script>
