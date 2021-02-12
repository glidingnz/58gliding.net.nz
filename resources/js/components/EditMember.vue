<template>
<div>
	<h1 class="results-title"><a href="/members">Members</a> &raquo; <a :href="'/members/' + member.id">{{member.first_name}} {{member.last_name}}</a> &raquo; Edit</h1>

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
						<!-- <input v-if="showAdmin" type="text" v-model="member.date_of_birth" class="form-control"> -->
						<v-date-picker  v-if="member.can_edit" id="start_date" v-model="member.date_of_birth" :locale="{ id: 'start_date', firstDayOfWeek: 2, masks: { weekdays: 'WW', L: 'DD/MM/YYYY' } }" :popover="{ visibility: 'click' }"></v-date-picker>
						<span v-if="!member.can_edit">
							{{member.date_of_birth}}
						</span>
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
							<label><input type="checkbox" v-model="member.privacy"> Keep contact details private from other GNZ members</label>
						</div>
					</td>
				</tr>
				<tr>
					<td class="table-label col-xs-6">Roles</td>
					<td>
						<div class="checkbox">
							<label><input type="checkbox" v-model="member.coach"> Coach</label>
							<br>
							<label><input type="checkbox" v-model="member.contest_pilot"> Contest Pilot</label>
						</div>
					</td>
				</tr>
				<tr>
					<td class="table-label col-xs-6">Awards</td>
					<td>
						<input type="text" v-model="member.awards" class="form-control" v-if="showAdmin">
						<span v-if="!showAdmin">{{member.awards}}</span>
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
					<td class="table-label col-xs-6">Primary Club</td>
					<td>
						<select  v-if="showAdmin" class="form-control input-sm" name="club" v-model="member.club">
							<option v-bind:value="null">None</option>
							<option v-for="org in orgs" v-bind:value="org.gnz_code">{{org.name}}</option>
						</select>

						<span v-if="!showAdmin">{{member.club}}</span>
					</td>
				</tr>
				<tr>
					<td class="table-label col-xs-6">GNZ Membership Type</td>
					<td>

						<select v-model="member.membership_type" class="form-control" v-if="showAdmin">
							<option value="Resigned">Resigned</option>
							<option value="Flying">Flying</option>
							<option value="Mag Only">Communications</option>
							<option value="VFP Bulk">Visiting Foreign Pilot Bulk</option>
							<option value="VFP 3 Mth">Visiting Foreign Pilot 3 Mth</option>
							<option value="Junior">Junior</option>
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
				<tr v-if="showAdmin">
					<td class="table-label col-xs-6">Comments</td>
					<td><textarea class="form-control" cols="30" rows="5" v-model="member.comments"></textarea></td>
				</tr>
				<tr>
					<td></td>
					<td><button class="btn btn-primary btn-sm" v-on:click="saveMember()">Save Changes</button></td>
				</tr>
			</table>

			<table class="table table-striped table-sm">
				<tr>
					<th colspan="2">Membership History <a :href="'/members/' + member.id + '/edit-affiliates'" class="btn btn-primary btn-sm float-right">Edit History</a></th>
				</tr>

				<template v-for="affiliate in orderedAffiliates">
					
					<tr>
						<td>{{affiliate.org.name}}</td>
						<td>
								<span v-if="getMemberType(affiliate.membertype_id)">{{getMemberType(affiliate.membertype_id).name}}</span>
								
								<span v-if="!affiliate.resigned" class="success">Active</span>
								<span v-if="affiliate.resigned" class="error">Ended/Resigned</span>

								<br>
								{{formatDate(affiliate.join_date)}} <span v-if="affiliate.end_date">to {{formatDate(affiliate.end_date)}}</span>
						</td>
					</tr>
				</template>
				
				
				<span v-if="!showAdmin">{{member.membership_type}}</span>

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
				org: null,
				memberTypes: [],
				member: [],
				orgs: [],
				showEdit: false,
				showAdmin: false
			}
		},
		mounted() {
			this.org = window.Laravel.org;
			this.loadOrgs();
			this.loadMemberTypes();
			this.loadMember();
			if (window.Laravel.admin==true) this.showAdmin=true;
			if (window.Laravel.editAwards==true) this.showAdmin=true;
		},
		computed: {
			orderedAffiliates: function () {
				return _.orderBy(this.member.affiliates, 'join_date', 'desc')
			}
		},
		methods: {
			getMemberType(membertypeId) {
				return this.memberTypes.find( ({ id }) => id === membertypeId);
			},
			loadMember: function() {
				var that = this;
				window.axios.get('/api/v1/members/' + this.memberId, {params: this.state}).then(function (response) {
					that.member = response.data.data;
					that.member.date_of_birth = that.$moment(that.member.date_of_birth).toDate();
					// convert dates to javascript for all affiliates
					if (that.member.affiliates) {
							that.member.affiliates.forEach(function(affiliate) {
							if (affiliate.join_date) affiliate.join_date = that.$moment(affiliate.join_date).toDate();
							if (affiliate.end_date) affiliate.end_date = that.$moment(affiliate.end_date).toDate();
						});
					}
				});
			},
			loadOrgs: function() {
				var that = this;
				window.axios.get('/api/v1/orgs/').then(function (response) {
					that.orgs = response.data.data;
				});
			},
			loadMemberTypes: function()
			{
				var that=this;
				window.axios.get('/api/v1/membertypes').then(function (response) {
					that.memberTypes = response.data.data;
				});
			},
			saveMember: function() {
				var that=this;

				var member = _.clone(this.member);
				member.date_of_birth = this.apiDateFormat(member.date_of_birth);

				window.axios.put('/api/v1/members/' + this.memberId, member).then(function (response) {
					for(var i=0; i<that.member.affiliates.length; i++)
					{
						that.updateAffiliate(that.member.affiliates[i])
					}
					messages.$emit('success', 'Member Updated');
				});
			},
			updateAffiliate: function(affiliate) {
				var affiliate_clone = _.clone(affiliate);
				affiliate_clone.join_date = this.apiDateFormat(affiliate_clone.join_date);
				affiliate_clone.end_date = this.apiDateFormat(affiliate_clone.end_date);

				window.axios.put('/api/v1/affiliates/' + affiliate.id, affiliate_clone).then(function (response) {
					messages.$emit('success', 'Affiliate Updated');
				});
			},
			resignAffiliate: function(affiliate) {
				affiliate.end_date = Vue.prototype.$moment().toDate();;
				//affiliate.end_date.set({hour:0,minute:0,second:0,millisecond:0}).toDate();
				affiliate.resigned = true;
				this.updateAffiliate(affiliate);
			}
		}
	}
</script>