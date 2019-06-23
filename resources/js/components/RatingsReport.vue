<style>
	@media (max-width:768px) {
		.ratings-table td {
			display: block;
		}
		.ratings-table th {
			display: none;
		}
		.name {
			font-weight: bold;
		}
	}
</style>

<template>
	<div class="list-group">
		
		<table class="table table-striped ratings-table">
			<tr>
				<th>Name</th>
				<th class="hidden-xs">GNZ#</th>
				<th>Instructor?</th>
				<th>QGP?</th>
				<th>BFR</th>
				<th>Medical</th>
				<th>Passengers</th>
				<th class="hidden-xs" v-if="allowsEdit">Edit</th>
			</tr>
			<template v-for="rating in ratings">
				<tr>
					<td><span class="name"><a href="/members/{{rating.id}}/ratings?from=ratings">{{rating.first_name}} {{rating.last_name}}</a></span> <a href="/members/{{rating.id}}/ratings?from=ratings" class="btn btn-xs btn-primary hidden-sm hidden-md hidden-lg" v-if="allowsEdit">Edit</a></td>
					<td class="hidden-xs">{{rating.nzga_number}}</td>
					<td>
						<span class=" hidden-sm hidden-md hidden-lg">Instructor: </span><span class="fa fa-check" v-if="rating.instructor"></span><span class="fa fa-times hidden-sm hidden-md hidden-lg" v-if="!rating.instructor"></span>
					</td>
					<td>
						<span class=" hidden-sm hidden-md hidden-lg">QGP: </span><span class="fa fa-check" v-if="rating.qgp_awarded!=null"></span><span class="fa fa-times hidden-sm hidden-md hidden-lg" v-if="!rating.qgp_awarded"></span>
					</td>
					<td v-bind:class="[ bfrGood(rating) ]">
						<span class="fa fa-check" v-if="bfrGood(rating)=='success'"></span>
						<span class=" hidden-sm hidden-md hidden-lg">BFR: </span>
						<span class="fa fa-exclamation-triangle" v-if="bfrGood(rating)=='warning'"></span>
						<span class="fa fa-info-circle" v-if="bfrGood(rating)=='info'"></span>
						<span class="fa fa-times error" v-if="bfrGood(rating)=='danger'"></span>
						<span v-if="rating.bfr_expires">
							<span v-if="!ratingExpired(rating.bfr_expires)">Expires</span>
							<span v-if="ratingExpired(rating.bfr_expires)">Expired</span>
							{{rating.bfr_expires_togo}}
						</span> 
						<span v-if="!rating.bfr_expires">No BFR on file</span>
					</td>
					<td v-bind:class="[ medicalGood(rating) ]">
						<span class="fa fa-check" v-if="medicalGood(rating)=='success'"></span>
						<span class=" hidden-sm hidden-md hidden-lg">Medical: </span>
						<span class="fa fa-exclamation-triangle" v-if="medicalGood(rating)=='warning'"></span>
						<span class="fa fa-info-circle" v-if="medicalGood(rating)=='info'"></span>
						<span class="fa fa-times error" v-if="medicalGood(rating)=='danger'"></span>
						<span v-if="rating.medical_expires!=null">
							<span v-if="!ratingExpired(rating.medical_expires)">Expires</span>
							<span v-if="ratingExpired(rating.medical_expires)">Expired</span>
							{{rating.medical_expires_togo}}
						</span> 
						<span v-if="rating.medical_awarded && !rating.medical_expires">Never Expires</span>
						<span v-if="!rating.medical_awarded">No medical on file</span>
					</td>
					<td v-bind:class="[ passengersGood(rating) ]">
						<span class=" hidden-sm hidden-md hidden-lg">Passengers: </span>
						<span class="fa fa-check" v-if="passengersGood(rating)=='success'"></span>
						<span class="fa fa-exclamation-triangle" v-if="passengersGood(rating)=='warning'"></span>
						<span class="fa fa-times error" v-if="passengersGood(rating)=='danger'"></span>
						<span v-if="!rating.qgp_awarded">No QGP.</span>
						<span v-if="!rating.medical_passenger_expires">No medical.</span>
						<span v-if="!rating.bfr_expires || ratingExpired(rating.bfr_expires)"> No BFR.</span>

						<span v-if="ratingNearlyExpired(rating.medical_passenger_expires)">Medical expires {{rating.medical_passenger_expires_togo}}</span>
						<span v-if="ratingExpired(rating.medical_passenger_expires)">Medical expired {{rating.medical_passenger_expires_togo}}</span>
						<span v-if="passengersGood(rating)=='success'">Medical expires {{rating.medical_passenger_expires_togo}}</span>
					</td>
					<td class="hidden-xs" v-if="allowsEdit">
						<a href="/members/{{rating.id}}/ratings?from=ratings" class="btn btn-xs btn-primary">Edit</a>
					</td>
				</tr>
				<tr>
					<td colspan="6" class="hidden-sm hidden-md hidden-lg" style="height: 30px;">&nbsp;</td>
				</tr>
			</template>
		</table>

	</div>
</template>

<script>
import common from '../mixins.js';
import timeago from 'timeago.js';

export default {
	mixins: [common],
	data: function() {
		return {
			ratings: []
		}
	},
	props: ['org', 'allowsEdit'],
	created: function () {
		this.loadRatings();
	},
	methods: {
		getDomain: function() {
			return window.Laravel.APP_DOMAIN;
		},
		loadRatings: function() {
			this.$http.get('/api/v1/ratings/report', {params: {org: this.org}}).then(function (response) {
				// success callback
				var responseJson = response.json();
				this.ratings = responseJson.data;

				var timeagoInstance = timeago();
				for (var i=0; i<this.ratings.length; i++) {
					this.ratings[i].bfr_expires_togo = timeagoInstance.format(this.ratings[i].bfr_expires);
					this.ratings[i].medical_expires_togo = timeagoInstance.format(this.ratings[i].medical_expires);
					this.ratings[i].medical_passenger_expires_togo = timeagoInstance.format(this.ratings[i].medical_passenger_expires);
				}
			});
		},
		passengersGood: function(rating) {
			if (this.ratingNearlyExpired(rating.bfr_expires)) return 'warning';
			if (this.ratingExpired(rating.bfr_expires)) return 'danger';
			if (!rating.bfr_expires) return 'danger';
			if (this.ratingNearlyExpired(rating.medical_passenger_expires)) return 'warning';
			if (this.ratingExpired(rating.medical_passenger_expires)) return 'danger';
			if (!rating.medical_passenger_expires) return 'danger';
			if (!rating.qgp_awarded) return 'danger';
			return 'success';
		},
		bfrGood: function(rating) {
			if (this.ratingNearlyExpired(rating.bfr_expires)) return 'warning';
			if (this.ratingExpired(rating.bfr_expires)) return 'danger';
			if (!rating.bfr_expires) return 'info';
			return 'success';
		},
		medicalGood: function(rating) {
			if (this.ratingNearlyExpired(rating.medical_expires)) return 'warning';
			if (this.ratingExpired(rating.medical_expires)) return 'danger';
			if (!rating.medical_awarded) return 'info';
			return 'success';
		}
	}
}
</script>

<style>
</style>