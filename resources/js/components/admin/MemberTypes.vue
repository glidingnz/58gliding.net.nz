<template>
<div>
	
	<h1 v-if="org">{{org.name}} Member Types</h1>
	<table class="table table-striped">
		<tr>
			<td>
				<div class="d-flex">
					<div class="col-4">Add New Member Type:</div>
					<input type="text" v-model="newMembertype" class="form-control inline ml-auto" size="10">
				</div>
			</td>
			<td><button v-on:click="create()" type="submit" class="btn btn-outline-dark">Add Member Type</button></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<th>Membertype Name</th>
			<th>Save</th>
			<th>Delete</th>
		</tr>
		<tr v-for="duty in membertypes">
			<td><input type="text" v-model="duty.name" class="form-control" v-on:blur="updateMembertype(duty)"></td>
			<td><button v-on:click="updateMembertype(duty)" class="btn btn-outline-dark btn-sm">Save</button></td>
			<td><button v-on:click="deleteMembertype(duty)" class="btn btn-outline-dark btn-sm">Delete</button></td>
		</tr>
	</table>

</div>
</template>

<script>
export default {
	data: function() {
		return {
			org: null,
			admin: false,
			clubAdmin: false,
			membertypes: [],
			newMembertype: '',
		}
	},
	props: [],
	created: function() {
		this.org = window.Laravel.org;
		this.admin = window.Laravel.admin;
		this.clubAdmin = window.Laravel.clubAdmin;
		this.load();
	},
	methods: {
		load: function() {
			var that = this;
			window.axios.get('/api/v1/membertypes/?org_id=' + that.org.id).then(function (response) {
				that.membertypes = response.data.data;
			});
		},
		create: function() {
			var that = this;

			var data = {
				org_id: this.org.id,
				name: this.newMembertype 
			};

			window.axios.post('/api/v1/membertypes', data).then(function (response) {
				that.load();
				messages.$emit('success', 'Membertype Added');
			});
		},
		updateMembertype: function(membertype) {
			window.axios.put('/api/v1/membertypes/' + membertype.id, membertype).then(function (response) {
				messages.$emit('success', 'Membertype Updated');
			});
		},
		deleteMembertype: function(membertype) {
			var that = this;
			window.axios.delete('/api/v1/membertypes/' + membertype.id).then(function (response) {
				messages.$emit('success', 'Membertype Deleted');
				that.load();
			});
		},

	}
}
</script>
