<template>
<div>
	
	<h1><a href="/events">Events</a> » <a :href="'/events/' + event.slug">{{event.name}}</a> » Edit</h1>

	<form @change="save">
		<div class="row">

			<div class="form-group col-md-6">
				<label for="name" class="col-xs-6 col-form-label">Name</label>
				<div class="col-xs-6">
					<input type="text" class="form-control" id="name" v-model.lazy="event.name">
				</div>
			</div>

			<div class="col-md-6 row">

				<div class="form-group col-sm-6">
					<label for="slug" class="col-form-label">Slug</label>
					<input type="text" class="form-control" id="slug" v-on:change="event.slug = slug(event.slug)" v-model="event.slug">

				</div>

				<div class="form-group col-sm-6">
					<label for="event_type" class="col-xs-6 col-form-label">Event Type</label>
					<div class="col-xs-6">
						<div class="form-inline">
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
				</div>

			</div>

		</div>
		<div class="row">

			<div class="col-md-6">
				<div class="row">
					<div class="form-group col-6">
						<label for="start_date" class="col-form-label">Start Date</label>
						<v-date-picker id="start_date" v-model="event.start_date" :locale="{ id: 'start_date', firstDayOfWeek: 2, masks: { weekdays: 'WW', L: 'DD/MM/YYYY' } }" :popover="{ visibility: 'click' }" @input="save()"></v-date-picker>
					</div>

					<div class="form-group col-6">
						<label for="end_date_checkbox" class="col-form-label">
							<input type="checkbox" id="end_date_checkbox" v-model="hasEndDate">
							End Date <span  v-show="hasEndDate">({{dateDiffDays(event.start_date, event.end_date)}})</span>
						</label>
						<div v-show="hasEndDate">
							<v-date-picker v-model="event.end_date" :locale="{ id: 'end_date', firstDayOfWeek: 2, masks: { weekdays: 'WW', L: 'DD/MM/YYYY' } }" :popover="{ visibility: 'click' }" @input="save()"></v-date-picker>
						</div>
					</div>
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
				<label for="website" class="col-xs-6 col-form-label">Website e.g. http://gliding.co.nz/</label>
				<div class="col-xs-6 flex">
					<input class="form-control" id="website" type="text" v-model="event.website">
				</div>
			</div>
			<div class="form-group col-md-3">
				<label for="start_date" class="col-xs-3 col-form-label">Facebook e.g glidingnewzealand</label>
				<div class="col-xs-6">
					<input class="form-control" id="website" type="text" v-model="event.website">
				</div>
			</div>
			<div class="form-group col-md-3">
				<label for="start_date" class="col-xs-3 col-form-label">Instagram e.g. glidingnz</label>
				<div class="col-xs-6">
					<input class="form-control" id="website" type="text" v-model="event.website">
				</div>
			</div>

		</div>



		<div class="row">

			<div class="form-group col-md-6">
				<label for="details" class="col-xs-6 col-form-label">Details (Markdown available)</label>
				<div class="col-xs-6">
					<autosize-textarea>
						<textarea type="text" class="form-control" id="details" v-model="event.details" rows="3"></textarea>
					</autosize-textarea>
				</div>
			</div>
			<div class="form-group col-md-6">
				<label for="start_date" class="col-xs-6 col-form-label">Page Preview</label>
				<div class="col-xs-6">

					<div class="card">
						<div v-if="event.details" class="card-body" v-html="compiledMarkdown"></div>
						<div v-if="!event.details" class="card-body" >&lt;-- Type some text!</div>
					</div>

				</div>
			</div>

		</div>


		<div class="row">

			<div class="form-group col-md-6">
				<label for="organiser" class="col-xs-6 col-form-label">Organiser</label>
				<div class="col-xs-6">
					
					<member-selector :org-id="orgId" :member-id="event.organiser_member_id"></member-selector>

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
			newDutyName: '',
			hasEndDate: false
		}
	},
	props: ['orgId', 'eventId'],
	created: function() {
		this.load();
		if (this.event.start_date == this.event.end_date) this.hasEndDate=false;
		else this.hasEndDate=true;
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
			var that = this;

			// shallow copy the object so we can alter the dates
			let event = Object.assign({}, this.event);
			event.start_date = this.apiDateFormat(event.start_date);
			event.end_date = this.apiDateFormat(event.end_date);

			window.axios.put('/api/events/' + this.eventId, event).then(function (response) {
				messages.$emit('success', 'Event ' + that.event.name + ' Updated');
			});
		}
	}
}
</script>
