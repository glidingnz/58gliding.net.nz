<style>
.fa-exclamation-circle {
	color: #A00;
}
</style>

<template>
	<div>
		<div v-if="allowsEdit">

			<button class="float-right btn btn-outline-dark" v-on:click="addRating = !addRating">
				<span v-show="addRating">Cancel</span>
				<span v-show="!addRating"><span class="fa fa-plus mr-2"></span> Add Rating</span>
			</button>

			<div v-show="addRating">
			<h2>Add Rating</h2>
				<div class="form form-inline form-group">
					<select class="form-control mr-2" name="add_rating" id="add_rating" @change="selectRating($event.target.selectedIndex)" v-model="newRating.rating_id">
						<option v-bind:value="null">Select rating...</option>
						<option v-for="rating in ratings" v-bind:value="rating.id">{{rating.name}}</option>
					</select>
					Granted Date (YYYY-MM-DD):
					<!-- <input type="text"  value="" v-model="newRating.awarded"> -->
					<div class="ml-2 mr-2">
						<v-date-picker v-model="newRating.awarded" :locale="{ id: 'nz', firstDayOfWeek: 2, masks: { weekdays: 'WW', L: 'DD/MM/YYYY' } }"></v-date-picker>
					</div>
					Expires:
					<select class="form-control ml-2 " name="expires" id="expires" v-model="presetExpires">
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
				
				<div class="form-inline form-group">
					<div class="mr-4">
						Authorised by: 
						<input class="form-control ml-2" type="search" v-on:keyup="onSearch(searchText)" v-model="searchText" placeholder="Search for member...">
						<select v-show="peopleSearchResults.length!=0" class="form-control ml-2" name="peopleSearch" id="peopleSearch" v-model="authorising_member_id">
							<option value="0">Select...</option>
							<option v-for="person in peopleSearchResults" v-bind:value="person.id">{{person.first_name}} {{person.last_name}} {{person.nzga_number}} {{person.club}} {{person.city}}</option>
						</select>
						<span v-show="peopleSearchResults.length==0" class="ml-2">No members found</span>
						<span class="ml-2">(e.g. CFI)</span>
					</div>
				</div>
				
				<div class="form-inline form-group">
					<label class="mr-1">Upload Files:</label>
					<div class="custom-file col-3 mr-2">
						<input type="file" class="custom-file-input" id="file" ref="file" multiple  v-on:change="onChangeFileUpload()" />
						<label class="custom-file-label" for="file">Choose files</label>
					</div>
					
					<span v-if="!files || !files.length">No files selected</span>
					<ul v-else>
						<li v-for="(file, key) in files" :key="file.name">{{file.name}}</li>
					</ul>
				</div>

				<a class="btn btn-outline-dark ml-2" v-on:click="insert()">Add Rating 
					<span class="fa fa-spinner fa-pulse" v-show="uploading"></span>
				</a>

				<hr>
			</div>


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
					<td><a v-bind:href="'/members/' + memberId + '/ratings/' + result.id + '/'">{{result.name}}</a></td>
					<td>{{formatDate(result.awarded)}}</td>
					<td>
						<span v-show="result.expires!='0000-00-00' && result.expires!=null">
							<span class="fa fa-exclamation-triangle" v-if="ratingNearlyExpired(result.expires)"></span>
								<span class="fa fa-exclamation-circle" v-if="ratingExpired(result.expires)"></span>
							{{result.timeToExpire}} ({{formatDate(result.expires)}})
						</span>
					</td>
					<td><a v-bind:href="'/members/' + result.authorising_member_id + '/'">{{result.auth_firstname}} {{result.auth_lastname}} {{result.nzga_number}}</a></td>
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
import moment from 'moment';
import VCalendar from 'v-calendar';
Vue.prototype.$moment = moment;

export default {
	mixins: [common],
	props: ['memberId', 'allowsEdit'],
	data() {
		return {
			ratings: [],
			memberRatings: [],
			loaded: false,
			loading: false,
			presetExpires: 'never',
			test: '',
			newRating: {}, // the object to store the new rating details
			peopleSearchResults: [],
			searchText: '',
			authorising_member_id: null,
			files: null,
			addRating: false,
			uploading: false
		}
	},
	mounted() {
		this.newRating.awarded = new Date();
		this.load();
	},
	methods: {
		load: function() {
			var that = this;
			window.axios.get('/api/v1/ratings').then(function (response) {
				that.loaded = true;
				that.ratings = response.data.data;
				that.newRating.rating_id = null;
			});

			this.getMemberRatings();
		},
		insert: function() {
			var that = this;

			var formData = new FormData();

			if (this.newRating.rating_id) formData.append('rating_id', this.newRating.rating_id);
			if (this.memberId) formData.append('member_id', this.memberId);
			if (this.newRating.awarded) formData.append('awarded', this.$moment(this.newRating.awarded).format('YYYY-MM-DD'));
			if (this.newRating.notes) formData.append('notes', this.newRating.notes);
			if (this.presetExpires) formData.append('expires', this.presetExpires);
			if (this.authorising_member_id) formData.append('authorising_member_id', this.authorising_member_id);
			if (this.files) {
				for (var i=0; i<this.files.length; i++) {
					formData.append('files[' + i + ']', this.files[i]);
				}
			}

			if (!formData.has('rating_id')) {
				messages.$emit('error', 'A rating is required');
				return false;
			}

			that.uploading = true;

			// create the new rating
			window.axios.post('/api/v1/members/' + this.memberId + '/ratings', 
				formData,
				{
					headers: {
						'Content-Type': 'multipart/form-data'
					}
				}).then(function (response) {
					messages.$emit('success', 'Rating Added');
					that.getMemberRatings();
					that.uploading = false;
					that.files = null;
					that.addRating = false;
				})
				.catch(function (error) {
					// handle error
					messages.$emit('error', 'Rating not added. Error given: ' + error.response.data.error);
					that.uploading = false;
					console.log(error);
					console.log(error.response.data.error);
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
			window.axios.get('/api/v1/members', {params: {"search":search}}).then(function (response) {
				vm.peopleSearchResults = response.data.data;

				// select the first item in the list if possible
				if (response.data.data.length==0) {
					vm.authorising_member_id = null;
				} else {
					vm.authorising_member_id = response.data.data[0].id;
				}
			});
		}, 250),
		getMemberRatings() {
			var that = this;
			window.axios.get('/api/v1/members/' + this.memberId + '/ratings').then(function (response) {
				that.memberRatings = response.data.data;

				for (var i=0; i<that.memberRatings.length; i++) {
					that.memberRatings[i].timeToExpire = moment(that.memberRatings[i].expires).fromNow();
				}
			});
		},
		onChangeFileUpload: function() {
			this.files = this.$refs.file.files;
		}
	}
}
</script>
