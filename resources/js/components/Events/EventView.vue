<template>
<div>
	
	<div class="float-right">
		<a class="btn btn-outline-dark mr-2" :href="'/events/' + event.slug + '/edit'" v-if="event.can_edit">Edit</a>
		<a class="btn btn-outline-dark mr-2" :href="'/events/' + event.slug + '/enter'" >Entry Form</a>
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

			<div class="card mb-4">
				<div class="card-header">
					<h3>Details</h3>
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

					<div class="row mb-2" v-if="selectedClasses.length>0">
						<div class="col-4 label">Classes</div>
						<div class="col-8">
							<span v-for="selectedClass in selectedClasses" class="mr-4">
								{{selectedClass.contest_class.name}}
							</span>
						</div>
					</div>

					<div class="row mb-2" v-if="catering">
						<div class="col-4 label">Catering Available</div>
						<div class="col-8">
							<span class="mr-2" v-show="event.catering_breakfasts">Breakfasts</span>
							<span class="mr-2" v-show="event.catering_lunches">Lunches</span>
							<span class="mr-2" v-show="event.catering_dinners">Dinners</span>
							<span class="mr-2" v-show="event.catering_final_dinner">Final Dinner</span>
						</div>
					</div>

					<div class="row mb-2" v-if="contestAdmin">
						<div class="col-4 label">All Emails</div>
						<input type="text" class="col-8 form-control" name="allEmails" :value="allEmails">
					</div>
					<div class="row mb-2" v-if="contestAdmin">
						<div class="col-4 label">All Mobiles</div>
						<input type="text" class="col-8 form-control" name="allMobiles" :value="allMobiles">
					</div>
				</div>
			</div>
		</div>
	</div>


	<div class="row" v-if="entries.length>0">
		<div class="col-md-12">

			<div class="card ">
				<div class="card-header">
					<h4 class="float-right">
						<span class="ml-2"><span class="text-muted">Entries: </span>{{entriesCount}}  </span>

						<span  class="ml-2" v-for="selectedClass in selectedClasses">
							<span class="text-muted">{{selectedClass.contest_class.name}}</span> {{classPilotCount(selectedClass.class_id)}}
						</span>

					</h4>
					<h4>Entries</h4>
				</div>
				<div class="card-body">
					
					<table class="table collapsable">
						<tr>
							<th>Name</th>
							<th>Mobile</th>
							<th v-if="selectedClasses.length>0">Class</th>
							<th>Glider</th>
							<th>Model</th>
							<th>Wingspan</th>
							<th>Handicap</th>
							<th>Status</th>
							<th></th>
						</tr>
						<tr v-for="entry in pilotEntries">
							<td>
								<h3 class="d-md-none mt-4">{{entry.first_name}} {{entry.last_name}}</h3>
								<span class="d-none d-md-block">{{entry.first_name}} {{entry.last_name}}</span>
							</td>
								<td>
									{{entry.mobile}}
								</td>
							<td v-if="selectedClasses.length>0">
								<span v-if="entry.contest_class">{{entry.contest_class.name}}</span>
							</td>
							<td>
								<span v-if="entry.aircraft">{{entry.aircraft.rego}}</span>
							</td> 
							<td>
								<span v-if="entry.aircraft">{{entry.aircraft.model}}</span>
							</td> 
							<td><span class="d-md-none text-muted">Wingspan: </span>{{entry.wingspan}}</td>
							<td><span class="d-md-none text-muted">Handicap: </span>{{entry.handicap}}</td>
							<td>
								<span class="d-md-none text-muted">Entry Status: </span>{{entry.entry_status}}
								<span class="badge badge-danger" v-if="!entry.signed">Not Signed</span>
							</td>
							<td>
								<a v-if="entry.editcode" class="fa fa-edit" :href="'/entries/' + entry.editcode"></a>
							</td>
						</tr>
					</table>


					<div v-if="secondPilotEntries.length>0">
						<h3>Second Pilots</h3>

						<table class="table collapsable">
							<tr>
								<th>Name</th>
								<th>Mobile</th>
								<th>Class</th>
								<th>Glider</th>
								<th>Model</th>
								<th>Status</th>
								<th></th>
							</tr>
							<tr v-for="entry in secondPilotEntries">
								<td>
									<h3 class="d-md-none mt-4">{{entry.first_name}} {{entry.last_name}}</h3>
									<span class="d-none d-md-block">{{entry.first_name}} {{entry.last_name}}</span>
								</td>
								<td>
									{{entry.mobile}}
								</td>
								<td>
									<span v-if="entry.contest_class">{{entry.contest_class.name}}</span>
									<span v-if="!entry.contest_class">Unkonwn</span>
								</td>
								<td>
									<span v-if="entry.aircraft">{{entry.aircraft.rego}}</span>
									<span v-if="!entry.aircraft">Unkonwn</span>
								</td> 
								<td>
									<span v-if="entry.aircraft">{{entry.aircraft.model}}</span>
								</td> 
								<td>
									<span class="d-md-none text-muted">Entry Status: </span>{{entry.entry_status}}
									<span class="badge badge-danger" v-if="!entry.signed">Not Signed</span>
								</td>
								<td>
									<a v-if="entry.editcode" class="fa fa-edit" :href="'/entries/' + entry.editcode"></a>
								</td>
							</tr>
						</table>
					</div>


					<div v-if="helperEntries.length>0">
						<h3>Helpers</h3>

						<table class="table collapsable">
							<tr>
								<th>Name</th>
								<th>Type</th>
								<th>Status</th>
								<th></th>
							</tr>
							<tr v-for="entry in helperEntries">
								<td>
									<h3 class="d-md-none mt-4">{{entry.first_name}} {{entry.last_name}}</h3>
									<span class="d-none d-md-block">{{entry.first_name}} {{entry.last_name}}</span>
								</td>
								<td>
									{{entry.entry_type}}
								</td>
								<td>
									{{entry.entry_status}}
									<span class="badge badge-danger" v-if="!entry.signed">Not Signed</span>
								</td>
								<td>
									<a v-if="entry.editcode" class="fa fa-edit" :href="'/entries/' + entry.editcode"></a>
								</td>
							</tr>
						</table>
					</div>
					

				</div>
			</div>


			<div class="card mt-4" v-if="catering">
				<div class="card-header">
					<div class="float-right">
						<span class="ml-2" v-if="event.catering_breakfasts"><span class="text-muted">Breakfasts all: </span>{{breakfastsAllCount}} <span class="text-muted">some:</span> {{breakfastsSomeCount}}</span>
						<span class="ml-2" v-if="event.catering_lunches"><span class="text-muted">Lunches all: </span>{{lunchesAllCount}} <span class="text-muted">some:</span> {{lunchesSomeCount}}</span>
						<span class="ml-2" v-if="event.catering_dinners"><span class="text-muted">Dinners all: </span>{{dinnersAllCount}} <span class="text-muted">some:</span> {{dinnersSomeCount}}</span>
						<span class="ml-2" v-if="event.catering_final_dinner"><span class="text-muted">Final Dinner: </span>{{finalDinnerCount}}</span>
					</div>
					<h4>Catering</h4>
				</div>
				<div class="card-body">
					
					<table class="table collapsable">
						<tr>
							<th>Name</th>
							<th v-if="event.catering_breakfasts">Brkfsts</th>
							<th v-if="event.catering_lunches">Lunches</th>
							<th v-if="event.catering_dinners">Dinners</th>
							<th v-if="event.catering_final_dinner">Final Dinner</th>
						</tr>
						<tr v-for="entry in entries">
							<td>
								<h3 class="d-md-none mt-4">{{entry.first_name}} {{entry.last_name}}</h3>
								<span class="d-none d-md-block">{{entry.first_name}} {{entry.last_name}}</span>
							</td>
							<td v-if="event.catering_breakfasts">
								<span class="d-md-none text-muted">Breakfasts: </span><span class="fa" :class="showMeal(entry.catering_breakfasts)"></span> {{entry.catering_breakfasts}}
							</td>
							<td v-if="event.catering_lunches">
								<span class="d-md-none text-muted">Lunches: </span><span class="fa" :class="showMeal(entry.catering_lunches)"></span> {{entry.catering_lunches}}
							</td>
							<td v-if="event.catering_dinners">
								<span class="d-md-none text-muted">Dinners: </span><span class="fa" :class="showMeal(entry.catering_dinners)"></span> {{entry.catering_dinners}}
							</td>
							<td v-if="event.catering_final_dinner">
								<span class="d-md-none text-muted">Final Dinner: </span>{{entry.catering_final_dinner}}
							</td>
						</tr>
					</table>
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
			calendar: null,
			selectedClasses: [],
			entries: [],
			contestAdmin: false,
		}
	},
	props: ['orgId', 'eventId'],
	created: function() {
		this.load();
	},
	mounted: function() {
		this.calendar = this.$refs.calendar;
		if (window.Laravel.contestAdmin) this.contestAdmin = true;
	},
	computed: {
		allEmails: function() {
			var emails = [];
			this.entries.forEach(function(entry) {
				if (entry.email) emails.push(entry.email);
			});
			return emails.join(', ');
		},
		allMobiles: function() {
			var mobiles = [];
			this.entries.forEach(function(entry) {
				if (entry.mobile) mobiles.push(entry.mobile);
			});
			return mobiles.join(', ');
		},
		catering: function() {
			return this.event.catering_breakfasts || this.event.catering_lunches || this.event.catering_dinners || this.event.catering_final_dinner;
		},
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
		},
		entriesCount: function() {
			return this.pilotEntries.length;
		},
		pilotEntries: function() {
			return this.entries.filter((obj) => obj.entry_type=='pilot');
		},
		secondPilotEntries: function() {
			return this.entries.filter((obj) => obj.entry_type=='2nd_pilot');
		},
		helperEntries: function() {
			return this.entries.filter((obj) => obj.entry_type=='helper' ||obj.entry_type=='towpilot');
		},
		breakfastsAllCount: function() {
			return this.entries.filter((obj) => obj.catering_breakfasts=='all').length;
		},
		breakfastsSomeCount: function() {
			return this.entries.filter((obj) => obj.catering_breakfasts=='some').length;
		},
		lunchesAllCount: function() {
			return this.entries.filter((obj) => obj.catering_lunches=='all').length;
		},
		lunchesSomeCount: function() {
			return this.entries.filter((obj) => obj.catering_lunches=='some').length;
		},
		dinnersAllCount: function() {
			return this.entries.filter((obj) => obj.catering_dinners=='all').length;
		},
		dinnersSomeCount: function() {
			return this.entries.filter((obj) => obj.catering_dinners=='some').length;
		},
		finalDinnerCount: function() {
			var counter=0;
			this.entries.forEach(function(item, index) {
				counter = counter + item.catering_final_dinner;
			});
			return counter;
		}

	},
	methods: {
		classPilotCount: function(class_id) {
			console.log(class_id);
			return this.pilotEntries.filter((obj) => obj.class_id==class_id).length;
		},
		showMeal: function(item) {
			switch(item) {
				case 'none': return 'fa-times'; break;
				case 'some': return 'fa-check'; break;
				case 'all': return 'fa-check-double'; break;
			}
		},
		loadEntries: function() {
			var that = this;
			window.axios.get('/api/v1/entries?cancelled=false&event_id=' + this.event.id).then(function (response) {
				that.entries = response.data.data;
			});
		},
		loadSelectedClasses: function() {
			var that = this;
			window.axios.get('/api/v1/events/' + this.event.id + '/classes').then(function (response) {
				that.selectedClasses = response.data.data;
			});
		},
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
				

				// once we have the event details, load the extra stuff
				that.loadSelectedClasses();
				that.loadEntries();
			});
		}
	}
}
</script>
