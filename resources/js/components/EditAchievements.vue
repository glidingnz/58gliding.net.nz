<template>
	<div>

		<p>Note FAI achievements are added by the awards officer. All the others here you can add yourself.</p>

		<table class="table results-table table-striped">
			<tr>
				<td>
					<select class="form-control input-sm" name="club" v-model="newBadge.badge_id" @change="getMaxNumber($event)">
						<option v-bind:value="null">Select Badge...</option>
						<option v-for="badge in badges" v-bind:value="badge.id">{{badge.name}}</option>
					</select>
				</td>
				<td>
					<input type="text" v-model="newBadge.badge_number" class="form-control" size="5" placeholder="Badge Number (if relevant)">
					<span v-if="maxNumber">Previous: {{maxNumber}}</span>
				</td>
				<td>
					<v-date-picker v-model="new_awarded_date" :locale="{ id: 'nz', firstDayOfWeek: 2, masks: { weekdays: 'WW', L: 'DD/MM/YYYY' } }"></v-date-picker>
				</td>
				<td>
					<button v-on:click="addAchievement()" class="btn btn-primary">Add Achievement</button>
				</td>
				<td></td>
			</tr>
			<tr>
				<th>Name</th>
				<th>Badge Number</th>
				<th>Date Awarded</th>
				<th>Type</th>
				<th>Delete</th>
			</tr>
			<tr v-for="result in results">
				<td>{{result.badge.name}}</td>
				<td>{{result.badge_number}}</td>
				<td>
					<span v-if="result.awarded_date">{{formatDate(result.awarded_date)}}</span>
				</td>
				<td>{{result.badge.type}}</td>
				<td><button v-on:click="deleteAchievement(result.id)" class="btn btn-outline-dark btn-sm"><i class="fa fa-times"></i></button></td>
			</tr>
		</table>

		<div v-if="results.length==0">
			None yet!
		</div>

	</div>
</template>


<script>
	import common from '../mixins.js';
	import VCalendar from 'v-calendar';
	import moment from 'moment';

	export default {
		mixins: [common],
		props: ['memberId', 'allowsEdit'],
		data() {
			return {
				results: [],
				badges: [],
				showEdit: false,
				editAwards: false,
				new_awarded_date: null,
				maxNumber: false,
				newBadge: {
					id:null,
					badge_number:null,
					awarded_date: null,
					member_id: this.memberId
				}
			}
		},
		mounted() {
			this.new_awarded_date = new Date();
			if (window.Laravel.editAwards) this.editAwards=true;
			this.load();
			this.loadBadges();
		},
		methods: {
			load: function() {
				var that = this;

				window.axios.get('/api/v1/achievements?member_id=' + this.memberId).then(function (response) {
					that.results = response.data.data;
				});
			},
			loadBadges: function() {
				var that = this;
				var url = '/api/v1/badges';
				if (!this.editAwards) url += '?exclude=fai';
				window.axios.get(url).then(function (response) {
					that.badges = response.data.data;
				});
			},
			addAchievement: function() {
				var that = this;
				this.newBadge.awarded_date = this.$moment(this.new_awarded_date).format('YYYY-MM-DD');
				window.axios.post('/api/v1/achievements', this.newBadge).then(function (response) {
					messages.$emit('success', 'Achievement Added');
					that.load();
				})
				.catch(function (error) {
					// handle error
					messages.$emit('error', 'Achievement Not Added. Error: ' + error.response.data.error);
				});;
			},
			deleteAchievement: function(achievement_id) {
				var that = this;
				window.axios.delete('/api/v1/achievements/' + achievement_id).then(function (response) {
					messages.$emit('success', 'Achievement Deleted');
					that.load();
				})
				.catch(function (error) {
					// handle error
					messages.$emit('error', 'Achievement Not Deleted. Error: ' + error.response.data.error);
				});
			},
			getMaxNumber: function(event) {
				var that=this;
				window.axios.get('/api/v1/badges/' + this.newBadge.badge_id).then(function (response) {
					var maxNumber = response.data.data.max;
					if (maxNumber) {
						that.maxNumber = maxNumber;
					} else {
						that.maxNumber=false
					}

				});
			}
		}
	}
</script>
