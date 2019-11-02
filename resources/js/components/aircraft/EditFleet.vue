<template>
<div>

	<h1><a href="/aircraft">Aircraft</a> &raquo; <a href="/fleets">Fleets</a> &raquo; <span v-if="fleet.name">{{fleet.name}}</span></h1>

	<div class="form-inline">
		<span class="label mr-2" >Add Aircraft:</span> <input type="text" class="form-control" v-model="searchString">
	</div>

</div>
</template>


<script>
	import common from '../../mixins.js';

	export default {
		mixins: [common],
		props: ['orgId', 'fleetId'],
		data() {
			return {
				fleet: {},
				searchString: ''
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

				window.axios.get('/api/v1/aircraft/').then(function (response) {
					that.fleet = response.data.data;
				});
			}
		}
	}
</script>