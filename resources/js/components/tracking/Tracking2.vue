<style>
.mapbox {
	width: 100%;
	height: 500px;
	border: 1px solid #A00;
}
.aircraft_marker {
	background-color: #A00;
	color: #FFF;
	font-size: 12px;
	font-weight: bold;
	text-align: center;
	border-radius: 50%;
	padding: 5px 0 3px 0;
	width: 30px;
	height: 30px;
}

.aircraft_marker::before {
	position: absolute;
	content: '';
	width: 0px;
	height: 0px;;
	border: 10px solid transparent;
	border-top: 10px solid #A00;
	bottom: -16px;
	left: 5px;
}
</style>

<template>
<div>

	Map:
	<div class="mapbox" id="map"></div>

	
	<label for="showAll"><input type="radio" id="showAll" value="all" v-model="filterIsland"> All</label> &nbsp;
	<label for="showNorth"><input type="radio" id="showNorth" value="north" v-model="filterIsland"> North</label> &nbsp;
	<label for="showSouth"><input type="radio" id="showSouth" value="south" v-model="filterIsland"> South</label>

</div>
</template>

<script>
	import common from '../../mixins.js';
	import moment from 'moment';
	import mapboxgl from 'mapbox-gl';
	require('../../../../node_modules/mapbox-gl/dist/mapbox-gl.css');

	Vue.prototype.$moment = moment;

	export default {
		props: ['year','month','day'],
		mixins: [common],
		data: function() {
			return {
				flyingDay: null,
				'map': {},
				'nav': {},
				aircraft: [],
				showTrails: false,
				filterIsland: 'all',
				filterUnknown: false,
			}
		},
		computed: {
			filteredAircraft: function() {
				var that = this;
				return this.aircraft.filter(function(craft) {
					if (that.filterIsland=='north') {
						if (craft.points[0].lng<172.5270994) return false;
					}
					if (that.filterIsland=='south') {
						if (craft.points[0].lng>174.8282816) return false;
					}
					if (that.filterUnknown) {
						if (craft.rego=='') return false;
					}
					return true;
				});
				
				// check if NOT in the list of aircraft if we are filtering that way
				// if (this.selectedFleet!=null && this.fleet.aircraft && this.fleet.aircraft.length>0) {
				// 	var found=false;
				// 	for(var i = 0; i < this.fleet.aircraft.length; i++) {
				// 		if (this.fleet.aircraft[i].rego == item.rego) {
				// 			found=true;
				// 			break;
				// 		}
				// 	}
				// 	if (!found) return false;
				// }
				return true;
			}
		},
	mounted: function() {
		this.map = new mapboxgl.Map({
			container: 'map',
			style: '/mapstyle.json',
			center: [175.409, -40.97435],
			zoom: 6
		});

		this.nav = new mapboxgl.NavigationControl();
		this.map.addControl(this.nav, 'top-left');

		this.loadDays();
	},
	methods: {
		loadDays: function() {
			var that = this;
			window.axios.get('/api/v1/tracking/days').then(function (response) {
				that.days = response.data.data;
				if (that.year==null || that.month==null || that.day==null) {
					// get the first day
					that.flyingDay = that.days[0].day_date;
				} else {
					// use the given date
					that.flyingDay = that.year + '-' + that.month + '-' + that.day;
				}
				that.dateToJumpTo=that.flyingDay;

				that.loadTracks();
			});
		},
		getLabel: function(point) {
			if (point.aircraft) {
				if (point.aircraft.contest_id) return point.aircraft.contest_id;
			}
			if (point.rego) return point.rego;
			return '?';
		},
		loadTracks: function() {
			var pings = 25;
			if (this.showTrails==false) pings=2;
			var that = this;

			window.axios.get('/api/v2/tracking/' + that.flyingDay + '/aircraft/' + pings).then(function (response) {
				that.aircraft = response.data.data;

				console.log(response.data.data);
				that.createMarkers();
			});
		},
		createMarkers() {
			var that = this;
			that.filteredAircraft.forEach(function (aircraft) { 
				var el = document.createElement('div');
				var iel = document.createElement('div');
				iel.className = 'aircraft_marker';
				el.appendChild(iel);
				iel.appendChild(document.createTextNode(that.getLabel(aircraft)));

				new mapboxgl.Marker(el, {
						anchor: 'bottom',
						offset: [0, -5]
					})
					.setLngLat([aircraft.points[0].lng, aircraft.points[0].lat])
					.addTo(that.map);
			}); 
		},
		createMarker() {

		},
		updateMarker() {
			
		},
	}
}
</script>