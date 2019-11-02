<template>
<div>
	<h1><a href="/aircraft">Aircraft</a> &raquo; Gaggles</h1>

	<add-gaggle-panel v-if="Laravel.clubMember==true || Laravel.admin" :org-id="orgId" :show="showAddPanel" @closeModal="showAddPanel=false" @gaggleAdded="gaggleAdded"></add-gaggle-panel>

	<div class="btn-group" v-if="Laravel.clubMember || Laravel.admin">
		<button class="btn btn-outline-dark btn-sm ml-auto" v-on:click="showAddPanel=true"><span class="fa fa-plus"></span> Add Gaggle</button>
	</div>

	<table class="table table-striped">
		<tr>
			<th>Name</th>
			<th>Date Added</th>
		</tr>
		<tr v-for="gaggle in gaggles">
			<td>{{gaggle.name}}</td>
			<td>{{formatDate(gaggle.created_at)}}</td>
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
				gaggles: [],
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
				window.axios.get('/api/v1/gaggles', {params: this.state}).then(function (response) {
					that.gaggles = response.data.data;
				});
			},
			gaggleAdded: function(event) {
				this.load();
				//window.location.href = "/events/" + event.slug  + "/edit";
			}
		}
	}
</script>