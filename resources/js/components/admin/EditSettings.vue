<template>
	<div>

		<h1>{{orgName}} Settings</h1>

		<form v-if="status=='loaded'">
			<div class="form-group">
				<label for="">New member signup</label>
				<div class="form-check form-check-inline">
					<label class="ml-4 form-check-label" for="allow_signup_yes">
						<input type="radio" name="allow_signup" class="form-check-input" id="allow_signup_yes" :value="1" v-model="allow_signup"> On
					</label>
					<label class="ml-4 form-check-label" for="allow_signup_no">
						<input type="radio" name="allow_signup" class="form-check-input" id="allow_signup_no" :value="0" v-model="allow_signup"> Off
					</label>
				</div>
			</div>
			<div class="form-group">
				<label for="">New member emails go to</label>
				<input type="text" class="form-control" id="email_new_member_to" v-model="email_new_member_to">
			</div>
			<input type="text" class="btn btn-primary" value="Save" v-on:click="save()">
		</form>

		<div v-show="status!='loaded'"><span class=" fas fa-sync fa-spin"></span> Loading...</div>

		<hr class="mb-4">

		<h2>Other Setup</h2>

		<a href="/club-admin/member-types" class="btn btn btn-outline-dark">Edit Member Types</a>


	</div>
</template>


<script>
	import common from '../../mixins.js';

	export default {
		mixins: [common],
		props: ['orgId', 'orgName'],
		data() {
			return {
				settings: [],
				email_new_member_to: '',
				allow_signup: '0',
				status: 'notloaded'
			}
		},
		mounted() {
			this.load();
		},
		computed: {
		},
		methods: {
			save: function() {
				var that = this;
				var data = {
					"settings": {
						"email_new_member_to" : {"value":this.email_new_member_to , protected:false},
						"allow_signup" :  {"value":this.allow_signup , protected:false}
					}
				}
				window.axios.post('/api/v1/orgs/' + this.orgId + '/settings', data).then(function (response) {
						messages.$emit('success', 'Settings Saved');
					});
			},
			load: function() {
				var that = this;
				this.status='loading';
				window.axios.get('/api/v1/orgs/' + this.orgId + '/settings').then(function (response) {
						that.settings = response.data.data;

						// unpack the data if it exists
						if (typeof(that.settings.email_new_member_to)!='undefined') that.email_new_member_to = that.settings.email_new_member_to.value;
						if (typeof(that.settings.allow_signup)!='undefined')  that.allow_signup = that.settings.allow_signup.value;

						that.status='loaded';
					});
			}
		}
	}
</script>