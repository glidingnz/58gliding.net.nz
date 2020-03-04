<style>
	.event-details .card-body {
		font-size: 120%;
	}
</style>

<template>
<div>
	
	<div class="float-right">
		<a class="btn btn-outline-dark mr-2" :href="'/events/' + event.slug + '/edit'" v-if="event.can_edit">Edit</a>
		<!-- <a class="btn btn-outline-dark" :href="'/events/' + event.slug + '/delete'" v-if="event.can_edit">Delete</a> -->
	</div>

	<h1><a href="/events">Events</a> » {{event.name}}</h1>


	<div class="row">
		
		<div class="col-md-6">

			<h2>{{formatStartsIn(event.start_date_moment)}}</h2>

			<h2>{{event.location}}</h2>

			<div v-if="event.details" v-html="compiledMarkdown"></div>


			<div class="mt-2 mb-4">
					<v-calendar :columns="$screens({ default: 1, lg: 2 })" :attributes='attributes' :first-day-of-week="2" :from-date="event.start_date" ref="calendar" />
					
			</div>
		</div>

		<div class="col-md-6">
			<div class="card event-details">
				<div class="card-header">
					Details
				</div>
				<div class="card-body">

					<div class="row mb-2" v-if="event.start_date">
						<div class="col-4 label">Date</div>
						<div class="col-8">
							{{formatDate(event.start_date)}}<span v-if="hasEndDate"> - {{formatDate(event.end_date)}}</span>
						</div>
					</div>

					<div class="row mb-2" v-if="event.start_time">
						<div class="col-4 label">Start Time</div>
						<div class="col-8">{{event.start_time}}</div>
					</div>

					<div class="row mb-2" v-if="event.end_time">
						<div class="col-4 label">End Time</div>
						<div class="col-8">{{event.end_time}}</div>
					</div>

					<div class="row mb-2" v-if="event.practice_days">
						<div class="col-4 label">Practice Days</div>
						<div class="col-8">{{event.practice_days}}</div>
					</div>

					<div class="row mb-2">
						<div class="col-4 label">Organisation</div>
						<div class="col-8">
							<span v-if="event.org">{{event.org.name}}</span>
							<span v-if="!event.org">Gliding New Zealand</span>
						</div>
					</div>

					<div class="row mb-2" v-if="event.location">
						<div class="col-4 label">Location</div>
						<div class="col-8">{{event.location}}</div>
					</div>

					<div class="row mb-2" v-if="event.cost">
						<div class="col-4 label">Cost</div>
						<div class="col-8">${{event.cost}}</div>
					</div>

					<div class="row mb-2" v-if="event.cost_earlybird">
						<div class="col-4 label">Earlybird</div>
						<div class="col-8">${{event.cost_earlybird}}<span v-if="event.earlybird"></span></div>
					</div>

					<div class="row mb-2" v-if="event.cost_earlybird">
						<div class="col-4 label"></div>
						<div class="col-8"><span v-if="event.earlybird">{{formatStartsIn(event.earlybird, 'Ends', 'Ended')}} ({{formatDate(event.earlybird)}})</span></div>
					</div>

					<div class="row mb-2" v-if="event.website">
						<div class="col-4 label">Website</div>
						<div class="col-8"><a :href="event.website">{{event.website}}</a></div>
					</div>

					<div class="row mb-2" v-if="event.facebook">
						<div class="col-4 label">Facebook</div>
						<div class="col-8"><a :href="'http://facebook.com/' + event.facebook">{{event.facebook}}</a></div>
					</div>

					<div class="row mb-2" v-if="event.instagram">
						<div class="col-4 label">Instagram</div>
						<div class="col-8"><a :href="'http://instagram.com/' + event.instagram">{{event.instagram}}</a></div>
					</div>

					<div class="row mb-2" v-if="event.organiser_name">
						<div class="col-4 label">Contact Name</div>
						<div class="col-8">{{event.organiser_name}}</div>
					</div>

					<div class="row mb-2" v-if="event.organiser_phone">
						<div class="col-4 label">Contact Phone </div>
						<div class="col-8">{{event.organiser_phone}}</div>
					</div>


					<div class="row mb-2" v-if="event.email">
						<div class="col-4 label">Email</div>
						<div class="col-8"><a :href="'mailto:' + event.email">{{event.email}}</a></div>
					</div>

				</div>
			</div>
		</div>

	</div>



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
			attributes: [],
			calendar: null
		}
	},
	props: ['orgId', 'eventId'],
	created: function() {
		this.load();
	},
	mounted: function() {
		this.calendar = this.$refs.calendar;
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
				that.event.start_date_moment = that.$moment(that.event.start_date);
				if (that.event.end_date)that.event.end_date_moment = that.$moment(that.event.end_date);
				if (that.event.earlybird) that.event.earlybird_moment = that.$moment(that.event.earlybird);

				that.event.start_date = that.$moment(that.event.start_date).toDate();
				if (that.event.end_date) that.event.end_date = that.$moment(that.event.end_date).toDate();
				if (that.event.earlybird) that.event.earlybird = that.$moment(that.event.earlybird).toDate();

				// figure out practice dates
				var practice_days = 0;
				if (that.event.practice_days) {
					practice_days = that.event.practice_days;
				}

				var comp_start_date = that.$moment(that.event.start_date).add(practice_days, 'days').toDate();
				var practice_end_date = that.$moment(that.event.start_date).add(practice_days-1, 'days').toDate();


				if (that.event.practice_days) {

					// push the actual contest days
					that.attributes.push({
						highlight: {
							color: 'purple', // Red
							fillMode: 'solid',
						},
						dates: [
							{
								'start': that.event.start_date,
								'end': practice_end_date,
							}
						],
						order: 1
					});
				}

				// push the actual contest days into the calendar
				if (that.event.end_date) {
					var dates = [{
							'start': that.event.start_date,
							'end': that.event.end_date,
						}];
				} else {
					var dates = [
						that.event.start_date
					];
				}

				that.attributes.push({
					highlight: {
						fillMode: 'solid',
					},
					dates: dates,
					order: 0
				});

				if (that.event.earlybird) {
					// push the actual contest days
					that.attributes.push({
						highlight: {
							color: 'red', // Red
							fillMode: 'solid',
						},
						dates: [
							that.event.earlybird
						],
						order: 2
					});
				}
				

				// move calendar to the date range
				that.calendar.showPageRange({ from: that.event.start_date });
				
			});
		}
	}
}
</script>
