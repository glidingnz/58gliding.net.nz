<template>
<div>
	<a class="btn btn-primary" v-if="showEdit" style="float:right; margin-left: 10px;" v-bind:href="'/members/' + results.id + '/edit'">Edit {{results.first_name}}</a>
	<a class="btn btn-outline-dark" v-if="showEdit" style="float:right; margin-left: 10px;" v-bind:href="'http://members.gliding.co.nz/index.php?r=member/update&id=' + results.id">Old Edit</a>
	<a class="btn btn-outline-dark float-right ml-2" v-bind:href="'/members/' + results.id + '/ratings'"><span class="fa fa-clipboard-check"></span> BFR, Medicals & Ratings</a>
	<a class="btn btn-outline-dark float-right" v-bind:href="'/members/' + results.id + '/achievements'"><span class="fa fa-trophy"></span> Achievements</a>

	<h1 class="results-title"><a href="/members">Members</a> &raquo; {{results.first_name}} {{results.last_name}}</h1>

	<div class="row">
		<div class="col-sm-6 col-xs-12">

			<table class="table table-striped">
				<tr>
					<th colspan="2">Member Details</th>
				</tr>
				<tr>
					<td class="table-label">Full Name</td>
					<td>{{results.first_name}} {{results.middle_name}} {{results.last_name}}</td>
				</tr>
				<tr>
					<td class="table-label">Address</td>
					<td>
						{{results.address_1}}<br v-if="results.address_1">
						{{results.address_2}}<br v-if="results.address_2">
						{{results.city}}<br v-if="results.city">
						{{results.country}} {{results.zip_post}}
					</td>
				</tr>
				<tr>
					<td class="table-label">Gender</td>
					<td>{{results.gender}}</td>
				</tr>
				<tr>
					<td class="table-label">Date of Birth</td>
					<td>{{results.date_of_birth}}</td>
				</tr>
				<tr>
					<td class="table-label">OO Number</td>
					<td>{{results.observer_number}}</td>
				</tr>
				<tr>
					<td class="table-label">Coach</td>
					<td>
						<span v-html="formatBoolean(results.coach)"></span>
					</td>
				</tr>
				<tr>
					<td class="table-label">Contest Pilot</td>
					<td>
						<span v-html="formatBoolean(results.contest_pilot)"></span>
					</td>
				</tr>
			</table>


			<table class="table table-striped">
				<tr>
					<th colspan="2">Contacts</th>
				</tr>
				<tr>
					<td class="table-label">Email</td>
					<td><a v-bind:href="'mailto:' + results.email">{{results.email}}</a></td>
				</tr>
				<tr>
					<td class="table-label">Home Phone</td>
					<td>{{results.home_phone}}</td>
				</tr>
				<tr>
					<td class="table-label">Mobile</td>
					<td>{{results.mobile_phone}}</td>
				</tr>
				<tr>
					<td class="table-label">Business Phone</td>
					<td>{{results.business_phone}}</td>
				</tr>
			</table>



			<table class="table table-striped">
				<tr>
					<th colspan="2">QGP</th>
				</tr>
				<tr>
					<td class="table-label">QGP Number</td>
					<td>{{results.qgp_number}}</td>
				</tr>
				<tr>
					<td class="table-label">Date of QGP</td>
					<td>{{results.date_of_qgp}}</td>
				</tr>
			</table>

			<table class="table table-striped">
				<tr>
					<th colspan="2">Comments</th>
				</tr>
				<tr>
					<td colspan="2">{{results.comments}}</td>
				</tr>
			</table>

			<table class="table table-striped">
				<tr>
					<th colspan="2">Ratings</th>
				</tr>
				<tr>
					<td class="table-label">Instructor</td>
					<td><span v-html="formatBoolean(results.instructor)"></span></td>
				</tr>
				<tr>
					<td class="table-label">Instructor Rating</td>
					<td><span v-html="formatBoolean(results.instructor_rating)"></span></td>
				</tr>
				<tr>
					<td class="table-label">Aerotow</td>
					<td><span v-html="formatBoolean(results.aero_tow)"></span></td>
				</tr>
				<tr>
					<td class="table-label">Winch Rating</td>
					<td><span v-html="formatBoolean(results.winch_rating)"></span></td>
				</tr>
				<tr>
					<td class="table-label">Self Launch</td>
					<td><span v-html="formatBoolean(results.self_launch)"></span></td>
				</tr>
				<tr>
					<td class="table-label">Auto Tow Instructor</td>
					<td><span v-html="formatBoolean(results.auto_tow)"></span></td>
				</tr>
				<tr>
					<td class="table-label">Auto Tow Instructor</td>
					<td><span v-html="formatBoolean(results.auto_tow)"></span></td>
				</tr>
				<tr>
					<td class="table-label">Aero Instructor</td>
					<td><span v-html="formatBoolean(results.aero_instructor)"></span></td>
				</tr>
				<tr>
					<td class="table-label">Advanced Aero Instructor</td>
					<td><span v-html="formatBoolean(results.advanced_aero_instructor)"></span></td>
				</tr>
				<tr>
					<td class="table-label">Instructor Trainer</td>
					<td><span v-html="formatBoolean(results.instructor_trainer)"></span></td>
				</tr>
				<tr>
					<td class="table-label">Tow Pilot</td>
					<td><span v-html="formatBoolean(results.tow_pilot)"></span></td>
				</tr>
				<tr>
					<td class="table-label">Tow Pilot Instructor</td>
					<td><span v-html="formatBoolean(results.tow_pilot_instructor)"></span></td>
				</tr>
				<tr>
					<td class="table-label">Insttrain (OLD: for info only)</td>
					<td>{{results.insttrain}}</td>
				</tr>
			</table>

		</div>
		<div class="col-sm-6 col-xs-12">


			<table class="table table-striped">
				<tr>
					<th colspan="2">Account Details</th>
				</tr>
				<tr>
					<td class="table-label">GNZ Number</td>
					<td>{{results.nzga_number}}</td>
				</tr>
				<tr>
					<td class="table-label">Access Level</td>
					<td>{{results.access_level}}</td>
				</tr>
				<tr>
					<td class="table-label">Created</td>
					<td>{{results.created}}</td>
				</tr>
				<tr>
					<td class="table-label">Modified</td>
					<td>{{results.modified}}</td>
				</tr>
			</table>


			<table class="table table-striped">
				<tr>
					<th colspan="2">Affiliation</th>
				</tr>
				<tr>
					<td class="table-label">Membership Type</td>
					<td>{{results.membership_type}}</td>
				</tr>
				<tr>
					<td class="table-label">Club</td>
					<td>{{results.club}}</td>
				</tr>
				<tr>
					<td class="table-label">Date Joined / Rejoined Club</td>
					<td>{{results.date_joined}}</td>
				</tr>
				<tr>
					<td class="table-label">Previous Clubs</td>
					<td>{{results.previous_clubs}}</td>
				</tr>
				<tr>
					<td class="table-label">GNZ number of Family Member</td>
					<td>{{results.gnz_family_member_number}}</td>
				</tr>
				<tr>
					<td class="table-label">Resigned</td>
					<td>{{results.resigned}}</td>
				</tr>
				<tr>
					<td class="table-label">Resigned Comments</td>
					<td>{{results.resigned_comment}}</td>
				</tr>
			</table>



			<table class="table table-striped">
				<tr>
					<th colspan="2">Awards</th>
				</tr>
				<tr>
					<td class="table-label">QGP Number</td>
					<td>{{results.qgp_number}}</td>
				</tr>
				<tr>
					<td class="table-label">QGP Date</td>
					<td>{{results.qgp_date}}</td>
				</tr>
				<tr>
					<td class="table-label">Silver Cert #</td>
					<td>{{results.silver_certificate_number}}</td>
				</tr>
				<tr>
					<td class="table-label">Silver Duration</td>
					<td>{{results.silver_duration}}</td>
				</tr>
				<tr>
					<td class="table-label">Silver Distance</td>
					<td>{{results.silver_distance}}</td>
				</tr>
				<tr>
					<td class="table-label">Silver Height</td>
					<td>{{results.silver_height}}</td>
				</tr>
				<tr>
					<td class="table-label">Gold Badge #</td>
					<td>{{results.gold_certificate_number}}</td>
				</tr>
				<tr>
					<td class="table-label">Gold Distance</td>
					<td>{{results.gold_distance}}</td>
				</tr>
				<tr>
					<td class="table-label">Gold Height</td>
					<td>{{results.gold_height}}</td>
				</tr>
				<tr>
					<td class="table-label">Diamond Distance #</td>
					<td>{{results.diamond_distance_number}}</td>
				</tr>
				<tr>
					<td class="table-label">Diamond Height #</td>
					<td>{{results.diamond_height_number}}</td>
				</tr>
				<tr>
					<td class="table-label">Diamond Height #</td>
					<td>{{results.diamond_height_number}}</td>
				</tr>
				<tr>
					<td class="table-label">Diamond Goal #</td>
					<td>{{results.diamond_goal_number}}</td>
				</tr>
				<tr>
					<td class="table-label">1000km Flight #</td>
					<td>{{results.flight_1000km_number}}</td>
				</tr>
				<tr>
					<td class="table-label">1250km Flight #</td>
					<td>{{results.flight_1250km_number}}</td>
				</tr>
				<tr>
					<td class="table-label">1500km Flight #</td>
					<td>{{results.flight_1500km_number}}</td>
				</tr>
				<tr>
					<td class="table-label">Other FAI Awards or Diplomas</td>
					<td>{{results.awards}}</td>
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
		props: ['memberId'],
		data() {
			return {
				results: [],
				showEdit: false
			}
		},
		mounted() {
			this.loadMember();
			if (window.Laravel.allowsEdit==true) this.showEdit=true;
		},
		methods: {
			loadMember: function() {
				var that=this;
				window.axios.get('/api/v1/members/' + this.memberId, {params: this.state}).then(function (response) {
					that.results = response.data.data;
				});
			}
		}
	}
</script>