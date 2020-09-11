<template><div>

	<h1><a href="/members">Members</a> &raquo; Add Member to <span v-if="org">{{org.name}}</span></h1>


	<div class="form-group col-md-12">
		<p>First check they're not already in the GNZ database.</p>
		<h3>Surname search:</h3>
		<div class="col-md-6">
			<member-selector v-model="existingMemberId" v-on:selected="selectedMember = $event"  v-on:searching="searchText = $event"  :resigned="true"></member-selector>
		</div>


		<div v-if="selectedMember" class="mt-4">

			<h3>Add {{selectedMember.first_name}} {{selectedMember.last_name}} to {{org.name}}</h3>

			<span class="text-muted">Current Club</span> {{selectedMember.club}}
			<span class="text-muted ml-3">GNZ Membership Type</span> {{selectedMember.membership_type}}
			<span class="text-muted ml-3">City</span> {{selectedMember.city}}
			<span class="text-muted ml-3">DOB</span> {{selectedMember.date_of_birth}}
		</div>

		<button v-if="selectedMember" class="btn btn-primary" v-on:click="addExistingMember()">Add {{selectedMember.first_name}} to {{org.name}}</button>

	</div>

	<div v-if="searchText">

		<div class="form-group col-md-6">
			<h3>OR add a new member:</h3>
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
			<button class="btn btn-primary" v-on:click="addNewMember()">Add New Member</button>
		</div>
		
	</div>
	

	{{searchText}}
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
			}
		},
		mounted() {
			this.org = window.Laravel.org;
		},
		methods: {
			addNewMember: function()
			{
				window.axios.post('/api/v1/members', {org_id: this.orgId, first_name: this.first_name, last_name: this.last_name}).then(function (response) {
					messages.$emit('success', 'Member has been added');
				}).catch(
					function (error) {
						var errors = Object.entries(error.response.data.errors);
						for (const [name, error] of errors) {
							messages.$emit('error', `${error}`);
						}
					}
				);
			},
			addExistingMember: function()
			{

			},
		}
	}
</script>