<template>
<div>
	
	<calendar-nav active="edit-duties" title="Edit Duties"></calendar-nav>

	<p>These are the default duties added to a day on the roster. You can add one off duties on the roster itself.</p>

	<table class="table table-striped">
		<tr>
			<td>
				<div class="d-flex">
					<div class="col-4">Add New Duty:</div>
					<input type="text" v-model="newDutyName" class="form-control inline ml-auto" size="10">
				</div>
			</td>
			<td><button v-on:click="createDuty()" type="submit" class="btn btn-outline-dark">Add Duty</button></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<th>Duty Name</th>
			<th>Save</th>
			<th>Delete</th>
		</tr>
		<tr v-for="duty in duties">
			<td><input type="text" v-model="duty.name" class="form-control" v-on:blur="updateDuty(duty)"></td>
			<td><button v-on:click="updateDuty(duty)" class="btn btn-outline-dark btn-sm">Save</button></td>
			<td><button v-on:click="deleteDuty(duty)" class="btn btn-outline-dark btn-sm">Delete</button></td>
		</tr>
	</table>

</div>
</template>

<script>
export default {
	data: function() {
		return {
			duties: [],
			newDutyName: '',
		}
	},
	props: ['orgId'],
	created: function() {
		this.load();
	},
	methods: {
		load: function() {
			var that = this;
			window.axios.get('/api/duties/?org_id=' + this.orgId).then(function (response) {
				that.duties = response.data.data;
			});
		},
		createDuty: function() {
			var that = this;

			var data = {
				org_id: this.orgId,
				name: this.newDutyName 
			};

			window.axios.post('/api/duties', data).then(function (response) {
				that.load();
				messages.$emit('success', 'Duty Added');
			});
		},
		updateDuty: function(duty) {
			window.axios.put('/api/duties/' + duty.id, duty).then(function (response) {
				messages.$emit('success', 'Duty Updated');
			});
		},
		deleteDuty: function(duty) {
			var that = this;
			window.axios.delete('/api/duties/' + duty.id).then(function (response) {
				messages.$emit('success', 'Duty Deleted');
				that.load();
			});
		},

	}
}
</script>
