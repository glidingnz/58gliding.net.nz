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

.marker_top {
	background-color: #A00;
	border-radius: 50%;
	width: 34px;
	height: 34px;
	box-shadow: 0px 0px 15px 0px rgba(0,0,0,0.3);
	padding-top: 2px;
	padding-left: 2px;
}
.marker_top_inner {
	width: 30px;
	height: 30px;
	border-radius: 50%;
	padding: 5px 0 2px 0;
	color: #FFF;
	font-size: 110%;
	font-weight: bold;
	text-align: center;
	position: absolute;
	z-index: 2;
}
.marker_pin {
	position: absolute;
	content: '';
	width: 0px;
	height: 0px;
	border: 10px solid transparent;
	border-top: 10px solid #A00;
	bottom: -17px;
	left: 7px;
}
.waypoint_dot {
	width: 8px;
	height: 8px;
	border-radius: 8px;
}

.waypoint_text {
	margin-top: -12px;
	margin-left: 13px;
}

/*.selectedMarker {  }*/
.selectedMarker .marker_top { 
	box-shadow: 0px 0px 15px 0px rgba(0,0,0,0.7);
}
.selectedMarker .marker_inner { 
	transform: scale(1.4) translate(0, -9px);
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
	max-width: 100%;
	margin-left: auto;
	margin-right: auto;
	flex-grow: 1;
	flex-wrap: wrap;
}

.selected-aircraft {
	border-top: 1px solid #888;
}
.selected-aircraft .detail {
	margin-left: 5px;
	margin-right: 5px;
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
.mapbox .dayDate {
	position: absolute;
	left: 10px;
	bottom: 35px;
	z-index: 11;
	font-size: 120%;
	font-weight: bold;
}
.tracking .options, 
.tracking .day-selector {
	padding: 10px;
	position: absolute;
	top: 50px;
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
.tracking h4 {
	font-size: 120%;
	color: #888;
}
.tracking .chart-container {
	margin-left: 0;
	margin-right: 0;
}
</style>

<template>
<div class="tracking" id="tracking">

	<div class="flex-vertical">

		<div class="maprow">

			<!-- <div class="mapbox">Map</div> -->
			<div class="mapbox" id="map">
				<div class="buttons">
					<button class="settings-button fa fa-cog btn btn-outline-dark" v-on:click="showPanel('showOptions')"></button>
					<button class="settings-button btn btn-outline-dark ml-2" v-on:click="showPanel('showDaySelector')">
						<span class="fa fa-calendar"></span>
					</button>
					<button class="settings-button fa fa-search-plus btn btn-outline-dark ml-2" v-on:click="showPanel('showZoomMenu')"></button>
					<button class="settings-button fa fa-route btn btn-outline-dark ml-2" v-on:click="showPanel('showTaskSelector')"></button>

					<div class="loading ml-2 mt-1" v-show="loading"><span class=" fas fa-sync fa-spin"></span> Loading...</div>
				</div>
				<div class="dayDate" v-if="flyingDay">{{formatDate(flyingDay)}}</div>

			</div>


			<div class="sidepanel" v-bind:class="[showLegend ? 'expanded' : '']">
				<table class="legend legend-header">
					<tr>
						<th v-show="!showLegend">
							<button class="fa fa-angle-double-left btn btn-xs btn-outline-dark ml-2 mt-1 pr-2 pl-2" v-if="!showLegend" v-on:click="showLegend=!showLegend" ></button>
						</th>
						<th v-show="showLegend" v-on:click="legendSort=['that.aircraft.legend']">Reg</th>
						<th v-show="showLegend" 
							v-on:click="legendShowAgl = !legendShowAgl; legendSort=['that.aircraft.points[0].alt']">
							<a href="javascript:void(0)" v-show="legendShowAgl">AGL</a>
							<a href="javascript:void(0)" v-show="!legendShowAgl">QNH</a>
						</th>
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
									{{craft.legend}}
								</div>
							</td>
							<td v-show="showLegend">
								<span v-if="legendShowAgl && craft.points[0].alt">
									<span v-if="craft.points[0].gl">
										{{formatAltitudeFeet(heightAgl(craft.points[0].alt, craft.points[0].gl))}}
									</span>
									<span v-if="!craft.points[0].gl">
										?
									</span>
								</span>
								<span v-if="!legendShowAgl && craft.points[0].alt">
									{{formatAltitudeFeet(craft.points[0].alt)}}
								</span>
								<span v-if="craft.points[0].alt==null">n/a</span>
								
							</td>
							<td v-show="showLegend">{{shortDateToNow(createDateFromMysql(craft.points[0].thetime))}}</td>
						</tr>
					</table>
					{{legendSort}}

				</div>
			</div>
		</div>

		<div class="selected-aircraft" v-if="selectedAircraft && selectedPoint">

			<div v-if="showAircraftDetails" class="flex-row">
				<div class="detail" v-if="selectedAircraft.aircraft">{{ selectedAircraft.aircraft.rego }}</div>
				<div class="detail" v-if="!selectedAircraft.rego">Unknown Aircraft</div>
				<div class="detail" v-if="selectedAircraft.aircraft">{{selectedAircraft.aircraft.contest_id}}</div>
				<div class="detail" v-if="selectedAircraft.aircraft">{{selectedAircraft.aircraft.model}}</div>
				<div class="detail" v-if="selectedAircraft.aircraft && selectedAircraft.aircraft.flarm">Flarm {{selectedAircraft.aircraft.flarm}}</div>
				<div class="detail" v-if="selectedAircraft.aircraft && selectedAircraft.aircraft.spot_esn">Spot {{selectedAircraft.aircraft.spot_esn}}</div>
			</div>

			<div class="flex-row">
				<div class="detail aircraft-badge" v-on:click="showAircraftDetails=!showAircraftDetails" v-bind:style="{backgroundColor: '#'+selectedAircraft.colour}">{{selectedAircraft.key}}</div>
				<div class="detail">
					<label for="follow"><input name="follow" id="follow" type="checkbox" v-on:click="follow()" v-model="optionFollow" :value="true"> Follow</label>
				</div>
				<div class="detail">{{formatAltitudeFeet(heightAgl(selectedPoint.alt, selectedPoint.gl))}} AGL</div>
				<div class="detail">{{formatAltitudeFeet(selectedPoint.alt)}} QNH</div>
				<div class="detail" v-if="selectedPoint.vspeed!=null">
					<span class="fa fa-arrow-up" v-show="selectedPoint.vspeed>0"></span>
					<span class="fa fa-arrow-down" v-show="selectedPoint.vspeed<0"></span>
					{{ Math.round(selectedPoint.vspeed * 1.944) }} kt
				</div>
				<div class="detail" v-if="selectedPoint.course!=null">
					{{selectedPoint.course}}&deg;
				</div>
				<div class="detail">{{shortDateToNow(createDateFromMysql(selectedPoint.thetime))}}</div>
				<div class="detail">{{formatType(selectedPoint.type)}}</div>
				<div class="detail">
					<button class="fa fa-map-marker btn-outline-dark btn btn-xs  ml-2 mt-1 pr-2 pl-2" v-on:click="showCoordDetails=!showCoordDetails" v-bind:class="[showCoordDetails ? 'active' : '']"></button>
				</div>
			</div>

			<div v-if="showCoordDetails" class="flex-row">
				<div class="detail">Lat {{ selectedPoint.lat.toFixed(5)}}</div>
				<div class="detail">Lng {{ selectedPoint.lng.toFixed(5) }}</div>
				<div class="detail"><a v-bind:href="'https://www.google.com/maps/place/' + selectedPoint.lat + '+' + selectedPoint.lng + '/'">Google Maps</a></div>
				<div class="detail"><a v-bind:href="'http://maps.apple.com/?q=' + selectedPoint.lat + ',' + selectedPoint.lng">Apple Maps</a></div>
			</div>
			<div v-if="showCoordDetails" class="flex-row">
				
				<altitude-chart :values="altitudes" @showpoint="showPoint" :height="100"></altitude-chart>  <!-- @showpoint="showPoint" @mouseout="mouseOut" @clickpoint="clickPoint" -->

			</div>



		</div>

	</div>



	<div class="day-selector" v-show="showDaySelector" v-if="days">

		<div class="list-group" >
			<button class="btn btn-outline-dark btn-sm mb-2" v-on:click="showDaySelector = !showDaySelector">Close</button>
			<a v-bind:href="'/tracking/' + flyingDay" class="btn btn-outline-dark btn-sm mb-2" >Go to Day URL</a>
		</div>

		<div class="list-group">
			<a v-on:click="selectDay(day.day_date)" v-for="(day, index) in days" class="list-group-item list-group-item-action" v-bind:class="[ day.day_date==flyingDay ? 'btn-secondary' : 'btn-outline-dark']">{{formatDate(day.day_date)}}
			</a>

			<div class="list-group-item">
				<div class="row">
					<input class="form-control col-8 mr-2" type="text" v-model="flyingDay" v-on:blur="selectDay(flyingDay)">
					<button class="btn btn-outline-dark col-3" v-on:click="selectDay(flyingDay)">Go</button>
				</div>
			</div>
		</div>
	</div>



	<div class="day-selector" v-show="showZoomMenu" v-if="sites">

		<div class="list-group" >
			<button class="btn btn-outline-dark btn-sm mb-2" v-on:click="showZoomMenu = !showZoomMenu">Close</button>
		</div>

		<div class="list-group" >
			<a v-bind:href="null" v-on:click="zoomTo(site.lat, site.lng, site.scale)" v-for="site in sites" class="list-group-item list-group-item-action">{{site.name}}</a>
		</div>
	</div>

	<div class="day-selector" v-show="showTaskSelector">
		
		<button class="btn btn-outline-dark btn-sm mb-2" v-on:click="showTaskSelector = !showTaskSelector">Close</button>

		<div v-if="contests">
			<div class="label">Choose a contest</div>
			<select class="form-control" name="contest" v-model="selectedContest">
				<option :value="null">Select a Contest</option>
				<option :value="contest" v-for="contest in contests">{{contest.name}}</option>
			</select>

			<div v-if="selectedContest && tasks">
				<div class="label">Tasks</div>
				<div v-if="loadingTasks"><span class="fa fa-spin"></span> Loading...</div>
				<select class="form-control" name="task" v-model="selectedTask" v-if="!loadingTasks">
					<option :value="null">Select a Task</option>
					<option :value="task" v-for="task in tasks"><span v-if="!task.class_name">{{task.class_type}}</span> {{task.class_name}} : Task {{task.task_number}} {{task.task_date}} {{task.task_name}}</option>
				</select>
			</div>

			<div v-if="selectedTask">
				<div class="label">
					Task Loaded
				</div>
				<div v-if="loadingTask"><span class="fa fa-spin"></span> Loading...</div>
				<div v-if="!loadingTask">
					<button class="btn btn-outline-dark btn-sm mr-2" v-on:click="zoomToTask()">Zoom to task</button>
					<button class="btn btn-outline-dark btn-sm mr-2"  v-on:click="clearCurrentTask()">Remove Task</button>
				</div>
			</div>
		</div>

		<div v-if="!contests">
			No contests available to choose a task
		</div>

	</div>


	<div class="options" v-show="showOptions">

		<button class="btn btn-outline-dark btn-sm float-right" v-on:click="showOptions = !showOptions">Close</button>

		<h4>Filters</h4>

		<div>
			<label for="showAll"><input type="radio" id="showAll" value="all" v-model="filterIsland"> All</label> &nbsp;
			<label for="showNorth"><input type="radio" id="showNorth" value="north" v-model="filterIsland"> North</label> &nbsp;
			<label for="showSouth"><input type="radio" id="showSouth" value="south" v-model="filterIsland"> South</label>
		</div>
	
		<div>
			<select name="fleet" v-if="fleets" v-model="selectedFleet">
				<option :value="null">Select a group of aircraft...</option>
				<option v-for="fleet in fleets" :value="fleet">{{fleet.name}}</option>
			</select>

			<a href="/fleets">Edit Aircraft Groups</a>
		</div>

		<hr>
		<h4>Options</h4>

			<div>
				<div class="btn-group mb-0" role="group">
					<button type="button" class="btn btn-sm mb-2" v-bind:class="[ currentStyle=='terrain' ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="changeStyle('terrain')">Terrain</button>
					<button type="button" class="btn btn-sm mb-2" v-bind:class="[ currentStyle=='satellite' ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="changeStyle('satellite')">Satellite</button>
					<button type="button" class="btn btn-sm mb-2" v-bind:class="[ currentStyle=='map' ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="changeStyle('map')">Fast</button>
					<button type="button" class="btn btn-sm mb-2" v-bind:class="[ currentStyle=='contours' ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="changeStyle('contours')">Topo</button>
				</div>
			</div>

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
	import MapboxCircle from 'mapbox-gl-circle';

	Vue.prototype.$moment = moment;

	export default {
		props: ['year','month','day'],
		mixins: [common],
		data: function() {
			return {
				loading: false,
				showOptions: false,
				showDaySelector: false,
				showZoomMenu: false,
				showTaskSelector: false,
				showLegend: true,
				showCoordDetails: false,
				showAircraftDetails: false,

				legendShowAgl: true,
				legendSort: ['that.aircraft.legend'],

				optionZoomToSelected: true,
				optionLive: true,
				optionFollow: false,
				selectedAircraft: null,
				selectedAircraftKey: null,
				selectedAircraftTrack: [], // all the track data
				selectedAircraftTrackGeoJson: [], // used by mapbox
				selectedMarker: null, // the larger marker on the map showing the selected point.
				selectedPoint: null, // the details of the currently selected point. Defaults to the last point received.
				//selectedAltitudes: [], // selected items altitude data for the chart
				flyingDay: null,
				'map': {},
				'nav': {},
				aircraft: [],
				days: [],

				// options to select a task
				contests: [],
				selectedContest: null,
				tasks: [],
				selectedTask: null,
				selectTaskCoords: [],
				selectedTaskData: [],
				loadingTasks: false,
				loadingTask: false,
				taskWaypoints: [],
				taskCircles: [],
				taskTrack: [],

				showTrails: false,
				filterIsland: 'all',
				filterUnknown: false,
				mapMarkers: [],
				mapFlying: false,
				fitBoundsStarted: false,

				currentStyle: 'terrain',
				mapStyles: {
					terrain: 'mapbox://styles/ipearx/ck49l0wrr0jqn1cmjarpcbmko',
					map: 'mapbox://styles/ipearx/ck49a1qq604ck1co7r9uwpi5k',
					satellite: 'mapbox://styles/ipearx/ck499vvka09bc1cn6bmofjh21',
					contours: 'mapbox://styles/ipearx/ck32s9fvj2k6s1cp2zih1vfim'
				},

				fleets: [], // the list of fleets available to select
				selectedFleet: null, // the currently selected fleet item in the select
				fleet: {}, // the actual fleet we'll filter, includes the list of aircraft
				sites: [
					{lat: -41.18301, lng: 174.0, scale: 5, name: "NZ"},
					{lat: -38.688, lng:  176.138, scale: 6, name: "North Island"},
					{lat: -37.11170, lng: 174.937, scale: 12, name: "Drury"},
					{lat: -37.29800, lng: 174.925, scale: 9, name: "Mercer"},
					{lat: -37.48444, lng: 175.511, scale: 9, name: "Swamp"},
					{lat: -37.73593, lng: 175.733, scale: 12, name: "Matamata"},
					{lat: -38.27730, lng: 175.863, scale: 9, name: "Tokoroa"},
					{lat: -38.688, lng:  176.138, scale: 12, name: "Centennial"},
					{lat: -38.688, lng:  176.138, scale: 9, name: "Taupo"},
					{lat: -39.30991, lng: 174.1413, scale: 10, name: "Taranaki"},
					{lat: -40.9724, lng: 175.632, scale: 12, name: "Hood"},
					{lat: -40.97435, lng: 175.409, scale: 9, name: "Wellington"},
					{lat: -41.09532, lng: 175.490, scale: 12, name: "Papawai"},
					{lat: -43.5547114, lng: 171.024, scale: 6, name: "South Island"},
					{lat: -43.38478, lng: 171.9054, scale: 10, name: "Springfield"},
					{lat: -44.48489, lng: 169.9809, scale: 12, name: "Omarama"},
					{lat: -44.51185, lng: 169.3208, scale: 8, name: "Otago"}
				]
			}
		},
		watch: {
			filterIsland: function() {
				this.loadTracks();
			},
			showLegend: function() {
				setTimeout(() => this.map.resize(), 20);
			},
			selectedFleet: function() {
				var that=this;
				if (this.selectedFleet) {
					window.axios.get('/api/v1/fleets/' + this.selectedFleet.id).then(function (response) {
						that.fleet = response.data.data;
					});
				} else {
					that.fleet = {};
				}
			},
			showTaskSelector: function() {
				// load contests if we haven't already when we open the panel
				if (this.contests.length==0) {
					this.loadContests();
				}
			},
			selectedContest: function() {
				this.tasks = [];
				this.selectedTask = null;
				this.selectedTaskData = null;

				// watch for a change to the selected contest
				if (this.selectedContest!=null) this.loadTasks();
			},
			selectedTask: function() {
				this.loadTask(this.selectedTask);
			}
		},
		computed: {
			filteredAircraft: function() {
				var that = this;
				console.log(that.aircraft);
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

					//check if NOT in the list of aircraft if we are filtering that way
					if (that.selectedFleet!=null && that.fleet.aircraft && that.fleet.aircraft.length>0) {
						var found=false;
						for(var i = 0; i < that.fleet.aircraft.length; i++) {
							if (that.fleet.aircraft[i].rego == 'ZK-' + craft.rego) {
								found=true;
								break;
							}
						}
						if (!found) return false;
					}

					return true;

				}), that.legendSort);
			},
			altitudes: function() {
				var that = this;
				var previous = 0;

				// create an array suitable for highcharts. Ensure it's in ascending order.
				var selectedAltitudes = that.selectedAircraftTrack.slice().reverse().map(function(point) {
					var alt = Math.round(point.alt * 3.28084);
					if (point.alt==null) alt=null;
					var newPoint = [that.createDateFromMysql(point.thetime).getTime(), alt];
					return newPoint;
				});

				// ensure NULL values are previous
				var previous=0;
				for (var i=0; i<selectedAltitudes.length; i++) {
					if (selectedAltitudes[i][1]==null) {
						selectedAltitudes[i][1]=previous;
						//that.noAltBands.push(selectedAltitudes[i][0]);
					} else {
						previous=selectedAltitudes[i][1];
					}
				}

				return selectedAltitudes;
			}
			
		},
	mounted: function() {
		var that = this;
		mapboxgl.accessToken = 'pk.eyJ1IjoiaXBlYXJ4IiwiYSI6ImNqd2c1dnU3bjFoMmg0NHBzbG9vbmQwbGkifQ.HeNPRpXBkpmC_ljY7QQTRA';
		this.map = new mapboxgl.Map({
			container: 'map',
			style: that.mapStyles[that.currentStyle],
			//style:  'http://maps.gliding.net.nz:8080/styles/positron/style.json',
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
		that.map.on('style.load', function () {
			console.log('style loaded');


			// Triggered when `setStyle` is called.
			if (that.selectedTask) that.drawTask();

			if (that.selectedAircraft) that.createSelectedTrack();
			if (that.aircraft) that.createTracks();
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

		// load the list of aircraft filters
		this.loadFleets();

		// fix bug with window resizing
		window.onresize = _.debounce(() => {
			that.map.resize();
		}, 100)

	},
	methods: {
		selectDay: function(day_date) {
			this.flyingDay = day_date;
			this.loadTracks();
			this.showDaySelector = false;
		},
		showPanel: function(name) {

			var current_state = this[name];

			// first close the other panels
			this.showOptions = false;
			this.showDaySelector = false;
			this.showZoomMenu = false;
			this.showTaskSelector = false;

			// open it if it's closed
			if (current_state==false) this[name]=true;
		},
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
		loadContests: function() {
			var that = this;
			this.loading=true;
			window.axios.get('/api/events/?soaringspot=1').then(function (response) {

				that.contests = response.data.data;
				that.loading=false;
			});
		},
		loadTasks: function() {
			var that = this;
			this.loading=true;
			this.loadingTasks = true;
			window.axios.get('/api/events/' + this.selectedContest.id + '/soaringspot/tasks').then(function (response) {

				that.tasks = response.data.data;
				that.loading=false;
				that.loadingTasks = false;
			});
		},
		loadTask: function(task) {
			var that = this;
			this.loading=true;
			this.loadingTask = true;

			// extract the task ID from the task URL
			var task_url = task._links.self.href.split('/');
			var task_id = task_url[task_url.length-1];

			window.axios.get('/api/events/' + this.selectedContest.id + '/soaringspot/tasks/' + task_id).then(function (response) {

				that.selectedTaskData = response.data.data;
				that.loading=false;
				that.loadingTask = false;

				that.drawTask();
				that.zoomToTask();

			});
		},
		loadTracks: function() {
			this.loading=true;

			var pings = 5;
			//if (this.showTrails==false) pings=3;
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

			this.selectedAircraft = aircraft;

			// check if we already have some data
			if (this.selectedAircraftKey==this.selectedAircraft.key) {
				// get the last point retreived
				from = this.selectedAircraftTrack[0].thetime;
			} else {
				that.selectedAircraftTrack = [];
			}

			this.selectedAircraftKey=this.selectedAircraft.key;
			this.loading=true;

			// load the data
			window.axios.get('/api/v2/tracking/' + that.flyingDay + '/aircraft/' + aircraft.key + '?from=' + from).then(function (response) {

				that.loading=false;
				var newData = response.data.data.points;

				// setup the geojson object if it hasn't been yet
				if (from==0) {
					that.selectedAircraftTrackGeoJson = {
						'type': 'FeatureCollection',
						'features': [{
							'type': 'Feature',
							'properties': {
								'color': '#' + that.selectedAircraft.colour
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

					// while we are here, add the unix time
					point.unixtime = that.createDateFromMysql(point.thetime).getTime();

					that.selectedAircraftTrackGeoJson.features[0].geometry.coordinates.unshift([point.lng, point.lat]);
					that.selectedAircraftTrack.unshift(point);
				}

				if (from==0) {
					// create a new track
					that.createSelectedTrack();
					// create a new marker
					that.createSelectedMarker();
				} else {
					// update the existing track
					that.map.getSource('selectedTrack').setData(that.selectedAircraftTrackGeoJson);
					that.selectedMarker.setLngLat([that.selectedAircraftTrack[0].lng, that.selectedAircraftTrack[0].lat]);
				}


				// set the selected point to the latest point given
				that.selectedPoint = that.selectedAircraftTrack[0];

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
		createSelectedMarker() {
			var that = this;

			// if an aircraft has never been selected, return
			if (that.selectedAircraftTrack.length==0) return false;

			// delete existing marker  if it exists
			if (this.selectedMarker) this.selectedMarker.remove();

			var aircraft = this.selectedAircraft;
			var el = that.createMarkerDom(aircraft.legend, aircraft.colour, 'selectedMarker');
			this.selectedMarker = new mapboxgl.Marker(el, {
					anchor: 'bottom',
					offset: [0, -5]
				})
				.setLngLat([that.selectedAircraftTrack[0].lng, that.selectedAircraftTrack[0].lat])
				.addTo(that.map);
		},
		createMarkers() {
			var that = this;

			that.filteredAircraft.forEach(function (aircraft) {

				if (typeof aircraft.points=='undefined') return false;

				var el = that.createMarkerDom(aircraft.legend, aircraft.colour, 'aircraftMarker');
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

				that.createSelectedMarker();
			});
		},
		createMarkerDom: function(label, colour, className) {
			var el = document.createElement('div');
			var el2 = document.createElement('div');
			var pingTop = document.createElement('div');
			var pingTopInner = document.createElement('div');
			var pinBottom = document.createElement('div');
			el.className = className;
			el.appendChild(el2);
			el2.className = 'marker_inner';
			pingTop.className = 'marker_top';
			pingTopInner.className = 'marker_top_inner';
			pinBottom.className = 'marker_pin';
			pingTop.appendChild(pingTopInner);
			el2.appendChild(pingTop);
			el2.appendChild(pinBottom);
			pingTopInner.appendChild(document.createTextNode(label));
			pinBottom.style.borderTopColor = '#'+colour;
			pingTop.style.backgroundColor = '#'+colour;
			return el;
		},
		createSelectedTrack() {
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
		},
		loadFleets: function() {
			var that=this;
			window.axios.get('/api/v1/fleets').then(function (response) {
				that.fleets = response.data.data;
			});
		},
		showPoint: function(object) {
			const point = this.selectedAircraftTrack.find( point => point.unixtime == object.x );
			this.selectedPoint = point; // update the selected point details
			this.selectedMarker.setLngLat([point.lng, point.lat]);
		},
		zoomTo: function(lat, lng, scale) {
			this.map.flyTo({
				center: [lng, lat],
					zoom: scale,
			});
		},
		drawTask: function() {
			var that = this;
			if (!this.selectedTaskData) return false;

			this.clearCurrentTask();

			// create the waypoints
			this.selectedTaskData.forEach(function(point, index) {
				var waypoint = that.createWaypointDom(point.name, '000000');

				var marker = new mapboxgl.Marker(waypoint, {
						anchor: 'left',
						offset: [-3, 4]
					})
					.setLngLat([point.lng, point.lat])
					.addTo(that.map);
				that.taskWaypoints.push(marker);

				var myCircle = new MapboxCircle({lat: point.lat, lng: point.lng}, point.oz_radius1, {
					editable: false,
					minRadius: 1500,
					fillColor: '#E3652B',
					strokeColor: '#E3652B',
					strokeWeight: 3,
					fillOpacity: 0.15,
				}).addTo(that.map);
				that.taskWaypoints.push(myCircle);

				that.selectTaskCoords.push([point.lng, point.lat]);
			});

			that.map.addLayer({
				"id": "taskLine",
				"type": "line",
				"source": {
					"type": "geojson",
					"data": {
						"type": "Feature",
						"properties": {},
						"geometry": {
							"type": "LineString",
							"coordinates":  that.selectTaskCoords
						}
					}
				},
				"layout": {
					"line-join": "round",
					"line-cap": "round"
				},
				"paint": {
					"line-color": "#E3652B",
					"line-width": 4
				}
			});

		},
		zoomToTask: function() {
			var coords = this.selectTaskCoords;
			var bounds = coords.reduce(function(bounds, coord) {
				return bounds.extend(coord);
			}, new mapboxgl.LngLatBounds(coords[0], coords[0]));
			this.map.fitBounds(bounds, {
				padding: 40
			});
			this.showTaskSelector = false;
			this.optionZoomToSelected = false;
		},
		clearCurrentTask: function() {
			var that = this;
			// drop all existing markers and lines
			for (var i=0; i<that.taskWaypoints.length; i++) {
				that.taskWaypoints[i].remove();
			}
			that.taskWaypoints=[];
			var taskLineLayer = that.map.getLayer('taskLine');
			if (typeof taskLineLayer !== 'undefined') {
				that.map.removeLayer('taskLine');
				that.map.removeSource('taskLine');
			}
			that.selectTaskCoords = [];
			this.showTaskSelector = false;
		},
		createWaypointDom: function(label, colour) {
			var el = document.createElement('div');
			var el2 = document.createElement('div');
			var el3 = document.createElement('div');
			el.appendChild(el2);
			el.appendChild(el3);
			el.className = 'waypoint';
			el2.className = 'waypoint_dot';
			el3.className = 'waypoint_text';
			el3.appendChild(document.createTextNode(label));
			el2.style.backgroundColor = '#'+colour;
			return el;
		},
		changeStyle: function(style) {
			this.clearCurrentTask();
			this.currentStyle = style;
			this.map.setStyle(this.mapStyles[style]);
		}

	}
}
</script>