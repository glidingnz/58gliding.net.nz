<style>
	.map {
		width: 100%; 
		height: 600px;
		height: calc(100vh - 420px);
		min-height: 400px;
	}
	.altitude-chart {
		width: 100%;
		height: 300px;
		border: 1px solid #A00;
	}
</style>

<template>
<div>
	<div v-show="mapStatus=='loader'">
		Loading...
	</div>

	<div class="row" style="margin-top: 20px;">
		<div class="col-md-9">

			<div id="map" class="map"  v-bind:class="[  fullScreen ? 'fullscreen' : '']"></div>

		</div>
		<div class="col-md-3">
			
			<h2 style="margin:0;"><a href="/tracking">Tracking</a> &gt; {{rego}} <span v-show="!rego">{{hex}}</span></h1>
</h2>

			<table class="table table-striped table-condensed" v-if="highlightedPoint">
				<tr>
					<td>Altitude</td>
					<td>
						<div v-if="highlightedPoint.altfeet!=null">
							{{ highlightedPoint.altfeet }} ft / {{ highlightedPoint.alt }} m / {{ highlightedPoint.aglfeet }} ft AGL
						</div>
						<div v-if="highlightedPoint.altfeet==null">n/a</div>
					</td>
				</tr>
				<tr>
					<td>Speed</td>
					<td>
						<div><span v-if="highlightedPoint.speed!=null">{{ Math.round(highlightedPoint.speed / 1.852) }} knots / {{ highlightedPoint.speed }} km/h</span><span v-if="selectedPoint.course!=null"> @ {{selectedPoint.course}}&deg;</span></div>
						<div v-if="highlightedPoint.speed==null">n/a</div>
					</td>
				</tr>
				<tr>
					<td>Vertical Speed</td>
					<td>
						<div v-if="highlightedPoint.vspeed!=null">{{ Math.round(highlightedPoint.vspeed * 1.944) }} knots / {{ highlightedPoint.vspeed }} m/s</div>
						<div v-if="highlightedPoint.vspeed==null">n/a</div>
					</td>
				</tr>
				<tr>
					<td>Flarm</td>
					<td>{{ highlightedPoint.hex }}</td>
				</tr>
				<tr>
					<td>Seen</td>
					<td>
						{{ lastSeenTime(highlightedPoint.nzdate) }}
						<span v-if="highlightedPoint.type==1">FLARM</span>
						<span v-if="highlightedPoint.type==2">SPOT (US)</span>
						<span v-if="highlightedPoint.type==3">CELL (Particle.io)</span>
						<span v-if="highlightedPoint.type==4">CELL (Overland)</span>
						<span v-if="highlightedPoint.type==5">SPOT (NZ)</span>
						<span v-if="highlightedPoint.type==6">InReach (NZ)</span>
						<span v-if="highlightedPoint.type==7">CELL (Btraced)</span>
						<span v-if="highlightedPoint.type==8">Gliding Ops</span>
					</td>
				</tr>
				<tr>
					<td>Lat/Long</td>
					<td>{{ highlightedPoint.lat.toFixed(5)}} &nbsp; {{ highlightedPoint.lng.toFixed(5) }}</td>
				</tr>
				<tr>
					<td>Links</td>
					<td>
						<a v-bind:href="'https://www.google.com/maps/place/' + highlightedPoint.lat + '+' + highlightedPoint.lng + '/'">Google Maps</a>
						&nbsp; <a v-bind:href="'http://maps.apple.com/?q=' + highlightedPoint.lat + ',' + highlightedPoint.lng">Apple Maps</a>
					</td>
				</tr>
			</table>

		</div>
	</div>

	<altitude-chart :values="altitudes" :noaltbands="noAltBands" @showpoint="showPoint" @mouseout="mouseOut" @clickpoint="clickPoint"></altitude-chart>

</div>
</template>

<script>

	import common from '../mixins.js';

	export default {
		props: ['year','month','day','rego', 'hex'],
		mixins: [common],
		data: function() {
			return {
				mapkit: {}, // the instance of mapkit
				map: {}, // the mapkit map
				liveLoading: false,
				mapStatus: 'loader',
				flyingDay: null,
				target: null, // highcharts target
				track: [],
				altitudes: [],
				annotation: null,
				coordinate: null,
				highlightedPoint: null,
				selectedPoint: null,
				noAltBands: [],
				fullScreen: false
			}
		},
	created: function() {

		this.flyingDay = this.year + '-' + this.month + '-' + this.day;

		// check we have a hex, otherwise make it from the rego
		if (this.hex=='') this.hex=this.rego.substring(3,9);

		// Add the script to the window object
		var script = document.createElement('script');
		script.type = 'text/javascript';
		script.src = "https://cdn.apple-mapkit.com/mk/5.x.x/mapkit.js";
		document.body.appendChild(script);
		script.onload = () => {

			this.mapkit = window.mapkit;
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
		lastSeenTime: function(datetime) {
			var options = {  hour: '2-digit', minute: '2-digit' };
			return datetime.toLocaleString("en-NZ", options);
		},
		showPoint: function(object) {
			const point = this.track.find( point => point.unixtime === object.x );
			this.highlightedPoint = point;

			// create a new coordinate
			var coordinate = new mapkit.Coordinate(point.lat, point.lng);
			this.annotation.coordinate = coordinate;
		},
		mouseOut: function() {
			//console.log('mouse out');
			//this.highlightedPoint=this.selectedPoint;
		},
		clickPoint: function(object) {
			const point = this.track.find( point => point.unixtime === object.x );
			this.selectedPoint = point;
		},
		timerLoop: function() {
			this.liveLoading = true;
			//this.timeoutTimer = setTimeout(this.timerLoop, 10000); // ten seconds
			this.loadTrack();
		},
		loadTrack: function() {

			var that = this;

			// load aircraft track
			window.axios.get('/api/v1/tracking/' + this.flyingDay + '/' + this.hex + '/pings?order=asc').then(function (response) {
				var responseJson = response.data;

				// add in the unix time to the object for searching later
				that.track = responseJson.data.map(function(point) {
					point.unixtime = that.createDateFromMysql(point.thetime).getTime();
					point.nzdate = that.createDateFromMysql(point.thetime);

					if (point.alt!=null) {
						point.altfeet = Math.round(point.alt * 3.28084);
						point.glfeet = Math.round(point.gl * 3.28084);
						point.aglfeet = point.altfeet - point.glfeet;
						if (point.aglfeet<0) point.aglfeet=0;
					}

					return point;
				});

				that.selectedPoint = that.track[0];

				// create an array suitable for highcharts
				that.altitudes = that.track.map(function(point) {
					var alt = Math.round(point.alt * 3.28084);
					if (point.alt==null) alt=null;
					var newPoint = [point.unixtime, alt];
					return newPoint;
				});

				// ensure NULL values are previous
				var previous=0;
				for (var i=0; i<that.altitudes.length; i++) {
					if (that.altitudes[i][1]==null) {
						that.altitudes[i][1]=previous;
						that.noAltBands.push(that.altitudes[i][0]);
					} else {
						previous=that.altitudes[i][1];
					}
				}

				var coords = responseJson.data.map(function(point) {
					return new mapkit.Coordinate(point.lat, point.lng);
				});

				var style = new mapkit.Style({
					lineWidth: 3,
					lineJoin: "round",
					strokeColor: "#000",
					lineDash: [2, 5]
				});

				var polyline = new mapkit.PolylineOverlay(coords, { style: style });

				// create a point for the last point
				that.coordinate = new mapkit.Coordinate(that.track[that.track.length-1].lat, 
				                                       that.track[that.track.length-1].lng);
				that.annotation = new mapkit.MarkerAnnotation(that.coordinate);
				that.map.showItems(that.annotation);

				that.highlightedPoint = that.track[that.track.length-1];


				// add that overlay if it doesn't exist already
				if (that.activeAircraftTrackOverlay!=null)
				{
					// delete existing overlay
					that.map.removeOverlay(that.activeAircraftTrackOverlay);
				}
				// ...and add it
				that.activeAircraftTrackOverlay = that.map.addOverlay(polyline);

				// zoom to it's extents
				that.map.showItems(that.activeAircraftTrackOverlay, {animate: true});

			});
		},
		loadMap: function () {
			this.mapkit = mapkit;
			var that = this;
			this.mapkit.init({ authorizationCallback: function(done) {
				done(that.apple_auth);
			}});
			this.map = new this.mapkit.Map("map", { 
				center: new mapkit.Coordinate(-39.38,157.31) ,
				isRotationEnabled: false,
				showsMapTypeControl: true
			});
			this.mapStatus = 'map';
			this.mapkit.addEventListener("configuration-change", function(event) {
				switch (event.status) {
					case "Initialized":
					// Mapkit was initialized and configured.
					//that.loadDays();
				break;
				}
			});
		},
	},
}
</script>