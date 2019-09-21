<style>
	.edit-roster-table .no-wrap {
		white-space: nowrap !important;
	}

	.add-custom-modal {
		width: 100%;
		height: 100%;
		position: fixed;
		top: 0;
		left: 0;
		background-color: rgba(0,0,0,0.7);
	}
	.add-custom-modal .inner {
		width: 80%;
		max-width: 500px;
		height: 50%;
		margin: 10% auto 0 auto;
		background-color: #EEE;
		padding: 20px;
		-webkit-box-shadow: 0px 6px 15px 7px rgba(0,0,0,0.27); 
		box-shadow: 0px 6px 15px 7px rgba(0,0,0,0.27);
		border-radius: 10px;
	}
</style>

<template>
	<div>

		<calendar-nav active="edit-roster" title="Edit Roster"></calendar-nav>


		<div class="add-custom-modal" v-show="showCustomModal" v-on:click="closeCustomModal()" @keyup.esc="closeCustomModal()" tabindex="0">
			<div class="inner" v-on:click.stop="">
				<h2>Add Extra Duty</h2>
				<select class="form-control" >
					<option :value="customDuty.id" v-for="customDuty in duties">{{customDuty.name}}</option>
				</select>
				<button v-on:click="closeCustomModal()" class="btn btn-outline-dark">Cancel</button>
			</div>
		</div>

		<table class="edit-roster-table table table-striped table-sm collapsable">
			<tr>
				<th>Date</th>
				<th>Available</th>
				<template v-for="duty in defaultDuties">
					<th>{{duty.name}}</th>
				</template>
				<th>Extras</th>
			</tr>
			<template v-for="(day, dayIndex) in results" >
				<tr >
					<td>
						<b>{{renderDate(day.day_date)}}</b>
						<p v-if="day.description" v-html="renderDescription(day.description)"></p>
					</td>
					<td>
						<span v-if="day.winching"><span class="fa fa-check"></span> Winching</span>
						<span v-if="day.towing"><span class="fa fa-check"></span> Towing</span>
						<span v-if="day.training"><span class="fa fa-check"></span> Training</span>
						<span v-if="day.trialflights"><span class="fa fa-check"></span> Trial Flights</span>
					</td>
					<template v-for="(duty, dutyIndex) in defaultDuties">
						<td class="no-wrap">

							<!-- list existing roster items -->
							<roster-edit-item v-for="(rosterItem, rosterIndex) in getDaysRosters(day.id, duty.id)" v-bind:key="rosterItem.id" :roster="rosterItem" :member="rosterItem.member" v-on:delete="deleteEvent(rosterItem)"></roster-edit-item>

							<!-- allow adding a new one -->
							<roster-add-item v-on:add="addEvent" :orgId="orgId" :day="day" :duty="duty" ></roster-add-item>

						</td>
					</template>
					<td>
						<button class="btn fa fa-plus-circle" v-on:click="openCustomModal()"></button>
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
				duties: [],
				results: [],
				roster: [],
				showCustomModal: false
			}
		},
		mounted() {
			this.loadDays();
			this.loadDuties();
			this.loadRoster();
		},
		computed: {
			defaultDuties: function() {
				return this.duties.filter(function(duty) {
					return !duty.custom;
				});
			},
			customDuties: function() {
				return this.duties.filter(function(duty) {
					return duty.custom;
				});
			}
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
			openCustomModal: function(day) {
				this.showCustomModal = true;
			},
			closeCustomModal: function() {
				this.showCustomModal = false;
			},
			loadDuties: function() {
				var that = this;
				window.axios.get('/api/duties/?org_id=' + this.orgId).then(function (response) {
					that.duties = response.data.data;
				});
			},
			loadRoster: function() {
				var that = this;
				window.axios.get('/api/roster/?org_id=' + this.orgId + '&start_date=' + this.$moment().format('YYYY-MM-DD')).then(function (response) {
					console.log('roster loaded');
					that.roster = response.data.data;
				});
			},
			renderDate: function(date) {
				return this.$moment(date).format('ddd, MMM Do YY');
			},
			renderDescription: function(description) {
				if (description==null) return null;
				return description.replace(/(?:\r\n|\r|\n)/g, '<br>');
			},
			getDaysRosters: function(day_id, duty_id) {
				var result = this.roster.filter(function(roster) {
					if (roster.day_id==day_id && roster.duty_id==duty_id) return true;
				});
				return result;
			},
			deleteEvent: function(rosterItem) {
				console.log(rosterItem);

				this.roster.splice(this.roster.indexOf(rosterItem), 1);
			},
			addEvent: function(newRoster) {
				this.roster.push(newRoster);
			}
		}

	}
</script>