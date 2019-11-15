<style>
.mapbox {
	width: 100%;
	height: 500px;
	border: 1px solid #A00;
}
</style>

<template>
<div>

	Map:
	<div class="mapbox" id="map"></div>

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
				'nav': {}
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

		var marker = new mapboxgl.Marker()
		.setLngLat([175.733, -37.73593])
		.addTo(this.map);

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
		loadTracks: function() {
			var pings = 25;
			if (this.showTrails==false) pings=2;
			var that = this;

			window.axios.get('/api/v1/tracking/' + that.flyingDay + '/pings/' + pings).then(function (response) {

				// Process the track data to split up by aircraft
				//var aircraftTracks = that.splitTracksByAircraft(response.data.data);
				//
				console.log(response.data.data);

				// set up the annotations
				//aircraftTracks.map(that.createAnnotation);

				// zoom to the extents, only if not 'live'
				//if (!that.liveLoading) that.map.showItems(that.annotationsArray);

				// follow if an item is selected and the option is set
				// if (that.liveLoading && that.followSelected && that.selectedPoint) {
				// 	that.centerOn(that.selectedPoint.lat, that.selectedPoint.lng)
				// } 

				// draw the track lines
				//aircraftTracks.map(that.createPolyline);

				// load the aircraft details from the hex codes that have been collected
				//that.loadAircraftDetails();

			});
		},
	}
}
</script>