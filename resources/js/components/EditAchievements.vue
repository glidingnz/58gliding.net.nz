

<template>
	<div>

		<table class="table results-table table-striped">
			<tr>
				<td>
					<select class="form-control input-sm" name="club" v-model="newBadge.badge_id">
						<option v-bind:value="null">Select Badge...</option>
						<option v-for="badge in badges" v-bind:value="badge.id">{{badge.name}}</option>
					</select>
				</td>
				<td>
					<input type="text" v-model="newBadge.badge_number" class="form-control" size="5" placeholder="Badge Number (if relevant)">
				</td>
				<td>
					<input type="text" v-model="newBadge.date_awarded" class="form-control" size="5" placeholder="YYYY-MM-DD">
				</td>
				<td>
					<button v-on:click="addAchievement()" class="btn btn-primary">Add Badge</button>
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
				<td>{{result.awarded_date}}</td>
				<td>{{result.badge.type}}</td>
				<td><button v-on:click="deleteAchievement(result.id)" class="btn btn-default btn-sm"><i class="fa fa-times"></i></button></td>
			</tr>
		</table>

		<div v-if="results.length==0">
			None yet!
		</div>

	</div>
</template>


<script>
	import common from '../mixins.js';

	export default {
		mixins: [common],
		props: ['memberId'],
		data() {
			return {
				results: [],
				badges: [],
				showEdit: false,
				newBadge: {
					id:0,
					badge_number:null,
					date_awarded: null,
					member_id: this.memberId
				}
			}
		},
		ready() {
			this.load();
			this.loadBadges();
		},
		methods: {
			load: function() {
				this.$http.get('/api/v1/achievements?member_id=' + this.memberId).then(function (response) {
					var responseJson = response.json();
					this.results = responseJson.data;
				});
			},
			loadBadges: function() {
				this.$http.get('/api/v1/badges?exclude=fai').then(function (response) {
					var responseJson = response.json();
					this.badges = responseJson.data;
				});
			},
			addAchievement: function() {
				this.$http.post('/api/v1/achievements', this.newBadge).then(function (response) {
					this.load();
				});
			},
			deleteAchievement: function(achievement_id) {
				var data = {};
				//data._method = 'DELETE';

				this.$http.delete('/api/v1/achievements/' + achievement_id, data).then(function (response) {
					this.load();
				});
			}
		}
	}
</script>
