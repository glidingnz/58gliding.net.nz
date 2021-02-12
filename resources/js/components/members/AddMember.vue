<template><div>

	<h1><a href="/members">Members</a> &raquo; Add Member to <span v-if="org">{{org.name}}</span></h1>


	<div class="form-group col-md-12">
		<p>First check they're not already in the GNZ database (e.g. in another club or a previous member):</p>
		<h3>Surname search:</h3>
		<div class="col-md-6">
			<member-selector v-model="existingMemberId" 
				v-on:selected="selectedMember = $event; showAddNew=false"  
				v-on:searching="last_name = $event; showAddNew=false" 
				:resigned="true"></member-selector>
		</div>

		<div v-if="selectedMember" class="mt-4 mb-4">

			<h3>Is {{selectedMember.first_name}} {{selectedMember.last_name}} the person you're adding?</h3>

			<span class="text-muted">Current Club</span> {{selectedMember.club}}
			<span class="text-muted ml-3">GNZ Membership Type</span> {{selectedMember.membership_type}}
			<span class="text-muted ml-3">City</span> {{selectedMember.city}}
			<span class="text-muted ml-3">DOB</span> {{selectedMember.date_of_birth}}
		</div>

		<button v-if="selectedMember" class="btn btn-primary mr-2" v-on:click="showAddExisting=true; showAddNew=false;">
			Yes, Add {{selectedMember.first_name}}...
		</button>

		<button v-if="selectedMember" class="btn btn-primary" v-on:click="showAddExisting=false; showAddNew=true">
			No, add a new person...
		</button>

	</div>

	<div v-if="last_name && selectedMember==null" class="form-group col-md-6">
		<button class="btn btn-primary" v-on:click="showAddExisting=false; showAddNew=true">
			I can't find them, add a new person...
		</button>
	</div>




	<div v-if="showAddExisting">

		<div class="form-group col-md-6">
			<label for="member_type">Type of Member To Add As</label> 
			<select v-model="membertype_id" name="member_type" id="member_type" v-if="memberTypes.length>0" class="form-control">
				<option v-for="memberType in memberTypes" :value="memberType.id">{{memberType.name}}</option>
			</select>
		</div>

		<div class="form-group col-md-6">
			<button v-if="selectedMember" class="btn btn-primary mr-2" v-on:click="addExistingMember()">
				Add {{selectedMember.first_name}} to {{org.name}}
			</button>
		</div>
	</div>




	<div v-if="showAddNew">

		<div class="form-group col-md-6">
			<h3>Add a new member:</h3>
		</div>

		<div class="form-group col-md-6">
			<label for="first_name">First Name</label>
			<input type="text" v-model="first_name" class="form-control" id="first_name" name="first_name">
		</div>

		<div class="form-group col-md-6">
			<label for="last_name">Last Name</label> 
			<input type="text" v-model="last_name" class="form-control" id="last_name" name="last_name">
		</div>

		<div class="form-group col-md-6">
			<label for="member_type">Type of Member To Add As</label> 
			<select v-model="membertype_id" name="member_type" id="member_type" v-if="memberTypes.length>0" class="form-control">
				<option v-for="memberType in memberTypes" :value="memberType.id">{{memberType.name}}</option>
			</select>
		</div>


		<div class="form-group col-md-6">
			<button class="btn btn-primary" v-on:click="addNewMember()">Add New Member</button>
		</div>
		
	</div>

</div></template>

<script>
	import common from '../../mixins.js';

	export default {
		mixins: [common],
		props: [],
		data() {
			return {
				searchText: null,
				org: null,
				existingMemberId: null,
				selectedMember: null,
				first_name: null,
				last_name: null,
				membertype_id: null,
				memberTypes: [],
				showAddNew: false,
				showAddExisting: false,
			}
		},
		mounted() {
			this.org = window.Laravel.org;
			this.loadMemberTypes();
		},
		methods: {
			addNewMember: function()
			{
				window.axios.post('/api/v1/members', {org_id: this.org.id, first_name: this.first_name, last_name: this.last_name, membertype_id: this.membertype_id}).then(function (response) {
					messages.$emit('success', 'Member added');
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
				window.axios.get('/api/v1/membertypes?org_id=' + that.org.id).then(function (response) {
					that.memberTypes = response.data.data;
				});
			},
			addExistingMember: function()
			{
				var that=this;
				// create the current date
				var thedate = that.$moment().format("YYYY-MM-DD");
				window.axios.post('/api/v1/affiliates', {
					org_id: this.org.id, 
					member_id: this.existingMemberId, 
					membertype_id:this.membertype_id,
					join_date: thedate
				}).then(function (response) {
					messages.$emit('success', 'Member added');
				}).catch(
					function (error) {
						var errors = Object.entries(error.response.data.errors);
						for (const [name, error] of errors) {
							messages.$emit('error', `${error}`);
						}
					}
				);
			},
		}
	}
</script>