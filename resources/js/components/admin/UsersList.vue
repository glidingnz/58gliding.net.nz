<template>
<div>
	<div class="btn-group pull-right " role="group">
		<input type="text" class="form-control" v-model="q" v-on:keyup="search()" size="30" placeholder="Search">
	</div>

	<div class="btn-group" role="group">
		<button type="button" class="btn btn-outline-dark btn-sm" v-on:click="previous()">&lt;</button>
		<button type="button" class="btn btn-outline-dark btn-sm disabled">Page {{ page }} of {{ last_page }}</button>
		<button type="button" class="btn btn-outline-dark btn-sm" v-on:click="next()">Next &gt;</button>
	</div>


	<table class="table table-striped results-table top-margin">
		<tr>
			<th class="clickable" v-on:click="order('email')">Email</th>
			<th class="clickable" v-on:click="order('first_name')">First</th>
			<th class="clickable" v-on:click="order('last_name')">Last</th>
			<th class="clickable center" v-on:click="order('activated')">Active</th>
			<th class="clickable center" v-on:click="order('gnz_active')">GNZ ID Validated</th>
			<th class="clickable center" v-on:click="order('gnz_id')">GNZ ID</th>
			<th class="center" v-if="showEdit">Edit</th>
		</tr>
		<tr v-for="result in results">
			<td>{{ result.email }}</td>
			<td>{{ result.first_name }}</td>
			<td>{{ result.last_name }}</td>
			<td class="center">
				<i v-show="result.activated" class="fa fa-check success"></i>
				<i v-show="!result.activated" class="fa fa-times error"></i>
			</td>
			<td class="center">
				<i v-show="result.gnz_active" class="fa fa-check success"></i>
				<i v-show="!result.gnz_active" class="fa fa-times error"></i>
			</td>
			<td class="center">{{ result.gnz_id }}</td>
			<td class="center" v-if="showEdit"><a v-bind:href="'/admin/users/' + result.id + '/roles'" class="btn btn-primary btn-xs">Roles</a></td>
		</tr>
	</table>

	<div class="btn-group" role="group">
		<button type="button" class="btn btn-outline-dark btn-sm" v-on:click="previous()">&lt;</button>
		<button type="button" class="btn btn-outline-dark btn-sm disabled">Page {{ page }} of {{ last_page }}</button>
		<button type="button" class="btn btn-outline-dark btn-sm" v-on:click="next()">Next &gt;</button>
	</div>
</div>
</template>

<script>
export default {
	data: function() {
		return {
			results: [],
			orgs: [],
			selectedOrg: "all",
			sort: 'email',
			direction: 'asc',
			page: 1,
			last_page: 0,
			total: 0,
			q: '',
			showEdit: false,
		}
	},
	created: function() {
		this.load();
		if (window.Laravel.clubAdmin) this.showEdit=true;
	},
	methods: {
		order: function(sortBy) {
			// check if we should change the direction
			if (this.sort==sortBy) {
				if (this.direction=='asc') this.direction='desc';
				else this.direction='asc';
			}
			// set the new item to sort by
			this.sort = sortBy;
			this.page=1;
			this.load();
		},
		load: function() {
			// this.$http.get('/api/v1/orgs').then(function (response) {
			// 	this.orgs = response.data.data;
			// });
			var that = this;
			var data = {sort: this.sort, direction: this.direction, page: this.page, q: this.q};
			window.axios.get('/api/v1/users', {params: data}).then(function (response) {
				var responseJson = response.data;

				that.results = responseJson.data;
				that.last_page = responseJson.data.last_page;
				that.total = responseJson.data.total;
				if (that.page > that.last_page && that.last_page>0) {
					that.page = 1;
				}
			});
		},
		next: function() {
			if (this.page<this.last_page) this.page = +this.page + 1;
			this.load();
		},
		previous: function() {
			if (this.page>1) this.page = +this.page - 1;
			this.load();
		},
		search: function() {
			this.load();
		}
	}
}
</script>
