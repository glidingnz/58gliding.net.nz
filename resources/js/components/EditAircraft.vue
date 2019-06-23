<style>
	div.image {
		padding-right: 10px;
	}

</style>

<template>
	<div>
		<h1 class="results-title"><a href="/aircraft">Aircraft</a> &raquo; <a v-bind:href="'/aircraft/' + aircraft.rego">{{aircraft.rego}}</a> &raquo; Edit</h1>

		<div class="row">
			<div class="">

				<table class="table table-striped">
					<tr>
						<th colspan=2>Aircraft Tracking</th>
						<th>Example</th>
					</tr>
					<tr>
						<td class="table-label">FLARM Code</td>
						<td><input type="text" v-model="aircraft.flarm" class="form-control"></td>
						<td>A1B2C3</td>
					</tr>
					<tr>
						<td colspan="3">
						The 6 letter hex code that is configured in your FLARM device. Best practice is to match your transponder code.
						We automatically pull these in from the <a href="http://wiki.glidernet.org/ddb">Open Glider Network</a>. Ensure your aircraft is added there as well.
						</td>
					</tr>
					<tr>
						<td class="table-label">SPOT ESN or InReach ID</td>
						<td><input type="text" v-model="aircraft.spot_esn" class="form-control"></td>
						<td>Spot: 0-8765432 &nbsp; InReach: 300134363320790</td>
					</tr>
					<tr>
						<td colspan="3">
						This code is the ID for your SPOT device. Found under the battery cover. Used to receive data from <a href="https://spotnz.com/home.html">SPOTNZ</a>. SPOTNZ are an alternative SPOT service provider here in NZ. Contact them and let them know you want your SPOT added to the Gliding New Zealand group. Slightly more expensive per month, but able to choose which months you're using it.
						</td>
					</tr>
					<tr>
						<td class="table-label">SPOT Feed</td>
						<td><input type="text" v-model="aircraft.spot_feed_id" class="form-control"></td>
						<td>0ZPRRtaEBnfAausjhDxp5qnNA5VCLN2Yq</td>
					</tr>
					<tr>
						<td colspan="3">
							This is your SPOT shared page code. Used to receive data from the main <a href="https://login.findmespot.com/spot-main-web/auth/login.html">SPOT website</a>.
							<a v-on:click="showSpotInstructions=!showSpotInstructions">Show Instructions</a>
						</td>
					</tr>
					<tr v-if="showSpotInstructions">
						<td colspan="4">

								<div class="row ">
									<div class="image col-xs-12 col-sm-8">
										<img src="/images/spot_instructions/1.jpg" width="100%" alt="">
									</div>
									<div class="col-sm-4">
										1. First up, login to the <a href="https://login.findmespot.com/spot-main-web/auth/login.html">SPOT website</a>
									</div>
								</div>
								<div class="row">
									<div class="image col-xs-12 col-sm-8">
										<img src="/images/spot_instructions/2.jpg" width="100%" alt="">
									</div>
									<div class="col-sm-4">
										2. Click the "Share" menu option, to view the share pages
									</div>
								</div>
								<div class="row">
									<div class="image col-xs-12 col-sm-8">
										<img src="/images/spot_instructions/3.jpg" width="100%" alt="">
									</div>
									<div class="col-sm-4">
										3. If you don't have a shared page yet, click the 'Create a share page' button. If you do, skip to step 6.
									</div>
								</div>
								<div class="row">
									<div class="image col-xs-12 col-sm-8">
										<img src="/images/spot_instructions/4.jpg" width="100%" alt="">
									</div>
									<div class="col-sm-4">
										4. Enter a share page name. It doesn't matter what it is. Change the timespan to 2 days. Ensure the page is public. Then click 'create'.
									</div>
								</div>
								<div class="row">
									<div class="image col-xs-12 col-sm-8">
										<img src="/images/spot_instructions/5.jpg" width="100%" alt="">
									</div>
									<div class="col-sm-4">
										5. The dialogue presented shows the URL with the shared page key display. Copy/paste the whole URL to somewhere else. Then you can copy just the code after the = sign. Paste it into the Spot feed box above. You can stop here. If you want to get the code again after closing the box, see step 6.
									</div>
								</div>
								<div class="row">
									<div class="image col-xs-12 col-sm-8">
										<img src="/images/spot_instructions/6.jpg" width="100%" alt="">
									</div>
									<div class="col-sm-4">
										6. The shared page code is in the URL for the shared page. You can access this anytime. Again copy/paste the code after the = sign.
									</div>
								</div>


						</td>
					</tr>
					<tr>
						<td class="table-label">Particle.io</td>
						<td><input type="text" v-model="aircraft.particle_id" class="form-control"></td>
						<td>290037000747373334363431</td>
					</tr>
					<tr>
						<td colspan="3">
						This code is for a particle.io electron cellular tracker. <a href="mailto:tim@pear.co.nz">Contact Tim for more information</a>
						</td>
					</tr>
					<tr>
						<td class="table-label" colspan=3>Overland iPhone App <a href="https://overland.p3k.app">overland.p3k.app</a></td>
					</tr>
					<tr>
						<td colspan="3">
						For free tracking on an iPhone try <a href="https://overland.p3k.app">Overland</a>. Use the endpoint URL of: <br> http://gliding.net.nz/overland<br>
												And Device ID of your 3 letter registration e.g. GBA
						</td>
					</tr>
					<tr>
						<td class="table-label" colspan=3>Btraced Android and iPhone App <a href="https://www.btraced.com">www.btraced.com</a></td>
					</tr>
					<tr>
						<td colspan="3">
							<b>Under "Upload Settings" set:</b><br>
							Upload Format: XML<br>
							Custom Server Address: http://gliding.net.nz/btraced/REGO<br>
							where REGO should be 3 letters e.g. http://gliding.net.nz/btraced/gba<br><br>

							<b>Under "GPS Settings" set:</b><br>
							Use Time Filter: Tick yes<br>
							Time Interval: 10, 20 or 30 seconds. Please don't use 5 or less.<br>
							<br>
							<a href="https://imgur.com/a/9H8Df8y">View these instructions with pictures</a>
						</td>
					</tr>
					<tr>
						<td></td>
						<td><button class="btn btn-primary btn-sm" v-on:click="save()">Save Changes</button></td>
						<td colspan="2"></td>
					</tr>
				</table>


			</div>
		</div>
	</div>
</div>

</template>


<script>
	import common from '../mixins.js';

	export default {
		mixins: [common],
		props: ['aircraftId'],
		data() {
			return {
				aircraft: [],
				showSpotInstructions: false,
			}
		},
		mounted() {
			this.loadAircraft();
		},
		methods: {
			loadAircraft: function() {
				var that = this;
				window.axios.get('/api/v1/aircraft/' + this.aircraftId).then(function (response) {
					console.log(response);
					that.aircraft = response.data.data;
				});
			},
			save: function() {
				window.axios.put('/api/v1/aircraft/' + this.aircraft.rego, this.aircraft).then(function (response) {
					messages.$emit('success', 'Aircraft Updated');
				});
			}
		}
	}
</script>