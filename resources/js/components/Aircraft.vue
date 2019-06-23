<style>
.results-title {
	margin-top: 0;
	margin-bottom: 20px;
}
.btn-group {
	margin-bottom: 20px;
}
.filter-buttons {
	margin-bottom: 15px;
}
.filter-buttons .btn {
	margin-bottom: 5px;
}
.filter-buttons .btn-group {
	margin-bottom: 0;
}
</style>


<template>
	<div>

		<h1 class="col-xs-6 results-title">Aircraft</h1>
		<div class="btn-group col-md-4 col-xs-6 pull-right" role="group">

			<div class="input-group">
				<input type="text" class="form-control" placeholder="Search" name="srch-term" id="srch-term" v-model="state.search" debounce="300">
				<div class="input-group-btn">
					<button class="btn btn-default" type="submit" v-on:click="state.search=''"><i class="fa fa-times"></i></button>
				</div>
			</div>

		</div>
	</div>

	<div class="filter-buttons nav nav-pills col-xs-12" role="group">

		<div class="btn-group" role="group">
			<button type="button" class="btn btn-default btn-sm" v-bind:class="{ 'btn-primary': state.type=='glider' }" v-on:click="filterTo('glider')">All Gliders</button>
			<button type="button" class="btn btn-default btn-sm" v-bind:class="{ 'btn-primary': state.type=='self-launch' }" v-on:click="filterTo('self-launch')">Self Launch</button>
			<button type="button" class="btn btn-default btn-sm" v-bind:class="{ 'btn-primary': state.type=='sustainer' }" v-on:click="filterTo('sustainer')">Turbo</button>
			<button type="button" class="btn btn-default btn-sm" v-bind:class="{ 'btn-primary': state.type=='vintage' }" v-on:click="filterTo('vintage')">Vintage</button>
			<button type="button" class="btn btn-default btn-sm" v-bind:class="{ 'btn-primary': state.type=='singles' }" v-on:click="filterTo('singles')">Singles</button>
			<button type="button" class="btn btn-default btn-sm" v-bind:class="{ 'btn-primary': state.type=='twins' }" v-on:click="filterTo('twins')">Twins</button>
		</div>
		<button type="button" class="btn btn-default btn-sm" v-bind:class="{ 'btn-primary': state.type=='tug' }" v-on:click="filterTo('tug')">Tugs</button>
		<button type="button" class="btn btn-default btn-sm" v-bind:class="{ 'btn-primary': state.type=='gyrocopter' }" v-on:click="filterTo('gyrocopter')">Gyros</button>
		<button type="button" class="btn btn-default btn-sm" v-bind:class="{ 'btn-primary': state.type=='helicopter' }" v-on:click="filterTo('helicopter')">Heli</button>
		<button type="button" class="btn btn-default btn-sm" v-bind:class="{ 'btn-primary': state.type=='balloon' }" v-on:click="filterTo('balloon')">Balloons</button>
		<button type="button" class="btn btn-default btn-sm" v-bind:class="{ 'btn-primary': state.type=='plane' }" v-on:click="filterTo('plane')">Planes</button>
		<button type="button" class="btn btn-default btn-sm" v-bind:class="{ 'btn-primary': state.type=='microlight' }" v-on:click="filterTo('microlight')">Microlights</button>
		<button type="button" class="btn btn-default btn-sm" v-bind:class="{ 'btn-primary': state.type=='all' }" v-on:click="filterTo('all')">All</button>
	</div>

	<div class="row">
		<div class="col-xs-12 col-sm-4 hidden-xs">

			<h2 class="results-title">{{ total }} Results</h2>

		</div>

		<div class="col-xs-12 col-sm-8">

			<div class="btn-group pull-right" role="group">
				<button type="button" class="btn btn-default btn-sm" v-on:click="previous()">&lt;</button>
				<button type="button" class="btn btn-default btn-sm disabled">Page {{ state.page }} of {{ last_page }}</button>
				<button type="button" class="btn btn-default btn-sm" v-on:click="next()">Next &gt;</button>
			</div>

		</div>
	</div>

		<table class="table results-table ">
			<tr>
				<th class="hidden-xs hidden-sm">Rego</th>
				<th>Comp</th>
				<th>Manufacturer</th>
				<th>Model</th>
				<th class="hidden-xs">Class</th>
				<th class="hidden-xs">Owner</th>
				<th></th>
				<th></th>
				<th class="hidden-xs"></th>
			</tr>
			<tbody v-for="result in results">
				<tr>
					<td class="hidden-xs hidden-sm nowrap">{{ result.rego }}</td>
					<td>{{ result.contest_id }}</td>
					<td>{{ result.manufacturer }}</td>
					<td>{{ result.model }}</td>
					<td class="hidden-xs">{{ result.class }}</td>
					<td class="hidden-xs">{{ result.owner }}</td>
					<td class="hidden-xs">
						<a href="https://www.caa.govt.nz/Script/AirReg3.asp?Mark={{ result.rego.substring(3,6) }}">CAA</a>
					</td>
					<td>
						<a href="/aircraft/{{result.rego}}" class="btn btn-primary btn-xs">View</a>
					</td>
					<td>
						<a href="/aircraft/{{result.rego}}/edit" class="btn btn-primary btn-xs">Edit</a>
					</td>
				</tr>
				<tr class="visible-xs" >
					<td colspan="5"style="border-top: none; padding-top: 0;">
						<span style="color: #888;">{{ result.owner }}</span>
						<a class="visible-xs pull-right" href="https://www.caa.govt.nz/Script/AirReg3.asp?Mark={{ result.rego.substring(3,6) }}">CAA</a>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</template>


<script>
	import common from '../mixins.js';

	export default {
		mixins: [common],
		data() {
			return {
				state: {
					type: 'glider',
					page: 1,
					search: ''
				},
				last_page: 1,
				total: 0,
				results: [],
				dont_reload: false
			}
		},
		watch: {
			'state': {
				handler: 'stateChanged',
				deep: true
			}
		},
		ready() {
			// check for URL params
			var State = History.getState();

			// load existing GET params
			if (this.get_url_param('search')) this.state.search = this.get_url_param('search');
			if (this.get_url_param('page')) this.state.page = this.get_url_param('page');
			if (this.get_url_param('type')) this.state.type = this.get_url_param('type');

			var that = this;

			History.Adapter.bind(window, 'statechange', function() {
				//console.log('statechange triggered');
				var state = History.getState();
				that.state = state.data;
				if (!that.dont_reload) {
					//console.log('reloading after statechange');
					that.loadSelected();
				}
				that.dont_reload=false;
			});

			this.dont_reload=true; // make sure we dont do a double load on page launch
			History.replaceState(this.state, null, "?search=" + this.state.search + "&type=" + this.state.type + "&page=" + this.state.page);
			that.loadSelected();
		},
		methods: {
			filterTo: function(type) {
				this.state.type = type;
				this.state.page=1;
			},
			stateChanged: function() {
				History.pushState(this.state, null, "?search=" + this.state.search + "&type=" + this.state.type + "&page=" + this.state.page);
			},
			loadSelected: function() {
				this.$http.get('/api/v1/aircraft', {params: this.state}).then(function (response) {
					var responseJson = response.json();
					
					this.results = responseJson.data;
					this.last_page = responseJson.last_page;
					this.total = responseJson.total;

					if (this.state.page > this.last_page && this.last_page>0) {
						this.state.page = 1;
					}
				});
			}
		}
	}
</script>
