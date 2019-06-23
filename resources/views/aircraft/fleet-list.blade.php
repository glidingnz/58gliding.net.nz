@extends('layouts.app')

@section('content')

<style>
.results-title {
	margin-top: 0;
	margin-bottom: 20px;
}
.btn-group {
	margin-bottom: 20px;
}
</style>


<div class="container" id="fleet">
	
	<h1>Fleet</h1>


	<table class="table table-striped results-table ">
		<tr>
			<th>Rego</th>
			<th>Contest ID</th>
			<th>Manufacturer</th>
			<th>Model</th>
			<th>Class</th>
			<th>Seats</th>
			<th></th>
			<th></th>
			<th></th>
		</tr>
		<tr v-for="result in results">
			<td>@{{ result.aircraft.rego }}</td>
			<td>@{{ result.aircraft.contest_id }}</td>
			<td>@{{ result.aircraft.manufacturer }}</td>
			<td>@{{ result.aircraft.model }}</td>
			<td>@{{ result.aircraft.class }}</td>
			<td>@{{ result.aircraft.seats }}</td>
			<td>
				<a href="https://www.caa.govt.nz/Script/AirReg3.asp?Mark=@{{ result.aircraft.rego.substring(3,6) }}">CAA</a>
			</td>
			<td>
				<a href="/aircraft/@{{result.aircraft.rego}}" class="btn btn-primary btn-xs">Details</a>
			</td>
			<td>
				<button class="btn btn-primary btn-xs" v-on:click="deleteFleetItem(result.id)">Delete</button>
			</td>
		</tr>
	</table>

	<h2>Add Aircraft to Fleet</h2>

	<div class="form-inline">
		<div class="form-group">
			<label for="add-search">Registration e.g. ZK-GXP</label>
			<input v-model="addSearch" id="add-search" class="form-control" placeholder="e.g. ZK-GXP" type="text" style="width: auto; margin-right: 10px;">
			<button class="form-control" style="width: auto;" v-on:click="insert()">Add</button>
		</div>
	</div>
	

</div>

@endsection



@section('scripts')

<script>
new Vue({
	el: '#fleet',
	data: {
		addSearch: 'ZK-',
		results: []
	},
	created: function() {
		this.load();
	},
	methods: {
		load: function() {
			this.$http.get('/api/v1/orgs/<?php echo $org->id; ?>/fleet').then(function (response) {
				console.log(response.data.data);
				this.results = response.data.data;
			});
		},
		insert: function() {
			// first find the ID of the aircraft from the search query
			this.$http.get('/api/v1/aircraft/' + this.addSearch).then(function (response) {
				if (response.status==200) {
					// add the new aircraft
					var data = {
						aircraft_id: response.data.data.id
					};

					this.$http.post('/api/v1/orgs/<?php echo $org->id; ?>/fleet', data).then(function (response) {
						// reload the results
						this.load();
					});
				}
			});
		},
		deleteFleetItem: function(fleet_id) {
			console.log(fleet_id);

			this.$http.delete('/api/v1/orgs/<?php echo $org->id; ?>/fleet/' + fleet_id).then(function (response) {
				console.log(response);
				// reload the results
				this.load();
			});
		}
	}
});
</script>

@endsection
