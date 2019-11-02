<template>
<div>

	<h1><a href="/aircraft">Aircraft</a> &raquo; <a href="/fleets">Fleets</a> &raquo; <span v-if="fleet.name">{{fleet.name}}</span></h1>

	<div class="form-inline mb-4">
		<span class="label mr-2" >Add by Rego:</span> <input type="text" class="form-control mr-2" v-model="searchString" v-debounce:500ms="searchAircraft"> e.g. GBA
	</div>

	<div v-if="searchResults!=null" class="mb-4">
		<h2>Search Results</h2>

		<table v-if="searchResults.length>0" class="table table-striped table-sm">
			<tr>
				<th>Rego</th>
				<th>Add</th>
				<th>Contest ID</th>
				<th>Model</th>
				<th>Manufacturer</th>
				<th>Class</th>
			</tr>
			<tr v-for="aircraft in searchResults">
				<td>{{aircraft.rego}}</td>
				<td><button class="btn btn-dark btn-xs" v-on:click="addAircraft(aircraft)">Add</button></td>
				<td>{{aircraft.contest_id}}</td>
				<td>{{aircraft.model}}</td>
				<td>{{aircraft.manufacturer}}</td>
				<td>{{aircraft.class}}</td>
			</tr>
		</table>

		<span class="badge badge-danger" v-if="searchResults.length==0">No Results</span>

	</div>

	<h2>Current Aircraft</h2>

	<table v-if="fleet.aircraft && fleet.aircraft.length!=0" class="table table-striped table-sm">
		<tr>
			<th>Rego</th>
			<th>Contest ID</th>
			<th>Model</th>
			<th>Manufacturer</th>
			<th>Class</th>
			<th>Remove</th>
		</tr>
		<tr v-for="aircraft in fleet.aircraft">
			<td>{{aircraft.rego}}</td>
			<td>{{aircraft.contest_id}}</td>
			<td>{{aircraft.model}}</td>
			<td>{{aircraft.manufacturer}}</td>
			<td>{{aircraft.class}}</td>
			<td><button class="btn btn-outline-dark btn-xs" v-on:click="removeAircraft(aircraft)">Remove</button></td>
		</tr>
	</table>

	<span  class="badge badge-warning" v-if="!fleet.aircraft || fleet.aircraft.length==0">This fleet is empty!</span>

</div>
</template>


<script>
	import common from '../../mixins.js';
	import vueDebounce from 'vue-debounce'
	Vue.use(vueDebounce);

	export default {
		mixins: [common],
		props: ['orgId', 'fleetId'],
		data() {
			return {
				fleet: {},
				searchString: '',
				searchResults: null
			}
		},
		mounted() {
			this.load();
		},
		methods: {
			load: function() {
				var that=this;
				window.axios.get('/api/v1/fleets/' + this.fleetId).then(function (response) {
					that.fleet = response.data.data;
				});
			},
			searchAircraft: function()
			{
				var that=this;
				if (this.searchString!='') {
					window.axios.get('/api/v1/aircraft?search-rego=' + this.searchString).then(function (response) {
						that.searchResults = response.data.data;
					});
				} else {
					this.searchResults = null;
				}
			},
			addAircraft: function(aircraft)
			{
				var that=this;
				var data = {
					"aircraft_id": aircraft.id
				}
				window.axios.post('/api/v1/fleets/' + this.fleetId + '/add', data).then(function (response) {
					messages.$emit('success', aircraft.rego + ' added');
					that.load();
				});
			},
			removeAircraft: function(aircraft)
			{
				var that=this;
				var data = {
					"aircraft_id": aircraft.id
				}
				window.axios.post('/api/v1/fleets/' + this.fleetId + '/remove', data).then(function (response) {
					messages.$emit('success', aircraft.rego + ' removed');
					that.load();
				});
			}
		}
	}
</script>