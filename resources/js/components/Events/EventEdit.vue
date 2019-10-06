<template>
<div>

	<h1><a href="/events">Events</a> » <a :href="'/events/' + event.slug">{{event.name}}</a> » Edit</h1>

	<form @submit="save">
		<div class="row">

			<div class="form-group col-md-6">
				<label for="featured" class="float-right">
					<input id="featured" type="checkbox" v-model="event.featured" :value="true">
					Feature Event on GNZ Website
				</label>
				<label for="name" class="col-xs-6 col-form-label">Name</label>
				<div class="col-xs-6">
					<input type="text" class="form-control" id="name" v-model.lazy="event.name">
				</div>
			</div>

			<div class="col-md-6">
				<div class="row">

					<div class="form-group col-sm-6">
						<label for="slug" class="col-form-label">Slug</label>
						<input type="text" class="form-control" id="slug" v-on:change="event.slug = slug(event.slug)" v-model.lazy="event.slug">

					</div>

					<div class="form-group col-sm-6">
						<label for="event_type" class="col-xs-6 col-form-label">Event Type</label>
						<div class="col-xs-6">
							<div class="form-inline">
								<select v-model="event.type" id="event_type" class="form-control">
									<option :value="null">Select a type of event...</option>
									<option :value="eventType.code" v-for="eventType in eventTypes()">{{eventType.name}}</option>
								</select>
							</div>
						</div>
					</div>

				</div>
			</div>

		</div>
		<div class="row">

			<div class="col-md-6">
				<div class="row">
					<div class="form-group col-6">
						<label for="start_date" class="col-form-label">Start Date<span v-show="event.type=='competition'"> (Inc. Practice Days)</span></label>
						<v-date-picker id="start_date" v-model="event.start_date" :locale="{ id: 'start_date', firstDayOfWeek: 2, masks: { weekdays: 'WW', L: 'DD/MM/YYYY' } }" :popover="{ visibility: 'click' }"></v-date-picker>
					</div>

					<div class="form-group col-6">
						<label for="end_date_checkbox" class="col-form-label">
							<input type="checkbox" id="end_date_checkbox" v-model="hasEndDate" v-on:click="hasEndDate ? event.end_date=event.start_date : 0">
							End Date <span  v-show="hasEndDate">({{dateDiffDays(event.start_date, event.end_date)}})</span>
						</label>
						<div v-show="hasEndDate">
							<v-date-picker v-model="event.end_date" :locale="{ id: 'end_date', firstDayOfWeek: 2, masks: { weekdays: 'WW', L: 'DD/MM/YYYY' } }" :popover="{ visibility: 'click' }"></v-date-picker>
						</div>
					</div>
				</div>
			</div>

			<div class="col-md-6">
				<div class="row">

					<div class="col-md-3 col-6">
						<label for="location" class="col-form-label">Start Time</label>
						<div class="">
							<input placeholder="e.g. 16:30" type="time" class="form-control" id="location"  v-model="event.start_time">
						</div>
					</div>
					<div class="col-md-3 col-6">
						
						<label for="location" class="col-form-label">End Time</label>
						<div class="">
							<input placeholder="e.g. 16:30" type="time" class="form-control" id="location" v-model="event.end_time">
						</div>
					</div>

					<div class="col-md-6">
						<label for="location" class="col-form-label">Location e.g. Matamata</label>
						<div class="">
							<input type="text" class="form-control" id="location" v-model="event.location">
						</div>
					</div>

				</div>
			</div>
		</div>

		<div class="row">
			<div class="form-group col-md-3">
				<input type="submit" value="Save Changes" class="btn btn-outline-dark mt-2">
				<input type="submit" v-on:click="returnOnSave=true" value="Save &amp; Return" class="btn btn-primary mt-2">
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
				<label for="facebook" class="col-xs-3 col-form-label">Facebook e.g glidingnewzealand</label>
				<div class="col-xs-6">
					<input class="form-control" id="facebook" type="text" v-model="event.facebook">
				</div>
			</div>
			<div class="form-group col-md-3">
				<label for="instagram" class="col-xs-3 col-form-label">Instagram e.g. glidingnz</label>
				<div class="col-xs-6">
					<input class="form-control" id="instagram" type="text" v-model="event.instagram">
				</div>
			</div>

		</div>


		<div class="row">

			<div class="col-md-6">
				<div class="row">
					
					<div class="col-6">
						<label for="cost" class="col-form-label">Cost</label>
							
						<div class="form-inline">
							$ <input id="cost" type="text" class="form-control ml-2 col-4" v-model="event.cost" size="4">
						</div>
					</div>


					<div class="col-6"  v-show="flyingEvent">
						<label for="practice_days" class="col-form-label">Practice Days</label>
							
						<div class="form-inline">
							<input id="practice_days" type="text" class="form-control mr-2" v-model="event.practice_days" size="4"> days
						</div>
					</div>

				</div>
			</div>

			<div class="form-group col-md-6" v-show="flyingEvent">
				<div class="row">
					
					
					<div class="col-6">
						<label for="earlybird" class="col-form-label">Earlybird End Date (inclusive)</label>
							
						<div class="form-inline">
							<v-date-picker id="earlybird" v-model="event.earlybird" :locale="{ id: 'earlybird', firstDayOfWeek: 2, masks: { weekdays: 'WW', L: 'DD/MM/YYYY' } }" :popover="{ visibility: 'click' }"></v-date-picker>
						</div>
					</div>

					<div class="col-6">
						<label for="cost_earlybird" class="col-form-label">Earlybird Cost</label>
							
						<div class="form-inline">
							$ <input id="cost_earlybird" type="text" class="form-control ml-2 col-4" v-model="event.cost_earlybird" size="4">
						</div>
					</div>
				
				</div>
			</div>
		</div>




		<div class="row">

			<div class="form-group col-md-6">
				<label for="details" class="col-xs-6 col-form-label">Page Details (Markdown available)</label>
				<div class="col-xs-6">
					<autosize-textarea>
						<textarea type="text" class="form-control" id="details" v-model="event.details" rows="3"></textarea>
					</autosize-textarea>

					<input type="submit" value="Save Changes" class="btn btn-outline-dark mt-2">
					<input type="submit" v-on:click="returnOnSave=true" value="Save &amp; Return" class="btn btn-primary mt-2">
				</div>
			</div>
			<div class="form-group col-md-6">
				<label for="start_date" class="col-xs-6 col-form-label">Page Preview</label>
				<div class="col-xs-6">

					<div class="card">
						<div v-if="event.details" class="card-body" v-html="compiledMarkdown"></div>
						<div v-if="!event.details" class="card-body" >&nbsp;</div>
					</div>

				</div>
			</div>

		</div>


		<div class="row" v-if="flyingEvent">

			<div class="form-group col-md-6">
				<label for="terms" class="col-xs-6 col-form-label">Terms &amp; Conditions (Markdown available)</label>
				<div class="col-xs-6">
					<autosize-textarea>
						<textarea type="text" class="form-control" id="terms" v-model="event.terms" rows="3"></textarea>
					</autosize-textarea>
					<input type="submit" value="Save Changes" class="btn btn-outline-dark mt-2">
					<input type="submit" v-on:click="returnOnSave=true" value="Save &amp; Return" class="btn btn-primary mt-2">
				</div>
			</div>
			<div class="form-group col-md-6">
				<label for="start_date" class="col-xs-6 col-form-label">Terms Preview</label>
				<div class="col-xs-6">

					<div class="card">
						<div v-if="event.terms" class="card-body" v-html="compiledTermsMarkdown"></div>
						<div v-if="!event.terms" class="card-body" >&nbsp;</div>
					</div>

				</div>
			</div>

		</div>


		<div class="row" v-if="flyingEvent">

			<div class="form-group col-md-6">
				<label for="soaringspot_api_secret" class="col-xs-6 col-form-label">SoaringSpot API Secret</label>
				<div class="col-xs-6">
					<input class="form-control" id="soaringspot_api_secret" type="text" v-model="event.soaringspot_api_secret">
				</div>
			</div>
			<div class="form-group col-md-6">
				<label for="soaringspot_api_client_id" class="col-xs-6 col-form-label">SoaringSpot API Client ID</label>
				<div class="col-xs-6">
					<input class="form-control" id="soaringspot_api_client_id" type="text" v-model="event.soaringspot_api_client_id">
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
			hasEndDate: false,
			returnOnSave: false
		}
	},
	props: ['orgId', 'eventId'],
	created: function() {
		this.load();
	},
	computed: {
		compiledMarkdown: function () {
			return marked(this.event.details, { sanitize: true })
		},
		compiledTermsMarkdown: function () {
			return marked(this.event.terms, { sanitize: true })
		},
		flyingEvent: function() {
			switch (this.event.type)
			{
				case 'competition':
				case 'xcountry': 
					return true;
					break;
			}
			return false;
		}
	},
	methods: {
		load: function() {
			var that = this;
			window.axios.get('/api/events/' + this.eventId).then(function (response) {
				that.event = response.data.data;
				if (that.event.start_date!=that.event.end_date) {
					that.hasEndDate=true;
				}
				that.event.start_date = that.$moment(that.event.start_date).toDate();
				that.event.end_date = that.$moment(that.event.end_date).toDate();
				that.event.earlybird = that.$moment(that.event.earlybird).toDate();
			});
		},
		save: function(e) {
			var that = this;

			// shallow copy the object so we can alter the dates
			let event = Object.assign({}, this.event);
			event.start_date = this.apiDateFormat(event.start_date);
			event.end_date = this.apiDateFormat(event.end_date);
			event.earlybird = this.apiDateFormat(event.earlybird);

			window.axios.put('/api/events/' + this.eventId, event).then(function (response) {
				messages.$emit('success', 'Event ' + that.event.name + ' Updated');
				if (that.returnOnSave) {
					window.location.href = "/events/" + event.slug;
				}
			});
			e.preventDefault();
		},
		selectedMember: function(member)
		{
			Vue.set(this.event, 'organiser_member_id', member.id);
			//this.event.organiser_member_id = member.id;
		}
	}
}
</script>
