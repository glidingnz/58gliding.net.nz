<style>

.fullscreen .main-nav,
.fullscreen .footer {
	display: none !important;
}

.mapbox {
}

html, body, 
.fullscreen,
.fullscreen .tracking, 
.fullscreen .flex-vertical {
	height: 100%;
}

.fullscreen .flex-vertical {
	height: 100vh; /* Fallback for browsers that do not support Custom Properties */
	height: calc(var(--vh, 1vh) * 100);
}

.aircraft_marker {
	background-color: #A00;
	color: #FFF;
	font-size: 110%;
	font-weight: bold;
	text-align: center;
	border-radius: 50%;
	padding: 5px 0 3px 0;
	width: 34px;
	height: 34px;
	box-shadow: 0px 0px 15px 0px rgba(0,0,0,0.3);
}
.aircraft_marker_pin {
	position: absolute;
	content: '';
	width: 0px;
	height: 0px;
	border: 10px solid transparent;
	border-top: 10px solid #A00;
	bottom: -17px;
	left: 7px;
}

.fullscreen .maprow	{
	display: flex;
	flex-direction: row;
	flex-grow: 1;
}
.fullscreen .mapbox, .fullscreen .options {
	flex-grow: 1;
}
.tracking .sidepanel {
	display: flex;
	flex-direction: column;
	width: 50px;
	background-color: #EEE;
	border-left: 1px solid #888;
}
.tracking .expanded {
	width: auto;
}
.aircraft-badges {
	flex-grow: 1;
	height: 100px;
	overflow: scroll;
	scrollbar-width: none; /* Firefox */
	-ms-overflow-style: none;  /* Internet Explorer 10+ */
}
.aircraft-badges::-webkit-scrollbar { /* WebKit */
	width: 0;
	height: 0;
}
.tracking .aircraft-badge {
	font-size: 110%;
	font-weight: bold;
	text-align: center;
	background-color: #A00;
	color: #FFF;
	padding: 0 3px;
	border-radius: 3px;
}
.legend td, .legend th {
	padding: 3px 3px;
}
.legend th {
	text-align: center;
}
.legend-header {
	width: 100%;
}
.hover-row:hover {
	background-color: #5AF;
}
.selected-aircraft .flex-row {
	display: flex;
	justify-content: space-between;
	padding: 3px 5px;
	font-size: 120%;
	max-width: 500px;
	margin-left: auto;
	margin-right: auto;
	flex-grow: 1;
}

.selected-aircraft {
	border-top: 1px solid #888;
}

.mapboxgl-ctrl-bottom-right {
	z-index: 0 !important;
}
.mapbox .btn-outline-dark {
	background-color: #EEE;
}
.mapbox .btn-outline-dark:hover {
	background-color: #000;
}

.mapbox .buttons {
	position: absolute;
	left: 50px;
	top: 10px;
	z-index: 10;
	display: flex;
}
.tracking .options, 
.tracking .day-selector {
	padding: 10px;
	position: absolute;
	top: 43px;
	left: 50px;
	z-index: 999;
	background-color: #FFF;
	border-radius: 5px;
	border: 1px solid #888;
	margin-right: 20px;
	max-height: 80%;
	overflow: scroll;
}
.flex-vertical {
	display: flex;
	flex-direction: column;
}
</style>

<template>
<div class="tracking" id="tracking">

	<div class="flex-vertical">

		<div class="maprow">

			<!-- <div class="mapbox">Map</div> -->
			<div class="mapbox" id="map">
				<div class="buttons">
					<button class="settings-button fa fa-cog btn btn-outline-dark" v-on:click="showOptions = !showOptions"></button>
					<button class="settings-button fa fa-calendar btn btn-outline-dark ml-2" v-on:click="showDaySelector = !showDaySelector"></button>
					<div class="loading ml-2 mt-1" v-show="loading"><span class=" fas fa-sync fa-spin"></span> Loading...</div>
				</div>
			</div>

			<div class="sidepanel" v-bind:class="[showLegend ? 'expanded' : '']">
				<table class="legend legend-header">
					<tr>
						<th v-show="!showLegend">
							<button class="fa fa-angle-double-left btn btn-xs btn-outline-dark ml-2 mt-1 pr-2 pl-2" v-if="!showLegend" v-on:click="showLegend=!showLegend" ></button>
						</th>
						<th v-show="showLegend">Reg</th>
						<th v-show="showLegend">AGL</th>
						<th v-show="showLegend">Seen</th>
						<th v-show="showLegend">
							<button class="fa fa-angle-double-right btn btn-xs btn-outline-dark ml-2 mt-1 pr-2 pl-2" v-on:click="showLegend=!showLegend" ></button>
						</th>
					</tr>
				</table>
				<div class="aircraft-badges">
					<table class="legend">
						<tr v-for="craft in filteredAircraft" v-on:click="selectAircraft(craft)" class="hover-row">
							<td>
								<div class="aircraft-badge" 
									v-bind:style="{backgroundColor: '#'+craft.colour}">
									{{getLabel(craft)}}
								</div>
							</td>
							<td v-show="showLegend">{{formatAltitudeFeet(heightAgl(craft.points[0].alt, craft.points[0].gl))}}</td>
							<td v-show="showLegend">{{dateToNow(createDateFromMysql(craft.points[0].thetime))}}</td>
						</tr>
					</table>
				</div>
			</div>
		</div>

		<div class="selected-aircraft" v-if="selectedAircraft">
			<div class="flex-row">
				<div class="aircraft-badge" v-on:click="showOptions=!showOptions" v-bind:style="{backgroundColor: '#'+selectedAircraft.colour}">{{selectedAircraft.key}}</div>
				<div>{{formatAltitudeFeet(heightAgl(selectedAircraft.points[0].alt, selectedAircraft.points[0].gl))}}</div>
				<div>{{ Math.round(selectedAircraft.points[0].vspeed * 1.944) }} kt</div>
				<div>{{dateToNow(createDateFromMysql(selectedAircraft.points[0].thetime))}}</div>
				<div>
					<label for="follow"><input name="follow" id="follow" type="checkbox" v-on:click="follow()" v-model="optionFollow" :value="true"> Follow</label>
				</div>
			</div>
		</div>

	</div>



	<div class="day-selector" v-show="showDaySelector" v-if="days">

		<button class="btn btn-outline-dark btn-sm mb-2" v-on:click="showDaySelector = !showDaySelector">Close</button>

		<div class="list-group" >
			<a v-bind:href="'/tracking2/' + day.day_date"  v-for="(day, index) in days" class="list-group-item list-group-item-action" v-bind:class="[ day.day_date==flyingDay ? 'btn-secondary' : 'btn-outline-dark']">{{formatDate(day.day_date)}}
			</a>
		</div>
	</div>


	<div class="options" v-show="showOptions">

		<button class="btn btn-outline-dark btn-sm float-right" v-on:click="showOptions = !showOptions">Close</button>

		<h4>Filters</h4>

		<label for="showAll"><input type="radio" id="showAll" value="all" v-model="filterIsland"> All</label> &nbsp;
		<label for="showNorth"><input type="radio" id="showNorth" value="north" v-model="filterIsland"> North</label> &nbsp;
		<label for="showSouth"><input type="radio" id="showSouth" value="south" v-model="filterIsland"> South</label>

		<hr>
		<h4>Options</h4>

			<label for="zoomToSelected"><input name="zoomToSelected" id="zoomToSelected" type="checkbox" class="" v-model="optionZoomToSelected" :value="true"> Zoom To Selected</label>

			<label for="live"><input name="live" id="live" type="checkbox" class="" v-model="optionLive" :value="true"> Live Updates</label>

		<hr>
		<p class=""><a href="/">Exit Tracking</a></p>

		<p class="figure-caption">
			Big thanks to <a href="http://glidernet.org">glidernet.org</a> for the FLARM tracking system and <a href="https://trackme.nz">TrackMe.nz</a> for their SPOT &amp; InReach Tracking data.
		</p>

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
				showDaySelector: false,
				showLegend: true,
				optionZoomToSelected: true,
				optionLive: true,
				optionFollow: true,
				selectedAircraftKey: null,
				selectedAircraftTrack: [], // all the track data
				selectedAircraftTrackGeoJson: [], // used by mapbox
				flyingDay: null,
				'map': {},
				'nav': {},
				aircraft: [],
				days: [],
				showTrails: false,
				filterIsland: 'all',
				filterUnknown: false,
				mapMarkers: [],
				mapFlying: false,
				fitBoundsStarted: false
			}
		},
		watch: {
			filterIsland: function() {
				this.loadTracks();
			},
			showLegend: function() {
				setTimeout(() => this.map.resize(), 20);
			}
		},
		computed: {
			selectedAircraft: function() {
				if (!this.selectedAircraftKey) return false;
				var searchKey = this.selectedAircraftKey;
				return this.aircraft.find( ({ key }) => key === searchKey );
			},
			filteredAircraft: function() {
				var that = this;
				return _.orderBy(this.aircraft.filter(function(craft) {

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
				}), ['aircraft.contest_id', 'key']);
				
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
		var that = this;
		mapboxgl.accessToken = 'pk.eyJ1IjoiaXBlYXJ4IiwiYSI6ImNqd2c1dnU3bjFoMmg0NHBzbG9vbmQwbGkifQ.HeNPRpXBkpmC_ljY7QQTRA';
		this.map = new mapboxgl.Map({
			container: 'map',
			style: 'mapbox://styles/ipearx/ck32sc9mh34gt1cqlyi852vh1',
			center: [175.409, -40.97435],
			zoom: 5
		});
		this.map.on('moveend', function(e){
			// we've finished moving. Check if it was started by a fit bounds
			if (that.fitBoundsStarted) {
				that.fitBoundsStarted=false;
				if (that.optionFollow && that.selectedAircraft) {
					that.map.panTo([that.selectedAircraft.points[0].lng, that.selectedAircraft.points[0].lat]);
				}
			}
		});

		// only try and put things on the map once it's loaded
		this.map.on('load', this.mapLoaded);

		// check if the legend should be open or not
		if (window.innerWidth<600) this.showLegend=false;

		// from https://css-tricks.com/the-trick-to-viewport-units-on-mobile/
		let vh = window.innerHeight * 0.01;
		document.documentElement.style.setProperty('--vh', `${vh}px`);
		window.addEventListener('resize', () => {
			//We execute the same script as before
			let vh = window.innerHeight * 0.01;
			document.documentElement.style.setProperty('--vh', `${vh}px`);
		});

	},
	methods: {
		mapLoaded: function() {
			var that = this;
			// setup center after zooming
			if(that.mapFlying) {
				// tooltip or overlay here
				map.fire(flyend); 
			}
			this.nav = new mapboxgl.NavigationControl();
			this.map.addControl(this.nav, 'top-left');

			// start the timer
			this.timeoutTimer = setTimeout(this.timerLoop, 15000);
			this.loadDays();
		},
		loadDays: function() {
			var that = this;

			this.loading=true;

			window.axios.get('/api/v1/tracking/days').then(function (response) {
				that.loading=false;
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
			this.loading=true;

			var pings = 25;
			if (this.showTrails==false) pings=3;
			var that = this;

			window.axios.get('/api/v2/tracking/' + that.flyingDay + '/' + pings).then(function (response) {

				// delete all exsiting markers
				for (var i=0; i<that.mapMarkers.length; i++) {
					that.mapMarkers[i].remove();
				}

				that.loading=false;
				that.aircraft = response.data.data;

				that.createMarkers();
				that.createTracks()
			});
		},
		selectAircraft: function(aircraft) {
			var that = this;
			var from = 0;

			// check if we already have some data
			if (this.selectedAircraftKey==aircraft.key) {
				// get the last point retreived
				from = this.selectedAircraftTrack[0].thetime;

			} else {
				that.selectedAircraftTrack = [];
			}

			this.selectedAircraftKey=aircraft.key;
			this.loading=true;

			// load the data
			window.axios.get('/api/v2/tracking/' + that.flyingDay + '/aircraft/' + aircraft.key + '?from=' + from).then(function (response) {

				that.loading=false;
				var newData = response.data.data;


				// setup the geojson object if it hasn't been yet
				if (from==0) {
					that.selectedAircraftTrackGeoJson = {
						'type': 'FeatureCollection',
						'features': [{
							'type': 'Feature',
							'properties': {
								'color': '#' + aircraft.colour
							},
							'geometry': {
								'type': 'LineString',
								'coordinates': []
							}
						}]
					};
				}
				// Step through the new data from oldest to newest, and place onto the beginning of the array.
				// This way the order of newest to oldest is maintained
				for (var i=newData.length-1; i>=0; i--) {
					var point = newData[i];
					that.selectedAircraftTrackGeoJson.features[0].geometry.coordinates.unshift([point.lng, point.lat]);
					that.selectedAircraftTrack.unshift(point);
				}

				if (from==0) {
					// create a new track
					that.createSelectedTrack(aircraft);
				} else {
					// update the existing track
					that.map.getSource('selectedTrack').setData(that.selectedAircraftTrackGeoJson);
				}
				

				if (that.optionZoomToSelected && from==0) {
					var coords = that.selectedAircraftTrackGeoJson.features[0].geometry.coordinates; // shortcut

					var bounds = coords.reduce(function(bounds, coord) {
						return bounds.extend(coord);
					}, new mapboxgl.LngLatBounds(coords[0], coords[0]));
					 
					that.fitBoundsStarted = true;
					that.map.fitBounds(bounds, {
						padding: 20
					});

				}

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
				el.addEventListener('click', () => 
					{ 
						that.selectAircraft(aircraft);
					}
				); 
				var marker = new mapboxgl.Marker(el, {
						anchor: 'bottom',
						offset: [0, -5]
					})
					.setLngLat([aircraft.points[0].lng, aircraft.points[0].lat])
					.addTo(that.map);
				that.mapMarkers.push(marker);

				if (that.optionFollow && that.selectedAircraft) {
					that.map.panTo([that.selectedAircraft.points[0].lng, that.selectedAircraft.points[0].lat]);
				}

			}); 
		},
		createSelectedTrack(aircraft) {
			var that = this;

			// delete existing selected track
			var mapLayer = this.map.getLayer('selectedTrack');
			if (typeof mapLayer !== 'undefined') {
				this.map.removeLayer('selectedTrack');
				this.map.removeSource('selectedTrack');
			}

			that.map.addLayer({
				'id': 'selectedTrack',
				'type': 'line',
				'source': {
					'type': 'geojson',
					'lineMetrics': true,
					'data': that.selectedAircraftTrackGeoJson
				},
				'paint': {
					"line-width": 6,
					'line-color': ['get', 'color'],
					'line-opacity': .8
				}
			});

		},
		createTracks() {
			// delete existing track lines
			var mapLayer = this.map.getLayer('lines');
			if (typeof mapLayer !== 'undefined') {
				this.map.removeLayer('lines');
				this.map.removeSource('lines');
			}

			var that = this;
			var features = [];

			that.filteredAircraft.forEach(function (aircraft) { 
				features.push({
					'type': 'Feature',
					'properties': {
						'color': '#' + aircraft.colour
					},
					'geometry': {
						'type': 'LineString',
						'coordinates': that.getLineCoords(aircraft)
					}
				});
			});

			that.map.addLayer({
				'id': 'lines',
				'type': 'line',
				'source': {
					'type': 'geojson',
					'lineMetrics': true,
					'data': {
						'type': 'FeatureCollection',
						'features': features
					}
				},
				'paint': {
					"line-width": 4,
					// Use a get expression (https://docs.mapbox.com/mapbox-gl-js/style-spec/#expressions-get)
					// to set the line-color to a feature property value.
					'line-color': ['get', 'color'],
					'line-opacity': 0.5
				}
			});
		},
		getLineCoords: function(aircraft) {
			var coords = [];
			aircraft.points.forEach(function(point) {
				coords.push([point.lng, point.lat]);
			});
			return coords;
		},
		timerLoop: function() {
			if (this.optionLive) {
				this.loadTracks();
				if (this.selectedAircraft) this.selectAircraft(this.selectedAircraft);
			}
			this.timeoutTimer = setTimeout(this.timerLoop, 15000); // thirty seconds
		},
		follow: function() {
			var that = this;
			Vue.nextTick(function () {
				if (that.optionFollow) {
					that.map.panTo([that.selectedAircraft.points[0].lng, that.selectedAircraft.points[0].lat]);
				}
			});
		}
	}
}
</script>