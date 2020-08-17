<template>
	<div>

		<h1>Import Features</h1>

		<p>
			<input type="submit" class="btn btn-outline-dark" value="Import Flarm Codes" v-on:click="importFlarm" :disabled="importingFlarm">
			<span v-show="importingFlarm"><i  class="fas fa-cog fa-spin"></i> Please Wait</span>
		</p>
		
		
		<p>
			<input type="submit" class="btn btn-outline-dark" value="Import Aircraft from CAA" v-on:click="importCAA" :disabled="importingAircraft">
			<span v-show="importingAircraft"><i  class="fas fa-cog fa-spin"></i> Please Wait</span>
		</p>
		
		
		<p>
			<input type="submit" class="btn btn-outline-dark" value="Send Email Address Changes to Laurie" v-on:click="emailAddressChange" :disabled="emailingAddressChanges">
			<span v-show="emailingAddressChanges"><i  class="fas fa-cog fa-spin"></i> Please Wait</span>
		</p>

	</div>
</template>



<script>
	import common from '../../mixins.js';

	export default {
		mixins: [common],
		props: [],
		data() {
			return {
				importingFlarm: false,
				importingAircraft: false,
				emailingAddressChanges: false
			}
		},
		mounted() {
		},
		computed: {
		},
		methods: {
			importFlarm: function() {
				var that = this;
				this.importingFlarm = true;
				window.axios.post('/api/v1/admin/import-flarm').then(function (response) {
					messages.$emit('success', response.data.data);
					that.importingFlarm = false;
				}).catch(
				function (error) {
					that.importingFlarm = false;
					var errors = Object.entries(error.response.data.errors);
					for (const [name, error] of errors) {
						messages.$emit('error', `${error}`);
					}
				});
			},
			importCAA: function() {
				var that = this;
				this.importingAircraft = true;
				window.axios.post('/api/v1/admin/import-aircraft-from-caa').then(function (response) {
					messages.$emit('success', response.data.data);
					that.importingAircraft = false;
				}).catch(
				function (error) {
					that.importingAircraft = false;
					var errors = Object.entries(error.response.data.errors);
					for (const [name, error] of errors) {
						messages.$emit('error', `${error}`);
					}
				});
			},
			emailAddressChange: function() {
				var that = this;
				this.emailingAddressChanges = true;
				window.axios.post('/api/v1/admin/email-address-changes').then(function (response) {
					that.emailingAddressChanges = false;
					messages.$emit('success', response.data.data);
				}).catch(
				function (error) {
					that.emailingAddressChanges = false;
					var errors = Object.entries(error.response.data.errors);
					for (const [name, error] of errors) {
						messages.$emit('error', `${error}`);
					}
				});
			},
		}
	}
</script>