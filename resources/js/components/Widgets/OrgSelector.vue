<template>
<div>
	<select name="org" v-model="selectedOrg" class="form-control custom-select custom-select-sm" :disabled="disabled ? '' : disabled">
		<option v-bind:value="null">All Clubs</option>
		<option v-for="org in orgs" v-bind:value="org">{{org.name}}</option>
	</select>
</div>
</template>

<script>
export default {
	data: function() {
		return {
			orgs: [],
			selectedOrg: null
		}
	},
	props: ['orgId', 'disabled'],
	created: function() {
		this.load();
	},
	watch: {
		selectedOrg: function(value) {
			return this.$emit('orgSelected', value);
		}
	},
	methods: {
		load: function() {
			var that = this;
			window.axios.get('/api/v1/orgs/').then(function (response) {
				that.orgs = response.data.data;
				if (that.orgId!='') {
					that.selectedOrg = that.orgs.find(function(org) {
						return org.id == that.orgId;
					});
				};
			});
		}
	}
}
</script>
