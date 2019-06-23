<style>
.fa-exclamation-circle {
	color: #A00;
}
</style>

<template>
	<div>
		<div v-if="allowsEdit">
			<h2>Add Rating</h2>
			<div class="form form-inline form-group">
				<select class="form-control" name="add_rating" id="add_rating" @change="selectRating($event.target.selectedIndex)" v-model="newRating.rating_id">
					<option v-bind:value="null">Select rating...</option>
					<option v-for="rating in ratings" v-bind:value="rating.id">{{rating.name}}</option>
				</select>
				Granted Date (YYYY-MM-DD):
				<input type="text" class="form-control" value="" v-model="newRating.awarded">
				Expires:
				<select class="form-control" name="expires" id="expires" v-model="presetExpires">
					<option value="never">Never</option>
					<option value="12">1 Year</option>
					<option value="24">2 Years</option>
					<option value="60">5 Years</option>
					<option value="custom">Custom:</option>
				</select>
				
				<span v-show="presetExpires=='custom'">
					<input  type="text" class="form-control" v-model="newRating.expires" size="5"> Months
				</span>

			</div>

			<div class="form-group">
				Notes <textarea class="form-control" name="notes" id="" cols="30" rows="2" v-model="newRating.notes"></textarea>
			</div>
			
			<div class="form form-inline form-group">
				Authorised by 
				<input class="form-control" type="search" v-on:keyup="onSearch(searchText)" v-model="searchText" placeholder="Search...">
				<select v-show="peopleSearchResults.length!=0" class="form-control" name="peopleSearch" id="peopleSearch" v-model="authorising_member_id">
					<option value="0">Select...</option>
					<option v-for="person in peopleSearchResults" v-bind:value="person.id">{{person.first_name}} {{person.last_name}} {{person.nzga_number}} {{person.club}} {{person.city}}</option>
				</select>
				<span v-show="peopleSearchResults.length==0">No members found</span>


				<a class="btn btn-default" v-on:click="insert()" class="pull-right">Add Rating</a>
			</div>


			<hr>
		</div>

		<h2 v-if="allowsEdit">Ratings</h2>

		<div class="ratings">
			<table class="table table-striped">
				<tr>
					<th>Rating</th>
					<th>Granted</th>
					<th>Expires</th>
					<th>Authorised By</th>
					<th>Added By</th>
					<th>Notes</th>
				</tr>
				<tr v-for="result in memberRatings" v-bind:class="[ 
					ratingNearlyExpired(result.expires) ? 'warning' : '',
					ratingExpired(result.expires) ? 'danger' : ''
					]"> 
					<td>{{result.name}}</td>
					<td>{{result.awarded}}</td>
					<td>
						{{result.expires}}
						<span v-show="result.expires!='0000-00-00' && result.expires!=null">
							<span class="fa fa-exclamation-triangle" v-if="ratingNearlyExpired(result.expires)"></span>
								<span class="fa fa-exclamation-circle" v-if="ratingExpired(result.expires)"></span>
							{{result.timeToExpire}} ({{result.expires}})
						</span>
					</td>
					<td><a href="/members/{{result.authorising_member_id}}/">{{result.auth_firstname}} {{result.auth_lastname}} {{result.nzga_number}}</a></td>
					<td>{{result.first_name}} {{result.last_name}}</td>
					<td>{{result.notes}}</td>
				</tr>
				<tr v-if="memberRatings.length==0">
					<td colspan="6">No existing ratings</td>
				</tr>
			</table>
		</div>

		<div v-if="loading">
			Loading...
		</div>

		<div v-if="ratings.length==0 && !loading">
			No Ratings Yet
		</div>


	</div>
</template>


<script>
	import common from '../mixins.js';
	import timeago from 'timeago.js';

	export default {
		mixins: [common],
		props: ['memberId', 'allowsEdit'],
		data() {
			return {
				ratings: [],
				memberRatings: [],
				loaded: false,
				presetExpires: 'never',
				test: '',
				newRating: {}, // the object to store the new rating details
				peopleSearchResults: [],
				searchText: '',
				authorising_member_id: 0
			}
		},
		ready() {
			this.newRating.awarded = new Date().toJSON().slice(0, 10);
			this.load();
		},
		methods: {
			load: function() {

				this.$http.get('/api/v1/ratings').then(function (response) {
					this.loaded = true;
					var responseJson = response.json();
					this.ratings = responseJson.data;
				});

				this.getMemberRatings();
			},
			insert: function() {

				this.newRating.member_id = this.memberId;
				this.newRating.expires = this.presetExpires;
				this.newRating.authorising_member_id = this.authorising_member_id;

				if (this.newRating.authorising_member_id==null) {
					messages.$emit('error', 'An authorising person is required');
					return false;
				}
				if (this.newRating.rating_id==null) {
					messages.$emit('error', 'A rating is required');
					return false;
				}

				// create the new rating
				this.$http.post('/api/v1/members/' + this.memberId + '/ratings', this.newRating).then(function (response) {
					var responseJson = response.json();
					this.getMemberRatings();
				});
			},
			selectRating: function(ratingKey)
			{
				if (ratingKey==0) return false;

				//this.newRating.expires = this.ratings[ratingKey].default_expires;
				Vue.set(this.newRating, 'expires', this.ratings[ratingKey-1].default_expires);

				switch (this.ratings[ratingKey-1].default_expires)
				{
					case 12:
					case 24:
					case 60:
						this.presetExpires = this.ratings[ratingKey-1].default_expires;
						break;
					case null:
						this.presetExpires = 'never';
						break;
				}
			},
			onSearch(search)
			{
				if (search=='') {
					this.peopleSearchResults=[];
					return true;
				}
				this.getPeople(search, this);
			},
			getPeople: _.debounce((search, vm) => {
				vm.$http.get('/api/v1/members', {params: {"search":search}}).then(function (response) {
					var responseJson = response.json();
					vm.peopleSearchResults = responseJson.data;
					if (responseJson.data.length==0) {
						vm.authorising_member_id = null;
					} else {
						vm.authorising_member_id = responseJson.data[0].id;
					}
				});
			}, 250),
			getMemberRatings()
			{
				var that = this;
				this.$http.get('/api/v1/members/' + this.memberId + '/ratings').then(function (response) {
					var responseJson = response.json();
					that.memberRatings = responseJson.data;

					var timeagoInstance = timeago();
					for (var i=0; i<that.memberRatings.length; i++) {
						that.memberRatings[i].timeToExpire = timeagoInstance.format(that.memberRatings[i].expires);
					}
				});
			}
		}
	}
</script>
