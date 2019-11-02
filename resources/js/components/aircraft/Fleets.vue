<template>
<div>
	<h1><a href="/aircraft">Aircraft</a> &raquo; Fleets</h1>

	<add-fleet-panel v-if="Laravel.clubMember==true || Laravel.admin" :org-id="orgId" :show="showAddPanel" @closeModal="showAddPanel=false" @fleetAdded="fleetAdded"></add-fleet-panel>

	<div class="btn-group" v-if="Laravel.clubMember || Laravel.admin">
		<button class="btn btn-outline-dark btn-sm ml-auto" v-on:click="showAddPanel=true"><span class="fa fa-plus"></span> Add Fleet</button>
	</div>

	<table class="table table-striped">
		<tr>
			<th>Name</th>
			<th>Organisation</th>
		</tr>
		<tr v-for="fleet in fleets">
			<td><a :href="'/fleets/' + fleet.slug + '/edit'">{{fleet.name}}</a></td>
			<td>{{fleet.org.name}}</td>
		</tr>
	</table>

</div>
</template>


<script>
	import common from '../../mixins.js';

	export default {
		mixins: [common],
		props: ['orgId', 'orgName'],
		data() {
			return {
				fleets: [],
				showAddPanel: false,
				showEdit: false,
				showAdmin: false
			}
		},
		mounted() {
			this.load();
			if (window.Laravel.allowsEdit==true) this.showEdit=true;
			if (window.Laravel.admin==true) this.showAdmin=true;
		},
		methods: {
			load: function() {
				var that=this;
				window.axios.get('/api/v1/fleets').then(function (response) {
					that.fleets = response.data.data;
				});
			},
			fleetAdded: function(event) {
				this.load();
			}
		}
	}
</script>