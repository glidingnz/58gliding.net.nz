<style>
.mapbox {
}
.aircraft_marker {
	background-color: #A00;
	color: #FFF;
	font-size: 14px;
	font-weight: bold;
	text-align: center;
	border-radius: 50%;
	padding: 5px 0 3px 0;
	width: 34px;
	height: 34px;
}

.aircraft_marker_pin {
	position: absolute;
	content: '';
	width: 0px;
	height: 0px;;
	border: 10px solid transparent;
	border-top: 10px solid #A00;
	bottom: -17px;
	left: 7px;
}
.fullscreen .main-nav,
.fullscreen .footer {
	display: none !important;
}

.fullscreen .tracking {
	display: flex;
	flex-direction: column;
	min-height: 100vh;
}
.fullscreen .mapbox, .fullscreen .options {
	flex-grow: 1;
}
.tracking .sidepanel {
	position: fixed;
	width: 50px;
	background-color: #EEE;
	border: 1px solid #888;
	right: 0;
	top: 20px;
	border-top-left-radius: 5px;
	border-bottom-left-radius: 5px;
}
.tracking .sidepanel::-webkit-scrollbar { /* WebKit */
	width: 0;
	height: 0;
}
.tracking .expanded {
	width: auto;
}
.aircraft-badges {
	height: 80vh;
	overflow: scroll;
	scrollbar-width: none; /* Firefox */
	-ms-overflow-style: none;  /* Internet Explorer 10+ */
}
.tracking .aircraft-badge {
	font-size: 14px;
	font-weight: bold;
	text-align: center;
	background-color: #A00;
	color: #FFF;
	margin: 3px 0;
	padding: 0;
}
.legend .aircraft-badge {
	border-radius: 3px;
}
.legend td {
	padding: 0px 3px;
}
.legend th {
	text-align: center;
}

</style>

<template>
<div class="tracking">

	<div v-show="!showOptions" class="mapbox" id="map"></div>

	<div class="options" v-show="showOptions">
		<a href="/">Home</a>

		<label for="showAll"><input type="radio" id="showAll" value="all" v-model="filterIsland"> All</label> &nbsp;
		<label for="showNorth"><input type="radio" id="showNorth" value="north" v-model="filterIsland"> North</label> &nbsp;
		<label for="showSouth"><input type="radio" id="showSouth" value="south" v-model="filterIsland"> South</label>
		<button class="btn btn-outline-dark btn-sm" v-on:click="showOptions = !showOptions">Settings</button>

	</div>

	<div class="sidepanel" v-show="!showOptions" v-bind:class="[showLegend ? 'expanded' : '']">
		<button class="fa fa-angle-double-left ml-2 mt-1" v-if="!showLegend" v-on:click="showLegend=!showLegend" ></button>
		<div class="aircraft-badges">
			<div v-if="!showLegend" class="aircraft-badge" v-for="craft in filteredAircraft" v-bind:style="{backgroundColor: '#'+craft.colour}">
				{{getLabel(craft)}}
			</div>
			<table v-if="showLegend" class="legend">
				<tr>
					<th>Reg</th>
					<th>AGL</th>
					<th>
						Seen 
						<button class="fa fa-angle-double-right ml-2 mt-1" v-on:click="showLegend=!showLegend" ></button>
					</th>
				</tr>
				<tr v-for="craft in filteredAircraft">
					<td>
						<div class="aircraft-badge" 
							v-on:click="selectedAircraft = craft"
							v-bind:style="{backgroundColor: '#'+craft.colour}">
							{{getLabel(craft)}}
						</div>
					</td>
					<td>{{formatAltitudeFeet(heightAgl(craft.points[0].alt, craft.points[0].gl))}}</td>
					<td>{{dateToNow(craft.points[0].thetime)}}</td>
				</tr>
			</table>
		</div>
	</div>

	<div class="selected-aircraft" v-if="selectedAircraft">
		{{selectedAircraft.key}}
	</div>


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
				loading: false,
				showOptions: false,
				showLegend: false,
				selectedAircraft: null,
				flyingDay: null,
				'map': {},
				'nav': {},
				aircraft: [],
				showTrails: false,
				filterIsland: 'all',
				filterUnknown: false,
				mapMarkers: [],
			}
		},
		watch: {
			filterIsland: function() {
				this.loadTracks();
			}
		},
		computed: {
			filteredAircraft: function() {
				var that = this;
				return this.aircraft.filter(function(craft) {

					if (that.filterIsland=='north') {
						if ((craft.points[0].lat<-40.29 && craft.points[0].lng<174.36)) 
							return false;
					}
					if (that.filterIsland=='south') {
						if (!(craft.points[0].lat<-40.29 && craft.points[0].lng<174.36)) return false;
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
			zoom: 8
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
			return '?' + point.key.substring(0,2);
		},
		loadTracks: function() {
			var pings = 25;
			if (this.showTrails==false) pings=2;
			var that = this;

			// delete all exsiting markers
			for (var i=0; i<this.mapMarkers.length; i++) {
				this.mapMarkers[i].remove();
			}

			window.axios.get('/api/v2/tracking/' + that.flyingDay + '/aircraft/' + pings).then(function (response) {
				that.aircraft = response.data.data;
				that.createMarkers();
			});
		},
		createMarkers() {
			var that = this;
			that.filteredAircraft.forEach(function (aircraft) { 
				var el = document.createElement('div');
				var iel = document.createElement('div');
				var elpin = document.createElement('div');
				iel.className = 'aircraft_marker';
				elpin.className = 'aircraft_marker_pin';
				el.appendChild(iel);
				el.appendChild(elpin);
				iel.appendChild(document.createTextNode(that.getLabel(aircraft)));
				elpin.style.borderTopColor = '#'+aircraft.colour;
				iel.style.backgroundColor = '#'+aircraft.colour;

				var marker = new mapboxgl.Marker(el, {
						anchor: 'bottom',
						offset: [0, -5]
					})
					.setLngLat([aircraft.points[0].lng, aircraft.points[0].lat])
					.addTo(that.map);
				that.mapMarkers.push(marker);
			}); 
		},
		createMarker() {

		},
		updateMarker() {
			
		},
	}
}
</script>