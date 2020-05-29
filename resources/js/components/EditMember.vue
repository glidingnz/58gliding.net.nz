<template>
<div>
	<h1 class="results-title"><a href="/members">Members</a> &raquo; {{member.first_name}} {{member.last_name}}</h1>

	<div class="row">
		<div class="col-sm-6 col-xs-12">

			<table class="table table-striped">
				<tr>
					<th colspan="2">Member Details</th>
				</tr>
				<tr>
					<td class="table-label col-xs-6">First Name</td>
					<td><input type="text" v-model="member.first_name" class="form-control"></td>
				</tr>
				<tr>
					<td class="table-label col-xs-6">Middle Name</td>
					<td><input type="text" v-model="member.middle_name" class="form-control"></td>
				</tr>
				<tr>
					<td class="table-label col-xs-6">Last Name</td>
					<td><input type="text" v-model="member.last_name" class="form-control"></td>
				</tr>
				<tr>
					<td class="table-label col-xs-6">Address</td>
					<td>
						<input type="text" v-model="member.address_1" class="form-control"><br>
						<input type="text" v-model="member.address_2" class="form-control">
					</td>
				</tr>
				<tr>
					<td class="table-label col-xs-6">City</td>
					<td><input type="text" v-model="member.city" class="form-control"></td>
				</tr>
				<tr>
					<td class="table-label col-xs-6">Country</td>
					<td><input type="text" v-model="member.country" class="form-control"></td>
				</tr>
				<tr>
					<td class="table-label col-xs-6">ZIP/Post Code</td>
					<td><input type="text" v-model="member.zip_post" class="form-control"></td>
				</tr>
				<tr>
					<td class="table-label col-xs-6">Gender</td>
					<td>
						<select v-model="member.gender" class="form-control">
							<option value="M">Male</option>
							<option value="F">Female</option>
						</select>
					</td>
				</tr>
				<tr>
					<td class="table-label col-xs-6">Date of Birth</td>
					<td>
						<input v-if="showAdmin" type="text" v-model="member.date_of_birth" class="form-control">
						<span v-if="!showAdmin">{{member.date_of_birth}}</span>
					</td>
				</tr>
				<tr>
					<td class="table-label col-xs-6">OO Number</td>
					<td>
						<input type="text" v-model="member.observer_number" class="form-control" v-if="showAdmin">
						<span v-if="!showAdmin">{{member.observer_number}}</span>
					</td>
				</tr>
				<tr>
					<td class="table-label col-xs-6">Privacy</td>
					<td>
						<div class="checkbox">
							<label><input type="checkbox" v-model="member.privacy">Keep contact details private from other GNZ members</label>
						</div>
					</td>
				</tr>
				<tr>
					<td class="table-label col-xs-6">Roles</td>
					<td>
						<div class="checkbox">
							<label><input type="checkbox" v-model="member.coach">Coach</label>
							<br>
							<label><input type="checkbox" v-model="member.contest_pilot">Contest Pilot</label>
						</div>
					</td>
				</tr>
				<tr>
					<td></td>
					<td><button class="btn btn-primary btn-sm" v-on:click="saveMember()">Save Changes</button></td>
				</tr>
			</table>


			<table class="table table-striped">
				<tr>
					<th colspan="2">Contacts</th>
				</tr>
				<tr>
					<td class="table-label col-xs-6">Email</td>
					<td><input type="text" v-model="member.email" class="form-control"></td>
				</tr>
				<tr>
					<td class="table-label col-xs-6">Home Phone</td>
					<td><input type="text" v-model="member.home_phone" class="form-control"></td>
				</tr>
				<tr>
					<td class="table-label col-xs-6">Mobile</td>
					<td><input type="text" v-model="member.mobile_phone" class="form-control"></td>
				</tr>
				<tr>
					<td class="table-label col-xs-6">Business Phone</td>
					<td><input type="text" v-model="member.business_phone" class="form-control"></td>
				</tr>
				<tr>
					<td></td>
					<td><button class="btn btn-primary btn-sm" v-on:click="saveMember()">Save Changes</button></td>
				</tr>
			</table>



		</div>
		<div class="col-sm-6 col-xs-12">


			<table class="table table-striped">
				<tr>
					<th colspan="2">GNZ Details</th>
				</tr>
				<tr>
					<td class="table-label col-xs-6">GNZ Number</td>
					<td>
						<input type="text" v-model="member.nzga_number" class="form-control" v-if="showAdmin">
						<span v-if="!showAdmin">{{member.nzga_number}}</span>
					</td>
				</tr>
				<tr>
					<td class="table-label col-xs-6">GNZ Membership Type</td>
					<td>

						<select v-model="member.membership_type" class="form-control" v-if="showAdmin">
							<option value="Resigned">Resigned</option>
							<option value="Flying">Flying</option>
							<option value="Mag Only">Mag Only</option>
							<option value="Flying Family">Flying Family</option>
							<option value="VFP 3 Mth">VFP 3 Mth</option>
							<option value="VFP Bulk">VFP Bulk</option>
							<option value="Junior">Junior</option>
							<option value="Junior Family">Junior Family</option>
							<option value="VFP 3 Days">VFP 3 Days</option>
							<option value="">None</option>
						</select>

						<span v-if="!showAdmin">{{member.membership_type}}</span>

					</td>
				</tr>
				<tr v-if="showAdmin">
					<td class="table-label col-xs-6">Created</td>
					<td>{{member.created}}</td>
				</tr>
				<tr v-if="showAdmin">
					<td class="table-label col-xs-6">Modified</td>
					<td>{{member.modified}}</td>
				</tr>
				<tr>
					<td class="table-label col-xs-6">Comments</td>
					<td><textarea class="form-control" cols="30" rows="5" v-model="member.comments"></textarea></td>
				</tr>
				<tr>
					<td></td>
					<td><button class="btn btn-primary btn-sm" v-on:click="saveMember()">Save Changes</button></td>
				</tr>
			</table>


			<table class="table table-striped">
				<tr>
					<th colspan="2">Affiliations</th>
				</tr>

				<tr v-for="affiliate in member.affiliates">
					<td class="table-label col-xs-6">{{affiliate.org.name}}</td>
					<td>
						<div>Joined {{formatDate(affiliate.join_date)}}</div>
						<div v-if="affiliate.end_date">
							Resigned {{formatDate(affiliate.end_date)}}
						</div>
						<div v-if="affiliate.resigned_comment">
							{{affiliate.resigned_comment}}
						</div>
					</td>
				</tr>
				<!-- 
				<tr>
					<td class="table-label col-xs-6">Club</td>
					<td>
						<select  v-if="showAdmin" class="form-control input-sm" name="club" v-model="member.club">
							<option v-bind:value="null">None</option>
							<option v-for="org in orgs" v-bind:value="org.gnz_code">{{org.name}}</option>
						</select>


						<span v-if="showAdmin">{{member.club}}</span>
					</td>
				</tr>
				<tr  v-if="showAdmin" >
					<td class="table-label col-xs-6">Date Joined / Rejoined Club</td>
					<td><input type="text" v-model="member.date_joined" class="form-control"></td>
				</tr>
				<tr  v-if="showAdmin" >
					<td class="table-label col-xs-6">Previous Clubs</td>
					<td><input type="text" v-model="member.previous_clubs" class="form-control"></td>
				</tr>
				<tr  v-if="showAdmin" >
					<td class="table-label col-xs-6">GNZ number of Family Member</td>
					<td><input type="text" v-model="member.gnz_family_member_number" class="form-control"></td>
				</tr>
				<tr  v-if="showAdmin" >
					<td class="table-label col-xs-6">Resigned</td>
					<td><input type="text" v-model="member.resigned" class="form-control"></td>
				</tr>
				<tr  v-if="showAdmin" >
					<td class="table-label col-xs-6">Resigned Comments</td>
					<td><input type="text" v-model="member.resigned_comment" class="form-control"></td>
				</tr> -->

				<tr  v-if="showAdmin" >
					<td></td>
					<td><button class="btn btn-primary btn-sm" v-on:click="saveMember()">Save Changes</button></td>
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
				member: [],
				orgs: [],
				showEdit: false,
				showAdmin: false
			}
		},
		mounted() {
			this.loadOrgs();
			this.loadMember();
			if (window.Laravel.admin==true) this.showAdmin=true;
		},
		methods: {
			loadMember: function() {
				var that = this;
				window.axios.get('/api/v1/members/' + this.memberId, {params: this.state}).then(function (response) {
					that.member = response.data.data;
				});
			},
			loadOrgs: function() {
				var that = this;
				window.axios.get('/api/v1/orgs/').then(function (response) {
					that.orgs = response.data.data;
				});
			},
			saveMember: function() {
				window.axios.put('/api/v1/members/' + this.memberId, this.member).then(function (response) {
					messages.$emit('success', 'Member Updated');
				});
			}
		}
	}
</script>