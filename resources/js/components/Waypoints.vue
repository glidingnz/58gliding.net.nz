<template>

	<table class="table table-striped">
		<tr>
			<!-- <th>ID</th> -->
			<th>Code</th>
			<th>Name</th>
			<th>Lat</th>
			<th>Long</th>
			<th>Elevation</th>
			<th>Style</th>
			<th>Direction</th>
			<th>Length</th>
			<th>Description</th>
		</tr>
		<tr v-for="waypoint in results">
			<!-- <td>{{waypoint.id}}</td> -->
			<td>{{waypoint.code}}</td>
			<td>{{waypoint.name}}</td>
			<td>{{waypoint.lat}}</td>
			<td>{{waypoint.long}}</td>
			<td>{{displayElevation(waypoint.elevation)}}</td>
			<td>{{displayStyle(waypoint.style)}}</td>
			<td><span v-html="displayDirection(waypoint.direction)"></span></td>
			<td>{{displayLength(waypoint.length)}}</td>
			<td>{{waypoint.description}}</td>
		</tr>
	</table>

</template>




<script>
	import common from '../mixins.js';

	export default {
		mixins: [common],
		data() {
			return {
				results: [],
				showEdit: false,
				showAdmin: false
			}
		},
		ready() {
			this.loadWaypoints();
			if (window.Laravel.allowsEdit==true) this.showEdit=true;
			if (window.Laravel.admin==true) this.showAdmin=true;
		},
		methods: {
			loadWaypoints: function() {
				this.$http.get('/api/v1/waypoints/').then(function (response) {
					var responseJson = response.json();
					this.results = responseJson.data;
				});
			},
			displayDirection: function(direction) {
				if (direction) return direction + '&deg;';
				return '';
			},
			displayLength: function(length) {
				if (length) return length + 'm';
				return '';
			},
			displayElevation: function(elevation) {
				if (elevation) return elevation + ' feet';
				return '';
			},
			displayStyle: function(style) {
				switch (style) {
					case 1: return 'Waypoint'; break;
					case 2: return 'Airfield Grass'; break;
					case 3: return 'Outlanding'; break;
					case 4: return 'Gliding airfield'; break;
					case 5: return 'Airfield Solid'; break;
					case 6: return 'Mountain Pass'; break;
					case 7: return 'Mountain Top'; break;
					case 8: return 'Transmitter Mast'; break;
					case 9: return 'VOR'; break;
					case 10: return 'NDB'; break;
					case 11: return 'Cooling Tower'; break;
					case 12: return 'Dam'; break;
					case 13: return 'Tunnel'; break;
					case 14: return 'Bridge'; break;
					case 15: return 'Power Plant'; break;
					case 16: return 'Castle'; break;
					case 17: return 'Intersection'; break;
					default: return 'Unknown'; break;
				}
			}
		}
	}
</script>