<style>
.fa-exclamation-circle {
	color: #A00;
}
</style>

<template>
	<div>
		<div v-if="allowsEdit">
		</div>

		<table class="table" v-if="rating.rating">
			<tr>
				<td>Rating</td>
				<td>{{rating.rating.name}}</td>
			</tr>
			<tr>
				<td>Granted</td>
				<td>{{formatDate(rating.awarded)}}</td>
			</tr>
			<tr>
				<td>Expires</td>
				<td>
					<span v-show="rating.expires!='0000-00-00' && rating.expires!=null">
						<span class="fa fa-exclamation-triangle" v-if="ratingNearlyExpired(rating.expires)"></span>
							<span class="fa fa-exclamation-circle" v-if="ratingExpired(rating.expires)"></span>
						{{rating.timeToExpire}} ({{formatDate(rating.expires)}})
					</span>
				</td>
			</tr>
			<tr>
				<td>Authorised By</td>
				<td><a v-bind:href="'/members/' + rating.authorising_member_id + '/'">{{rating.auth_firstname}} {{rating.auth_lastname}} {{rating.nzga_number}}</a></td>
			</tr>
			<tr>
				<td>Added By</td>
				<td>{{rating.first_name}} {{rating.last_name}}</td>
			</tr>
			<tr>
				<td>Notes</td>
				<td>{{rating.notes}}</td>
			</tr>
			<tr>
				<td>Files</td>
				<td>

					<ul class="list-unstyled">
						<li v-for="upload in rating.uploads">
							<a :href="upload.folder + '/' + upload.filename"><span class="fa fa-file mr-2"></span>{{upload.filename}}</a>
							<button class="ml-4 btn btn-outline-dark btn-xs" v-on:click="deleteFile(upload)" v-if="allowsEdit">Delete</button>
						</li>
					</ul>

					
					<div class="form-inline form-group" v-if="allowsEdit">
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

					<a class="btn btn-outline-dark ml-2" v-on:click="uploadFiles()">Upload Files 
						<span class="fa fa-spinner fa-pulse" v-show="uploading"></span>
					</a>

				</td>
			</tr>
		</table>

		<div v-if="loading">
			Loading...
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
		props: ['ratingId', 'memberId', 'allowsEdit'],
		data() {
			return {
				rating: {},
				loading: false,
				files: null,
				uploading: false
			}
		},
		mounted() {
			this.load();
		},
		methods: {
			load: function() {
				var that = this;
				that.loading = true;
				window.axios.get('/api/v1/members/' + this.memberId + '/ratings/' + this.ratingId).then(function (response) {
					that.loading = false;
					that.rating = response.data.data;
					console.log(that.rating);
				});
			},
			deleteFile: function(upload) {
				var that = this;
				window.axios.delete('/api/v1/members/' + this.memberId + '/ratings/' + this.ratingId + '/upload/' + upload.id).then(function (response) {
					that.load();
				});
			},
			uploadFiles: function() {
				var that = this;

				var formData = new FormData();

				if (this.files) {
					for (var i=0; i<this.files.length; i++) {
						formData.append('files[' + i + ']', this.files[i]);
					}
				}

				that.uploading = true;

				// create the new rating
				window.axios.post('/api/v1/rating-member/'+ that.rating.id + '/upload', 
					formData,
					{
						headers: {
							'Content-Type': 'multipart/form-data'
						}
					}).then(function (response) {
						messages.$emit('success', 'Files Uploaded');
						that.files = null;
						that.load();
						that.uploading = false;
					})
					.catch(function (error) {
						// handle error
						messages.$emit('error', 'Files not uploaded. Error given: ' + error.response.data.error);
						that.uploading = false;
					});
			},
			onChangeFileUpload: function() {
				this.files = this.$refs.file.files;
			}

		}
	}
</script>
