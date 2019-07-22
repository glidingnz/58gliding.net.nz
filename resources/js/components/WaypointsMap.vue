<template>
  <div>
    <vl-map :load-tiles-while-animating="true" :load-tiles-while-interacting="true"
             data-projection="EPSG:4326" style="height: 800px">
      <vl-view :zoom.sync="zoom" :center.sync="center" :rotation.sync="rotation"></vl-view>

      <vl-geoloc @update:position="geolocPosition = $event">
        <template slot-scope="geoloc">
          <vl-feature v-if="geoloc.position" id="position-feature">
            <vl-geom-point :coordinates="geoloc.position"></vl-geom-point>
            <vl-style-box>
              <vl-style-icon src="_media/marker.png" :scale="0.4" :anchor="[0.5, 1]"></vl-style-icon>
            </vl-style-box>
          </vl-feature>
        </template>
      </vl-geoloc>

      <vl-layer-tile id="osm">
        <vl-source-osm></vl-source-osm>
      </vl-layer-tile>
    </vl-map>
    <div style="padding: 20px">
      Zoom: {{ zoom }}<br>
      Center: {{ center }}<br>
      Rotation: {{ rotation }}<br>
      My geolocation: {{ geolocPosition }}
    </div>
  </div>
</template>

<script>
    import common from '../mixins.js';
	export default {
		mixins: [common],
        name: "waypoints-map",
        data () {
            return {
            zoom: 6,
            center: [-187.1442545237832, -40.78985074042114],
            rotation: 0,
            geolocPosition: undefined,
            }
        },
		mounted() {
			this.loadSelected();
			if (window.Laravel.allowsEdit==true) this.showEdit=true;
			if (window.Laravel.admin==true) this.showAdmin=true;
		},
		methods: {
            loadSelected: function() {
                var that=this;
                window.axios.get('/api/v1/waypoints', {params: this.state}).then(function (response) {

                    that.results = response.data.data;
                    that.last_page = response.data.last_page;
                    that.total = response.data.total;

                    if (that.state.page > that.last_page && that.last_page>0) {
                        that.state.page = 1;
                    }
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