<template>
	<div>

		<h1>Global Settings</h1>

		<form v-if="status=='loaded'">
			<div class="form-group">
				<label for="">New member notification emails: (comma separate for multiple)</label>
				<input type="text" class="form-control" id="email_new_member_to" v-model="email_new_member_to"><br>
				Usually the GNZ secratary
			</div>
			<input type="text" class="btn btn-primary" value="Save" v-on:click="save()">
		</form>

		<div v-show="status!='loaded'"><span class=" fas fa-sync fa-spin"></span> Loading...</div>


	</div>
</template>


<script>
	import common from '../../mixins.js';

	export default {
		mixins: [common],
		props: [],
		data() {
			return {
				settings: [],
				email_new_member_to: '',
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
						"email_new_member_to" : {"value":this.email_new_member_to , protected:false}
					}
				}
				window.axios.post('/api/v1/orgs/' + this.org.id + '/settings', data).then(function (response) {
						messages.$emit('success', 'Settings Saved');
					});
			},
			load: function() {
				var that = this;
				this.status='loading';
				window.axios.get('/api/v1/orgs/' + this.org.id + '/settings').then(function (response) {
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