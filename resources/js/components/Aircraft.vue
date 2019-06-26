

<template>
	<div>
		<div class="">

			<div class="input-group ml-auto col-md-4 col-6 float-right">
				<input type="text" class="form-control" placeholder="Search" name="srch-term" id="srch-term" v-model="state.search">
				<div class="input-group-append">
					<button class="btn btn-outline-dark" type="submit" v-on:click="state.search=''"><i class="fa fa-times"></i></button>
				</div>
			</div>

			<h1 class="results-title">Aircraft</h1>

		</div>

		<div class="filter-buttons col-xs-12" role="group">

			<div class="btn-group mr-2" role="group">
				<button type="button" class="btn btn-sm mb-2" v-bind:class="[ state.type=='glider' ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="filterTo('glider')">All Gliders</button>
				<button type="button" class="btn btn-sm mb-2" v-bind:class="[ state.type=='self-launch' ? 'btn-secondary':  'btn-outline-dark' ] " v-on:click="filterTo('self-launch')">Self Launch</button>
				<button type="button" class="btn btn-sm mb-2" v-bind:class="[ state.type=='sustainer' ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="filterTo('sustainer')">Turbo</button>
				<button type="button" class="btn btn-sm mb-2" v-bind:class="[ state.type=='vintage' ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="filterTo('vintage')">Vintage</button>
				<button type="button" class="btn btn-sm mb-2" v-bind:class="[ state.type=='singles' ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="filterTo('singles')">Singles</button>
				<button type="button" class="btn btn-sm mb-2" v-bind:class="[ state.type=='twins' ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="filterTo('twins')">Twins</button>
			</div>

			<button type="button" class="btn btn-sm mr-2 mb-2" v-bind:class="[ state.type=='tug' ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="filterTo('tug')">Tugs</button>
			<button type="button" class="btn btn-sm mr-2 mb-2" v-bind:class="[ state.type=='gyrocopter' ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="filterTo('gyrocopter')">Gyros</button>
			<button type="button" class="btn btn-sm mr-2 mb-2" v-bind:class="[ state.type=='helicopter' ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="filterTo('helicopter')">Heli</button>
			<button type="button" class="btn btn-sm mr-2 mb-2" v-bind:class="[ state.type=='balloon' ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="filterTo('balloon')">Balloons</button>
			<button type="button" class="btn btn-sm mr-2 mb-2" v-bind:class="[ state.type=='plane' ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="filterTo('plane')">Planes</button>
			<button type="button" class="btn btn-sm mr-2 mb-2" v-bind:class="[ state.type=='microlight' ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="filterTo('microlight')">Microlights</button>
			<button type="button" class="btn btn-sm mr-2 mb-2" v-bind:class="[ state.type=='all' ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="filterTo('all')">All Aircraft</button>
		</div>
	
		<div>
			<div class="ml-auto ">

				<div class="btn-group float-right" role="group">
					<button type="button" class="btn btn-outline-dark btn-sm" v-on:click="previous()">&lt;</button>
					<button type="button" class="btn btn-outline-dark btn-sm disabled">Page {{ state.page }} of {{ last_page }}</button>
					<button type="button" class="btn btn-outline-dark btn-sm" v-on:click="next()">Next &gt;</button>
				</div>

			</div>

			<h2 class="results-title">{{ total }} Results</h2>

		</div>

			<table class="table results-table">
				<!-- <tr>
					<th class="d-lg-block">Rego</th>
					<th>Comp</th>
					<th>Manufacturer</th>
					<th>Model</th>
					<th class="d-lg-block">Class</th>
					<th class="d-lg-block">Owner</th>
					<th></th>
					<th class="d-lg-block"></th>
				</tr> -->
				<tbody v-for="result in results">
					<tr>
						<td class="d-none d-lg-table-cell nowrap">{{ result.rego }}</td>
						<td>{{ result.contest_id }}</td>
						<td>{{ result.manufacturer }}</td>
						<td>{{ result.model }}</td>
						<td class="d-none d-lg-table-cell">{{ result.class }}</td>
						<td class="d-none d-lg-table-cell">{{ result.owner }}</td>
						<td>
							<a v-bind:href="'/aircraft/' + result.rego" class="btn btn-primary btn-sm">View</a>
						</td>
						<td>
							<a v-bind:href="'/aircraft/' + result.rego + '/edit'" class="btn btn-primary btn-sm">Edit</a>
						</td>
					</tr>
					<tr class="d-md-none">
						<td colspan="5"style="border-top: none; padding-top: 0;">
							<span style="color: #888;">{{ result.owner }}</span>
							<!-- <a class="visible-xs pull-right" v-bind:href="'https://www.caa.govt.nz/Script/AirReg3.asp?Mark=' + result.rego.substring(3,6) ">CAA</a> -->
						</td>
					</tr>
				</tbody>
			</table>
		</div>
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
		mounted() {
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
				var that=this;
				window.axios.get('/api/v1/aircraft', {params: this.state}).then(function (response) {
					
					that.results = response.data.data;
					that.last_page = response.data.last_page;
					that.total = response.data.total;

					if (that.state.page > that.last_page && that.last_page>0) {
						that.state.page = 1;
					}
				});
			}
		}
	}
</script>
