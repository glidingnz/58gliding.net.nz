<template>
<div>

	<div class="custom-modal" v-show="show" v-on:click="closeCustomModal()" @keyup.esc="closeCustomModal()" tabindex="0">
		<div class="inner" v-on:click.stop="">

			<button v-on:click="closeCustomModal()" class="btn btn-outline-dark float-right">Cancel</button>
			
			<div class="form-group">
				<h2>Add Fleet</h2>
			</div>

			<div class="form-group">
				<label>Fleet Name</label> <span class="error" v-show="showNameRequired">Name is required</span>
				<input type="text" class="form-control" v-model="name" ref="newName">
			</div>


			<div class="form-group mt-2">
				<button v-on:click="addFleet()" class="btn btn-outline-dark">Add Fleet</button>
			</div>

		</div>
	</div>

</div>
</template>

<script>
import common from '../../mixins.js';
export default {
	mixins: [common],
	data: function() {
		return {
			showNameRequired: false,
			name: '',
		}
	},
	props: ['orgId', 'show'],
	created: function() {
		
	},
	methods: {
		openCustomModal: function(day_date) {
			this.show = true;
			this.$nextTick(() => this.$refs.newName.focus());
		},
		closeCustomModal: function() {
			this.$emit('closeModal');
		},
		addFleet: function() {
			var that = this;
			if (this.name=='') {
				messages.$emit('error', 'A name is required');
				this.showNameRequired = true;
			}
			else
			{
				var data = {
					"name": this.name,
					"org_id": this.orgId
				}

				window.axios.post('/api/v1/fleets', data).then(function (response) {
					messages.$emit('success', 'Fleet ' + that.name + ' added');
					that.closeCustomModal();
					that.$emit('fleetAdded', response.data.data);

				});
			}
		}
	}
}
</script>
