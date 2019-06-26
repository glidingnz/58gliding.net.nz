

<style>
	tr.selected td {
		background-color: #CCC;
	}
	.fullscreen {
		width: 100%;
		height: 100% !important;
		position: fixed;
		top: 0; left: 0;
		z-index: 998;
	}
	.fullScreenTable {
		position: fixed;
		z-index: 999;
		top: 50px; right: 10px;
		width: 20em;
		max-height: 80%;
		overflow: auto;
	}
	.fullScreenTableCollapsed {
		width: 3em;
		height: 2em;
	}
	.fullScreenTable table {
		background-color: #FFF;
		border: 1px solid #CCC;
		margin-bottom: 0;
	}
	.toggleLegend {
		position: absolute;
		top: 12px; right: 110px;
		z-index: 999;
	}
	.exitFullScreen {
		position: absolute;
		z-index: 999;
		top: 10px; left: 20px;
	}
	.maps {
		width: 100%; 
		height: 600px;
		min-height: 400px;
		height: calc(100vh - 220px);
	}
	tr.old td {
		color: #999;
	}

</style>

<template>
<div>

	<div class="form-inline">
		<button class="btn btn-sm btn-outline-dark mr-1 mb-1" v-on:click="zoomTo(-41.18301,174.0, 8)">NZ</button>
		<button class="btn btn-sm btn-outline-dark mr-1 mb-1" v-on:click="zoomTo(-38.688, 176.138, 4)">NI</button>
		<button class="btn btn-sm btn-outline-dark mr-1 mb-1" v-on:click="zoomTo(-37.11170,174.937, .1)">Drury</button>
		<button class="btn btn-sm btn-outline-dark mr-1 mb-1" v-on:click="zoomTo(-37.29800,174.925, .4)">Mercer</button>
		<button class="btn btn-sm btn-outline-dark mr-1 mb-1" v-on:click="zoomTo(-37.48444,175.511, .5)">Swamp</button>
		<button class="btn btn-sm btn-outline-dark mr-1 mb-1" v-on:click="zoomTo(-37.73593,175.733, .1)">Matamata</button>
		<button class="btn btn-sm btn-outline-dark mr-1 mb-1" v-on:click="zoomTo(-38.27730,175.863, .5)">Tokoroa</button>
		<button class="btn btn-sm btn-outline-dark mr-1 mb-1" v-on:click="zoomTo(-38.688, 176.138, .1)">Centennial</button>
		<button class="btn btn-sm btn-outline-dark mr-1 mb-1" v-on:click="zoomTo(-38.688, 176.138, .5)">Taupo</button>
		<button class="btn btn-sm btn-outline-dark mr-1 mb-1" v-on:click="zoomTo(-39.30991,174.1413, .3)">Taranaki</button>
		<button class="btn btn-sm btn-outline-dark mr-1 mb-1" v-on:click="zoomTo(-40.9724,175.632, .1)">Hood</button>
		<button class="btn btn-sm btn-outline-dark mr-1 mb-1" v-on:click="zoomTo(-40.97435,175.409, .6)">Wellington</button>
		<button class="btn btn-sm btn-outline-dark mr-1 mb-1" v-on:click="zoomTo(-41.09532,175.490, .1)">Papawai</button>
		<button class="btn btn-sm btn-outline-dark mr-1 mb-1" v-on:click="zoomTo(-43.5547114,171.024, 4)">SI</button>
		<button class="btn btn-sm btn-outline-dark mr-1 mb-1" v-on:click="zoomTo(-43.38478,171.9054, .1)">Springfield</button>
		<button class="btn btn-sm btn-outline-dark mr-1 mb-1" v-on:click="zoomTo(-44.48489,169.9809, .1)">Omarama</button>
		<button class="btn btn-sm btn-outline-dark mr-1 mb-1" v-on:click="zoomTo(-44.51185,169.3208, 1)">Otago</button>
	</div>

	<div v-show="mapStatus=='loader'">
		Loading...
	</div>

	<div class="row mt-1" v-show="mapStatus=='map'">
		<div class="col-md-9">
			<div id="map" class="maps"  v-bind:class="[  fullScreen ? 'fullscreen' : '']">
				<button class="exitFullScreen btn btn-secondary btn-sm" v-on:click="toggleFullScreen">Full Screen</button>

				<button v-show="fullScreen || collapseLegend" class="toggleLegend fa btn btn-sm btn-secondary" v-on:click="collapseLegend=!collapseLegend" v-bind:class="[  collapseLegend ? 'fa-angle-down' : 'fa-angle-up']" style="pointer-events: auto;">&nbsp;<span v-show="!collapseLegend">Hide</span><span v-show="collapseLegend">Show</span> Legend</button>
			</div>


			<div class="form-inline mt-2">
				
				<div class="form-group" style="float: right;">

					<div class="checkbox mr-2"><label for="showTrails" class="form-check-label"><input id="showTrails" type="checkbox"  v-model="showTrails" class="form-check-input"> Long Trails</label></div>

					<div class="checkbox mr-2"><label for="live-update" class="form-check-label"><input id="live-update" type="checkbox"  v-on:click="toggleLive()" class="form-check-input" v-model="liveLoading"> Live</label></div>

					<div class="checkbox mr-2"><label for="follow" class="form-check-label"><input id="follow" type="checkbox"  v-model="followSelected" class="form-check-input"> Follow Selected</label></div>
					<div class="checkbox mr-2"><label for="zoomselected" class="form-check-label"><input id="zoomselected" type="checkbox"  v-model="zoomSelected" class="form-check-input"> Zoom to Selected</label></div>
				
				</div>

				<div class="ml-auto">
					Day:
					<div class="btn-group" role="group" style="margin-bottom: 0;">
						<a v-bind:href="'/tracking/' + day.day_date"  v-for="(day, index) in firstDays" class="btn" v-bind:class="[ day.day_date==flyingDay ? 'btn-secondary' : 'btn-outline-dark']">{{day.day_date}}
						</a>
					</div>
				</div>
			</div>

		</div>
		<div class="col-md-3">
			
			<table class="table table-striped table-sm" v-if="selectedAircraft">
				<tr>
					<th>Aircraft</th>
					<th>
						<div v-if="selectedAircraft.rego">{{ selectedAircraft.rego + ' / ' + selectedAircraft.contest_id }} / {{selectedAircraft.model}}</div>
						<div v-if="!selectedAircraft.rego">Unkown</div>
					</th>
				</tr>
				<tr>
					<td>Altitude</td>
					<td>
						<div v-if="selectedPoint.altfeet!=null">
							{{ selectedPoint.altfeet }} ft / {{ selectedPoint.altmeters }} m / {{ selectedPoint.aglfeet }} ft AGL
						</div>
						<div v-if="selectedPoint.altfeet==null">n/a</div>
					</td>
				</tr>
				<tr>
					<td>Speed</td>
					<td>
						<div><span v-if="selectedPoint.speed!=null">{{ Math.round(selectedPoint.speed / 1.852) }} knots / {{ selectedPoint.speed }} km/h</span><span v-if="selectedPoint.course!=null"> @ {{selectedPoint.course}}&deg;</span></div>
						<div v-if="selectedPoint.speed==null">n/a</div>
						
					</td>
				</tr>
				<tr>
					<td>V. Speed</td>
					<td>
						<div v-if="selectedPoint.vspeed!=null">{{ Math.round(selectedPoint.vspeed * 1.944) }} knots / {{ selectedPoint.vspeed }} m/s</div>
						<div v-if="selectedPoint.vspeed==null">n/a</div>
					</td>
				</tr>
				<tr>
					<td>Flarm</td>
					<td>{{ selectedAircraft.flarm }}</td>
				</tr>
				<tr>
					<td>Last Seen</td>
					<td>
						{{ lastSeenTime(selectedPoint.thetime) }}
						<span v-if="selectedPoint.type==1">FLARM</span>
						<span v-if="selectedPoint.type==2">SPOT (US)</span>
						<span v-if="selectedPoint.type==3">CELL (Particle.io)</span>
						<span v-if="selectedPoint.type==4">CELL (Overland)</span>
						<span v-if="selectedPoint.type==5">SPOT (NZ)</span>
						<span v-if="selectedPoint.type==6">InReach (NZ)</span>
						<span v-if="selectedPoint.type==7">CELL (Btraced)</span>
						<span v-if="selectedPoint.type==8">Gliding Ops</span>
					</td>
				</tr>
				<tr>
					<td>Lat/Long</td>
					<td>{{ selectedPoint.lat.toFixed(5)}} &nbsp; {{ selectedPoint.lng.toFixed(5) }}</td>
				</tr>
				<tr>
					<td>Links</td>
					<td>
						<a v-bind:href="'https://www.google.com/maps/place/' + selectedPoint.lat + '+' + selectedPoint.lng + '/'">Google</a>
						&nbsp; <a v-bind:href="'http://maps.apple.com/?q=' + selectedPoint.lat + ',' + selectedPoint.lng">Apple</a> &nbsp; 
						<a v-if="selectedAircraft.rego" v-bind:href="'/tracking/' + this.flyingDay + '/' + selectedAircraft.rego">Analyse</a>
						<a v-if="!selectedAircraft.rego" v-bind:href="'/tracking/' + this.flyingDay + '/' + selectedAircraft.hex">Analyse</a>
					</td>
				</tr>
			</table>

			<div v-bind:class="[  fullScreen ? 'fullScreenTable' : '', collapseLegend ? 'fullScreenTableCollapsed' : '']">

				<table class="table table-striped table-sm" v-show="!collapseLegend" style="pointer-events: auto;">
					<tr>
						<td colspan="2">Filter</td>
						<td colspan="2">
							<label for="showAll"><input type="radio" id="showAll" value="all" v-model="filterIsland"> All</label> &nbsp;
							<label for="showNorth"><input type="radio" id="showNorth" value="north" v-model="filterIsland"> North</label> &nbsp;
							<label for="showSouth"><input type="radio" id="showSouth" value="south" v-model="filterIsland"> South</label>
							<br>
							<label for="showUnknown"><input type="checkbox" value="true" v-model="filterUnknown" id="showUnknown"> Hide Unknown</label>
						</td>
					</tr>
					<tr>
						<th></th>
						<th><a v-on:click="sortLegendBy('rego')">Rego</a></th>
						<th>
							<a v-on:click="sortLegendBy('alt')" v-show="!legendShowAGL">Alt</a>
							<a v-on:click="sortLegendBy('agl')" v-show="legendShowAGL">Alt</a>
							(<a v-on:click="legendShowAGL=!legendShowAGL" v-show="!legendShowAGL">QNH</a><a v-on:click="legendShowAGL=!legendShowAGL" v-show="legendShowAGL">AGL</a>)
						</th>
						<th><a v-on:click="sortLegendBy('nzdate')">Last Seen</a></th>
					</tr>
					<tr  v-on:click="clickAircraftInLegend(aircraft.hex)" v-for="aircraft in aircrafts" v-bind:class="[ Date.now()-aircraft.nzdate>=1800000 ? 'old' : '', selectedAircraft && aircraft.hex==selectedAircraft.hex ? 'selected' : '' ]" v-if="legendFilter(aircraft)">
						<td style="width: 20px;">
							<div v-bind:style="{backgroundColor: '#' + aircraft.colour}" style="width: 20px; height: 20px;"></div>
						</td>
						<td>
							<div v-if="!aircraft.contest_id">{{aircraft.hex}}</div>
							<div v-if="aircraft.contest_id">{{aircraft.contest_id}}</div>
						</td>
						<td>
							<div v-if="aircraft.alt!=null">
								<div v-show="legendShowAGL">{{aircraft.agl}} ft</div>
								<div v-show="!legendShowAGL">{{aircraft.alt}} ft</div>
							</div>
							<div v-if="aircraft.alt==null">n/a</div>
						</td>
						<td>
							<div v-if="aircraft.nzdate!=0" v-bind:alt="aircraft.nzdate">
								{{aircraft.timeago}}
							</div>
							<div v-if="aircraft.nzdate==0">n/a</div>
						</td>
					</tr>
					<tr v-if="!fullScreen">
						<td colspan="4">
							Missing your aircraft/rego? Set up tracking in the <a href="/aircraft">aircraft database</a>
						</td>
					</tr>
				</table>
			</div>


		</div>
	</div>


</div>
</template>

<script>
	import common from '../mixins.js';
	import moment from 'moment';
	Vue.prototype.$moment = moment;

	export default {
		props: ['year','month','day'],
		mixins: [common],
		data: function() {
			return {
				mapkit: {}, // the instance of mapkit
				map: {}, // the mapkit map
				liveLoading: false,
				mapStatus: 'loader',
				dateToJumpTo: null,
				flyingDay: null,
				fullScreen: false,
				showTrails: false,
				legendShowAGL: true,
				legendShowTimeAgo: true,
				selectedAircraft: null,
				selectedPoint: null,
				followSelected: false, // whether to keep the display centered on the selected aircraft
				zoomSelected: true, // whether to zoom to the selected aircraft track when selected
				collapseLegend: false, // whether to collapse the legend when in full screen
				filterIsland: 'all',
				filterUnknown: false,
				days: [],
				hexes: [], // array of hex codes for the aircraft for today
				aircrafts: {}, // the list of aircraft details
				annotations: {}, // the list of aircraft location annotations on a map
				annotationsArray: [],
				activeAircraftTrackOverlay: null, // individual aircraft overlays, that can be toggled on and off
				polylines: {}, // the list of aircraft track polylines on a map
				lastAircraftLocations: {},
				sortBy: 'rego',
				sortOrder: 0,

				colors: ['e86666', 'ab4b4b', 'e87766', 'ba6052', '8c483e', 'e88966', 'ba6e52', '8c533e', 'e89a66', 'ab714b', 'c99559', '9c7344', 'c9a459', '8c723e', 'e8ce66', '9c8a44', 'aba44b', 'dfe866', 'b3ba52', '878c3e', 'b0d95f', '8aba52', '739c44', '9ae866', '68c959', '58ab4b', '66e866', '3e8c3e', '66e889', '4bab65', '3e8c5d', '66e8ab', '52ba8a', '5fd9b0', '449c7f', '59c9b3', '66e8df', '52bab3', '3e8c87', '5fd1d9', '4ba4ab', '66cee8', '52a5ba', '3e7d8c', '5fb0d9', '4b8bab', '3e728c', '66abe8', '44739c', '669ae8', '44679c', '6689e8', '526eba', '3e538c', '6677e8', '44509c', '6052ba', '483e8c', '805fd9', '67449c', 'ab66e8', '8a52ba', 'ce66e8', 'ab4ba4', '8c3e87', 'e866ce', 'c959a4', '9c447f', 'e8669a', 'ba527c', 'e86689', '9c445c', 'e86677', 'ab4b58']
			}
		},
	created: function() {

		// Add the script to the window object
		var script = document.createElement('script');
		script.type = 'text/javascript';
		script.src = "https://cdn.apple-mapkit.com/mk/5.x.x/mapkit.js";
		document.body.appendChild(script);
		script.onload = () => {
			this.loadMap();
		}
		// start the timer
		this.timeoutTimer = setTimeout(this.timerLoop, 3000);
	},
	events: {
	},
	computed: {
		firstDays: function() {
			// return the first 5 days available
			var niceDays = this.days.slice(0, 5);
			return niceDays;
		},
		apple_auth: function () { return window.Laravel.apple_auth; },
	},
	methods: {
		legendFilter: function(item) {
			if (this.filterIsland=='north') {
				if (item.lng<172.5270994) return false;
			}
			if (this.filterIsland=='south') {
				if (item.lng>174.8282816) return false;
			}
			if (this.filterUnknown) {
				if (item.rego=='') return false;
			}
			return true;
		},
		sortLegendBy: function(name) {
			this.sortBy = name;
			this.sortOrder >= 0 ? this.sortOrder=-1 : this.sortOrder=0;
		},
		sortOldAircraft: function(a, b) {
			return ((Date.now()-a.$value.nzdate) - (Date.now()-b.$value.nzdate));
		},
		toggleFullScreen: function() {
			if (this.fullScreen==true) this.fullScreen=false; else this.fullScreen=true;
		},
		timerLoop: function() {
			this.liveLoading = true;
			this.loadTracks();
			if (this.selectedAircraft) this.selectAircraft(this.selectedAircraft.hex, false);

			this.timeoutTimer = setTimeout(this.timerLoop, 30000); // ten seconds
		},
		toggleLive : function() {
			if (this.liveLoading==false) {
				clearTimeout(this.timeoutTimer);
			} else {
				// start the timer
				this.timeoutTimer = setTimeout(this.timerLoop, 30000);
			}
		},
		loadMap: function () {
			this.mapkit = mapkit;
			var that = this;
			this.mapkit.init({ authorizationCallback: function(done) {
				done(that.apple_auth);
			}});
			this.map = new this.mapkit.Map("map", { 
				center: new mapkit.Coordinate(-41.18301,174.0),
				isRotationEnabled: false,
				showsMapTypeControl: true,
				showsUserLocation: true
			});
			this.mapStatus = 'map';
			this.map.addEventListener("select", function(e) {
				var hex = e.annotation.data.hex;
				that.selectedAircraft = that.aircrafts[e.annotation.data.hex];
				that.selectedPoint = e.annotation.data;
				that.selectAircraft(hex, true);
			});
			this.map.addEventListener("deselect", function(e) {
				that.selectedAircraft = null;
				that.selectedPoint = null;

				if (that.activeAircraftTrackOverlay!=null) {
					that.map.removeOverlay(that.activeAircraftTrackOverlay);
				}
			});
			this.mapkit.addEventListener("configuration-change", function(event) {
				switch (event.status) {
					case "Initialized":
					// Mapkit was initialized and configured.
					that.loadDays();
					// that.zoomTo(-41.18301,174.0, 8);
				break;
				}
			});
		},
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

		loadAircraftDetails: function() {
			var that = this;
			window.axios.post('/api/v1/aircraft/hexes', {'hexes': this.hexes}).then(function (response) {
				
				for (var i in response.data.data) {
					var aircraft = response.data.data[i];

					// check if we are dealing with a glider rego or flarm hex code
					var regoname = aircraft.rego.replace(/ZK-/g, '');

					if (typeof(that.aircrafts[regoname])!=='undefined') {
						// it's a glider rego, merge with that
						Object.assign(that.aircrafts[regoname], aircraft);
						that.annotations[regoname].glyphText = aircraft.contest_id;
					} else {
						// it's a hex code, merge with that
						Object.assign(that.aircrafts[aircraft.flarm], aircraft);
						// update annotations on the map
						that.annotations[aircraft.flarm].glyphText = aircraft.contest_id;
					}

 				}
			});
		},


		splitTracksByAircraft: function(tracks) {
			var points = [];
			var currentAircraft = '';
			var aircraftTracks = [];

			// clear the list of hexes
			this.hexes = [];
			this.aircrafts = {};

			// loop through each individual point
			for (var i=0; i<tracks.length; i++) {
				// check if we have a new aircraft
				if (tracks[i].hex!=currentAircraft) {
					// add to the list of hexes
					this.hexes.push(tracks[i].hex);

					// get colour
					var col = parseInt(tracks[i].hex, 16);
					if (!col) col = parseInt(tracks[i].hex, 36);

					// setup the list of aircraft (i.e. legend)
					let aircraft = {
						hex: tracks[i].hex,
						flarm: tracks[i].hex,
						lat: tracks[i].lat,
						lng: tracks[i].lng,
						colour: this.colors[col % this.colors.length],
						rego: '',
						contest_id: ''
					}
					Vue.set(this.aircrafts, tracks[i].hex, aircraft);

					// save into the list of aircraft
					if (points.length>0) {
						aircraftTracks.push(points);
						points = [];
					}
					currentAircraft = tracks[i].hex;
				} 
				points.push(tracks[i]);
			}

			// save the last set of points into a track
			if (points.length>0) {
				aircraftTracks.push(points);
			}

			return aircraftTracks;
		},

		// create or update annotations
		createAnnotation: function(track) {
			var calloutDelegate = {
				calloutContentForAnnotation: function(annotation) {
					var element = document.createElement("div");
					element.className = "review-callout-content";
					var title = element.appendChild(document.createElement("div"));
					title.textContent = annotation.glyphText;
					var subtitle = element.appendChild(document.createElement("div"));
					subtitle.textContent = annotation.subtitle;
					// Add more content.
					return element;
				}
			}

			// use the first item from the given track
			let coordinate = new this.mapkit.Coordinate(track[0].lat, track[0].lng);

			// altitudes are null unless actually provided by device
			var altfeet = null;
			var glfeet = null;
			var aglfeet = null;
			if (track[0].alt!=null) {
				altfeet = Math.round(track[0].alt * 3.28084);
				glfeet = Math.round(track[0].gl * 3.28084);
				aglfeet = altfeet - glfeet;
				if (aglfeet<0) aglfeet=0;
			}
			var nzdate = this.createDateFromMysql(track[0].thetime);

			// update the altitude and timein the legend with the latest height
			this.aircrafts[track[0].hex].alt = altfeet;
			this.aircrafts[track[0].hex].gl = glfeet;
			this.aircrafts[track[0].hex].agl = aglfeet;
			this.aircrafts[track[0].hex].nzdate = nzdate;
			this.aircrafts[track[0].hex].timeago = moment(nzdate).fromNow();

			var title = '';
			if (track[0].alt!=null)  title = altfeet + ' ft, ';
			title = title + this.lastSeenTime(nzdate);

			var options = {
				title: title,
				subtitle: track[0].hex,
				calloutEnabled: true,
				glyphText: track[0].hex,
				// callout: calloutDelegate,
				data: {
					hex : track[0].hex, 
					lat: track[0].lat, 
					lng: track[0].lng, 
					altfeet: altfeet, 
					course: track[0].course, 
					gl: glfeet, 
					aglfeet: aglfeet,
					speed: track[0].speed, 
					vspeed: track[0].vspeed, 
					altmeters: track[0].alt, 
					thetime: nzdate,
					type: track[0].type
				}
			}

			// check if we are creating or updating
			if (typeof this.annotations[track[0].hex] == 'undefined') 
			{
				this.annotations[track[0].hex] = new this.mapkit.MarkerAnnotation(coordinate, options);
				this.annotationsArray.push(this.annotations[track[0].hex]);
				this.map.addAnnotation(this.annotations[track[0].hex]);

			} else {

				// update the existing annotation
				this.annotations[track[0].hex].title = title;
				this.annotations[track[0].hex].subtitle = track[0].hex;
				this.annotations[track[0].hex].coordinate = coordinate;
				this.annotations[track[0].hex].data = options.data;

				// update the selected point, only if this is the right one
				if (this.selectedPoint && this.selectedPoint.hex==track[0].hex) {
					this.selectedPoint = this.annotations[track[0].hex].data;
				}
			}
		},

		createPolyline: function(track) {

			var coords = track.map(function(point) {
				return new mapkit.Coordinate(point.lat, point.lng);
			});

			var style = new mapkit.Style({
				lineWidth: 3,
				lineJoin: "round",
				lineDash: [2, 5],
				strokeColor: "#" + this.aircrafts[track[0].hex].colour
			});

			// check if we need to remove the old one first
			if (typeof this.polylines[track[0].hex]!='undefined') {
				this.map.removeOverlay(this.polylines[track[0].hex]);
				delete this.polylines[track[0].hex];
			}

			var polyline = new mapkit.PolylineOverlay(coords, { style: style });
			this.map.addOverlay(polyline);

			// save this into the list of aircraft
			this.polylines[track[0].hex] = polyline;
			

		},

		loadTracks: function() {
			var pings = 25;
			if (this.showTrails==false) pings=2;
			var that = this;

			window.axios.get('/api/v1/tracking/' + that.flyingDay + '/pings/' + pings).then(function (response) {

				// Process the track data to split up by aircraft
				var aircraftTracks = that.splitTracksByAircraft(response.data.data);

				// set up the annotations
				aircraftTracks.map(that.createAnnotation);

				// zoom to the extents, only if not 'live'
				if (!that.liveLoading) that.map.showItems(that.annotationsArray);

				// follow if an item is selected and the option is set
				if (that.liveLoading && that.followSelected && that.selectedPoint) {
					that.centerOn(that.selectedPoint.lat, that.selectedPoint.lng)
				} 

				// draw the track lines
				aircraftTracks.map(that.createPolyline);

				// load the aircraft details from the hex codes that have been collected
				that.loadAircraftDetails();

			});
		},
		zoomTo: function(lat, long, zoom) {
			var coordinate = new mapkit.Coordinate(lat, long); // latitude, longitude
			var span = new mapkit.CoordinateSpan(zoom, zoom); // latitude delta, longitude delta
			var region = new mapkit.CoordinateRegion(coordinate, span);
			this.map.setRegionAnimated(region, true);
		},
		centerOn: function(lat, long) {
			var coordinate = new mapkit.Coordinate(lat, long);
			this.map.setCenterAnimated(coordinate, true);
		},
		lastSeenTime: function(datetime) {
			var options = {  hour: '2-digit', minute: '2-digit' };
			return datetime.toLocaleString("en-NZ", options);
		},
		clickAircraftInLegend: function(hex) {
			this.annotations[hex].selected=true;
		},
		selectAircraft: function(hex, zoom) {
			var that=this;
			// load aircraft track
			window.axios.get('/api/v1/tracking/' + that.flyingDay + '/' + hex + '/pings').then(function (response) {

				var coords = response.data.data.map(function(point) {
					return new mapkit.Coordinate(point.lat, point.lng);
				});

				var style = new mapkit.Style({
					lineWidth: 4,
					lineJoin: "round",
					strokeColor: "#" + that.aircrafts[hex].colour,
					lineDash: [2, 5]
				});

				var polyline = new mapkit.PolylineOverlay(coords, { style: style });

				// add this overlay if it doesn't exist already
				if (that.activeAircraftTrackOverlay!=null)
				{
					// delete existing overlay
					that.map.removeOverlay(that.activeAircraftTrackOverlay);
				}
				// ...and add it
				that.activeAircraftTrackOverlay = that.map.addOverlay(polyline);

				// zoom to it's extents
				if (zoom && that.zoomSelected) that.map.showItems(that.activeAircraftTrackOverlay, {animate: true});

				// if following an aircraft, center on it when selected
				if (that.followSelected) that.centerOn(response.data.data[0].lat, response.data.data[0].lng);
			});
		}
	}
}
</script>