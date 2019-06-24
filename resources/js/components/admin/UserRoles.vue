<template>
<div>
	<table class="table table-striped">
		<tr>
			<td>
				<div class="form form-inline">
					<select class="form-control" name="add_role" id="add_role" v-model="newRole">
						<option v-bind:value="null">Select new role...</option>
						<option v-for="role in roles" v-bind:value="role.id">{{role.name}}</option>
					</select>
					
				</div>
			</td>
			<td><button v-on:click="addUserRole()" type="submit" class="btn btn-outline-dark">Add Role</button></td>
			<td></td>
		</tr>
		<tr>
			<th>Role</th>
			<th>Scope</th>
			<th>Delete</th>
		</tr>
		<tr v-for="userRole in userRoles">
			<td>{{userRole.name}}</td>
			<td style="width: 100%;">
				<select class="form-control input-sm" name="club" v-model="userRole.pivot.org_id" v-show="userRole.club" v-on:change="updateUserRole(userRole.pivot.id, userRole.pivot.org_id)">
					<option v-bind:value="null">Select Organisation...</option>
					<option v-for="org in orgs" v-bind:value="org.id">{{org.name}}</option>
				</select>
				<span v-show="!userRole.club">All Clubs</span>
			</td>
			<td><button v-on:click="deleteUserRole(userRole.pivot.id)" class="btn btn-outline-dark btn-sm">Delete</button></td>
		</tr>
	</table>

	<p>Note, GNZ members are automatically given the 'club member' role for their club. Only add a 'club member' role to users who aren't actually a member e.g. someone who helps with the club</p>

</div>
</template>

<script>
export default {
	data: function() {
		return {
			roles: [],
			userRoles: [],
			orgs: [],
			newRole: null,
		}
	},
	props: ['userId'],
	created: function() {
		this.loadRoles();
		this.loadUserRoles();
	},
	methods: {
		loadRoles: function() {
			var that = this;
			window.axios.get('/api/v1/roles').then(function (response) {
				that.roles = response.data.data;
			});
		},
		addUserRole: function() {
			var data = { 'roleID': this.newRole }
			var that = this;
			window.axios.post('/api/v1/users/' + this.userId + '/roles', data).then(function (response) {
				messages.$emit('success', 'Role Added');
				that.loadUserRoles();
			});
		},
		deleteUserRole: function(roleUserID) {
			var that = this;
			window.axios.delete('/api/v1/role-user/' + roleUserID).then(function (response) {
				messages.$emit('success', 'Role Deleted');
				that.loadUserRoles();
			});
		},
		loadUserRoles: function() {
			var that = this;
			window.axios.get('/api/v1/users/' + this.userId + '/roles').then(function (response) {
				that.userRoles = response.data.data;
				that.loadOrgs();
			});
		},
		loadOrgs: function() {
			var that = this;
			window.axios.get('/api/v1/orgs/').then(function (response) {
				that.orgs = response.data.data;
			});
		},
		updateUserRole: function(roleUserID, orgID) {
			var data = {orgID: orgID}
			window.axios.post('/api/v1/role-user/' + roleUserID, data).then(function (response) {
				messages.$emit('success', 'Role Updated');
			});
		}

	}
}
</script>
