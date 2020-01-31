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
						<th colspan=3>Aircraft Tracking</th>
						<th>Example</th>
					</tr>
					<tr>
						<td class="table-label">FLARM Code</td>
						<td style="min-width: 200px;"><input type="text" v-model="aircraft.flarm" class="form-control" ></td>
						<td><button class="btn btn-primary btn-sm" v-on:click="save()">Save</button></td>
						<td>
							e.g. A1B2C3
							<br>
							The 6 letter hex code that is configured in your FLARM device. Best practice is to match your transponder code.
							We automatically pull these in from the <a href="http://wiki.glidernet.org/ddb">Open Glider Network</a>. Ensure your aircraft is added there as well.
						</td>
					</tr>
					<tr>
						<td class="table-label">trackme.nz SPOT ESN or InReach ID IMEI</td>
						<td><input type="text" v-model="aircraft.spot_esn" class="form-control"></td>
						<td><button class="btn btn-primary btn-sm" v-on:click="save()">Save</button></td>
						<td>
							e.g. Spot: 0-8765432 &nbsp; InReach: 300134363320790
							<br>
							This code is the ID for your SPOT device. Found under the battery cover. Used to receive data from <a href="https://trackme.nz">trackme.nz</a> (formally SPOTNZ). TrackMe are an alternative SPOT service provider here in NZ. Contact them and let them know you want your SPOT added to the Gliding New Zealand group. Slightly more expensive per month, but able to choose which months you're using it.
						</td>
					</tr>
					<tr>
						<td class="table-label">US SPOT Feed</td>
						<td><input type="text" v-model="aircraft.spot_feed_id" class="form-control"></td>
						<td><button class="btn btn-primary btn-sm" v-on:click="save()">Save</button></td>
						<td>
							e.g. 0ZPRRtaEBnfAausjhDxp5qnNA5VCLN2Yq
							<br>
							This is your SPOT shared page code. Used to receive data from the main <a href="https://login.findmespot.com/spot-main-web/auth/login.html">SPOT website</a>.
							<a v-on:click="showSpotInstructions=!showSpotInstructions">Show Instructions</a>
						</td>
					</tr>
					<tr>
						<td class="table-label">US InReach Share Name</td>
						<td><input type="text" v-model="aircraft.inreach_share" class="form-control"></td>
						<td><button class="btn btn-primary btn-sm" v-on:click="save()">Save</button></td>
						<td>e.g. 'timbromhead' taken from https://share.garmin.com/timbromhead</td>
					</tr>
					<tr>
						<td class="table-label">US InReach IMEI</td>
						<td><input type="text" v-model="aircraft.inreach_imei" class="form-control"></td>
						<td><button class="btn btn-primary btn-sm" v-on:click="save()">Save</button></td>
						<td>The actual device ID (IMEI) e.g. 300434030128761</td>
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
						<td><button class="btn btn-primary btn-sm" v-on:click="save()">Save</button></td>
						<td>
							e.g. 290037000747373334363431
							<br>
							This code is for a particle.io electron cellular tracker. <a href="mailto:tim@pear.co.nz">Contact Tim for more information</a>
						</td>
					</tr>
					<tr>
						<td class="table-label">MT600 Tracker Code</td>
						<td><input type="text" v-model="aircraft.mt600" class="form-control"></td>
						<td><button class="btn btn-primary btn-sm" v-on:click="save()">Save</button></td>
						<td>IMEI code e.g. 861585042912480</td>
					</tr>
					<tr>

						<td class="table-label">Cellular Tracking</td>
						<td class="table-label" colspan=3>
							We recommend Btraced available for Android and iPhone for a small fee (less than $5) <br>
							<a href="https://www.btraced.com">www.btraced.com</a>
							<br>
							<a href="http://gliding.co.nz/how-to-set-up-cell-tracking/">Setup Instructions</a>
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