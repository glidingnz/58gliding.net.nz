<template>
<div>
	<h1 v-if="member" class="results-title"><a href="/members">Members</a> &raquo; <a :href="'/members/' + member.id">{{member.first_name}} {{member.last_name}}</a> &raquo; <a :href="'/members/' + member.id + '/edit'">Edit</a> &raquo; Membership History</h1>

	Add New 


	<table class="table table-striped table-sm collapsable">
		<tr>
			<th>Club</th>
			<th>Membership Type</th>
			<th>Status</th>
			<th>Start</th>
			<th colspan="2">End</th>
			<th>Comment</th>
			<th></th>
		</tr>

		<tr v-for="(affiliate, index) in orderedAffiliates" v-bind:key="affiliate.id">
			<td>{{affiliate.org.name}}</td>
			<td>
				{{getMemberType(affiliate.membertype_id).name}}<br>
					<a href="#" v-on:click.prevent="affiliate.showEdit=!affiliate.showEdit">Edit</a> &nbsp; 
					<a v-show="!affiliate.resigned" href="#" v-on:click.prevent="affiliate.showChange=!affiliate.showChange">Change</a>


				<span v-if="affiliate.showChange">
					<br>
					Change to:
					<select v-model="affiliate.cloneMemberType" name="member_type" id="member_type" v-if="memberTypes.length>0" class="form-control">
						<option v-for="memberType in filteredMembershipTypes(affiliate.org.id)" :value="memberType.id">{{memberType.name}}</option>
					</select>
					<button class="btn btn-primary btn-xs" v-on:click="changeType(affiliate)">Change Type</button>
				</span>


				<span v-if="affiliate.showEdit">
					<br>
					<span class="warning">Only edit if actually wrong!</span>
					<br>Click Change to change type of member.
					<select v-model="affiliate.membertype_id" name="member_type" id="member_type" v-if="memberTypes.length>0" class="form-control">
						<option v-for="memberType in filteredMembershipTypes(affiliate.org.id)" :value="memberType.id">{{memberType.name}}</option>
					</select>
				</span>
			</td>
			<td>
					<span v-if="!affiliate.resigned" class="success">Active</span>
					<span v-if="affiliate.resigned" class="error">Ended</span>
			</td>
			<td>
				<v-date-picker id="join_date" v-model="affiliate.join_date" :locale="{ id: 'join_date', firstDayOfWeek: 2, masks: { weekdays: 'WW', L: 'DD/MM/YYYY' } }" :popover="{ visibility: 'click' }"></v-date-picker>
			</td>
			<td style="white-space: nowrap;">
				
				<label v-show="affiliate.resigned" :for="'resigned'+affiliate.id"><input :id="'resigned'+affiliate.id" type="checkbox" :value="true" v-model="affiliate.resigned"> Ended</label>
			</td>
			<td>
				<button v-if="!affiliate.resigned" class="btn btn-danger btn-sm " v-on:click="resignAffiliate(affiliate)">End Now</button>

				<span  v-show="affiliate.resigned">
					<!-- <input type="text" v-model="affiliate.end_date" class="form-control"> -->
					<v-date-picker id="end_date" v-model="affiliate.end_date" :locale="{ id: 'end_date', firstDayOfWeek: 2, masks: { weekdays: 'WW', L: 'DD/MM/YYYY' } }" :popover="{ visibility: 'click' }"></v-date-picker>

				</span>

			</td>
			<td>
				<input type="text" v-model="affiliate.resigned_comment" class="form-control">
			</td>
			<td>
				<button class="btn btn-primary btn-sm mb-1 mr-2" v-on:click="updateAffiliate(affiliate, false)">Save</button>
				<button class="btn btn-primary btn-sm" v-on:click="affiliate.showDelete=!affiliate.showDelete">Delete</button>
				<div v-if="affiliate.showDelete">
					Are you sure?<br>
					<button class="btn btn-primary btn-xs" v-on:click="affiliate.showDelete=false">Cancel</button>
					<button class="btn btn-danger btn-xs" v-on:click="deleteAffiliate(affiliate)">Yes, Delete</button>
				</div>
				
			</td>
			
		</tr>

		
	</table>
</div>
</template>
<script>
	import common from '../../mixins.js';

	export default {
		mixins: [common],
		props: ['memberId'],
		data() {
			return {
				org: null,
				memberTypes: [],
				member: [],
				showEdit: false,
				showAdmin: false,
				showChangeType: [],
			}
		},
		mounted() {
			this.org = window.Laravel.org;
			this.loadMemberTypes();
			this.loadMember();
			if (window.Laravel.admin==true) this.showAdmin=true;
			if (window.Laravel.editAwards==true) this.showAdmin=true;
		},
		computed: {
			orderedAffiliates: function () {
				return _.orderBy(this.member.affiliates, ['join_date', 'resigned'], 'desc')
			}
		},
		methods: {
			// filter membership types by the given org ID
			filteredMembershipTypes: function(org_id) {
				return this.memberTypes.filter( function (m) {
					if (m.org_id == org_id) return true;
				});
			},
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

								Vue.set(affiliate, 'showEdit', false); // add flag and make it reactive
								Vue.set(affiliate, 'showChange', false); // add flag and make it reactive
								Vue.set(affiliate, 'showDelete', false); // add flag and make it reactive
								Vue.set(affiliate, 'cloneMemberType', null); // add flag and make it reactive

								if (affiliate.join_date) affiliate.join_date = that.$moment(affiliate.join_date).toDate();
								if (affiliate.end_date) affiliate.end_date = that.$moment(affiliate.end_date).toDate();
						});
					}
				});
			},
			changeType: function(affiliate)
			{
				var that = this;
				// create a new affilliate with the new details

				var today = that.$moment().format("YYYY-MM-DD"); // starts today
				window.axios.post('/api/v1/affiliates', {
					org_id: affiliate.org.id, 
					member_id: this.member.id, 
					membertype_id: affiliate.cloneMemberType,
					join_date: today
				}).then(function (response) {

					// set the current affiliate to ended
					affiliate.end_date = today;
					affiliate.resigned = true;
					affiliate.resigned_comment = 'Changed Membership Type';
					that.updateAffiliate(affiliate, true);


				}).catch(
					function (error) {
						var errors = Object.entries(error.response.data.errors);
						for (const [name, error] of errors) {
							messages.$emit('error', `${error}`);
						}
					}
				);
			},
			loadMemberTypes: function()
			{
				var that=this;
				window.axios.get('/api/v1/membertypes').then(function (response) {
					that.memberTypes = response.data.data;
				});
			},
			updateAffiliate: function(affiliate, reload) {
				var that=this;
				var affiliate_clone = _.clone(affiliate);
				affiliate_clone.join_date = this.apiDateFormat(affiliate_clone.join_date);
				affiliate_clone.end_date = this.apiDateFormat(affiliate_clone.end_date);

				window.axios.put('/api/v1/affiliates/' + affiliate.id, affiliate_clone).then(function (response) {
					messages.$emit('success', 'Membership Details Updated');
					if (reload) that.loadMember();


				}).catch(error => {
					if (error.response) {
						messages.$emit('error', error.response.data.error);
					}
					
				});
			},
			resignAffiliate: function(affiliate) {
				affiliate.end_date = Vue.prototype.$moment().toDate();
				//affiliate.end_date.set({hour:0,minute:0,second:0,millisecond:0}).toDate();
				affiliate.resigned = true;
				this.updateAffiliate(affiliate, false);
			},
			deleteAffiliate: function(affiliate) {
				var that=this;
				window.axios.delete('/api/v1/affiliates/' + affiliate.id).then(function (response) {
					messages.$emit('success', 'Membership History Deleted');
					that.loadMember();

				}).catch(error => {
					if (error.response) {
						messages.$emit('error', error.response.data.error);
					}
					
				});
			}
		}
	}
</script>