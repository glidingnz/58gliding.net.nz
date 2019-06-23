<template>

<style>
	tr.selected td {
		background-color: #CCC;
	}
</style>

<div>
	<div id="map" style="width: 100%; height: 600px;"></div>

	<div v-show="mapStatus=='loader'">
		Loading...
	</div>

	<input type="button" class="btn btn-default" value="Add Marker" v-on:click="addMarker();">
	<input type="button" class="btn btn-default" value="Remove Marker" v-on:click="removeMarker();">
	<input type="button" class="btn btn-default" value="Update Marker" v-on:click="updateMarker();">

</div>
</template>

<script>

	export default {
		props: ['year','month','day'],
		data: function() {
			return {
				mapkit: {}, // the instance of mapkit
				map: {}, // the mapkit map
				mapStatus: 'loader',
				dateToJumpTo: null,
				markers: {},
				markersArray: [],
				i: 0
			}
		},
		created: function() {
			// Add the script to the window object
			var script = document.createElement('script');
			script.type = 'text/javascript';
			script.src = "https://cdn.apple-mapkit.com/mk/5.7.x/mapkit.js";
			document.body.appendChild(script);
			script.onload = () => {
				this.mapkit = window.mapkit;
				this.loadMap();
			}
			
		},
		events: {
		},
		computed: {
			firstDays: function() {
				// return the first 5 days available
				var niceDays = this.days.slice(0, 5);
				return niceDays;
			}
		},
		methods: {
			loadMap: function () {
				this.mapkit = mapkit;
				this.mapkit.init({ authorizationCallback: function(done) {
					done("eyJhbGciOiJFUzI1NiIsInR5cCI6IkpXVCIsImtpZCI6Ijk5NFpBMlZNRzIifQ.eyJpc3MiOiJMQ0FXWjQ0MlNDIiwiaWF0IjoxNTI4NTQwMjI2LjQ2MiwiZXhwIjoxNTQ0MzE5MDI2LjQ2Mn0.eN6Csvnfi2L78l-2xoCzueO3l04s2oE6QDl-1coG2rA8fFOgy3qvg7NBW0HrK5JsufWgrHSeRIInes05BnhwmA");
				}});
				this.map = new this.mapkit.Map("map", { center: new mapkit.Coordinate(-39.38,157.31) });
				//this.mapStatus = 'map';
			},
			addMarker: function() {

				let coordinate = new this.mapkit.Coordinate(-38.688, 176.138);

				var options = {
					title: "Hi there"
				}

				this.markers[this.i] = new this.mapkit.MarkerAnnotation(coordinate, options);
				this.markersArray.push(this.markers[this.i]);

				this.map.showItems(this.markersArray);

				this.i = this.i+1;
			},
			removeMarker: function() {
				this.map.removeAnnotations(this.markersArray);
				this.markers = {};
			},
			updateMarker: function() {
				let coordinate = new this.mapkit.Coordinate(-37.298,174.925);
				this.markers[0].coordinate = coordinate;
				this.map.showItems(this.markersArray);
			}
		}
	}
</script>