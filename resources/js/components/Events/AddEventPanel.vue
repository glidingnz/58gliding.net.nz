<style>
.custom-modal {
	width: 100%;
	height: 100%;
	position: fixed;
	top: 0;
	left: 0;
	background-color: rgba(0,0,0,0.7);
	z-index: 999;
	overflow: scroll;
}
.custom-modal .inner {
	width: 80%;
	max-width: 500px;
	margin: 100px auto 0 auto;
	background-color: #EEE;
	padding: 20px;
	-webkit-box-shadow: 0px 6px 15px 7px rgba(0,0,0,0.27); 
	box-shadow: 0px 6px 15px 7px rgba(0,0,0,0.27);
	border-radius: 10px;
	overflow: scroll;
}
</style>

<template>
<div>

	<div class="custom-modal" v-show="show" v-on:click="closeCustomModal()" @keyup.esc="closeCustomModal()" tabindex="0" v-if="Laravel.clubAdmin==true">
		<div class="inner" v-on:click.stop="">

			<button v-on:click="closeCustomModal()" class="btn btn-outline-dark float-right">Cancel</button>
			
			<div class="form-group">
				<h2>Add Event</h2>
			</div>

			<div class="form-group">
				<label>Event Name</label> <span class="error" v-show="showNameRequired">Name is required</span>
				<input type="text" class="form-control" v-model="newEventName" ref="newName">
			</div>

			<div class="form-group">
				<label>Event Date</label>
				<v-date-picker v-model="newEventDate" :locale="{ id: 'nz', firstDayOfWeek: 2, masks: { weekdays: 'WW', L: 'DD/MM/YYYY' } }" :popover="{ visibility: 'click' }"></v-date-picker>
			</div>

			<div class="form-group">
				<button v-on:click="addEvent()" class="btn btn-outline-dark">Add Event</button>
			</div>

		</div>
	</div>

</div>
</template>

<script>
export default {
	data: function() {
		return {
			showNameRequired: false,
			newEventName: '',
			newEventDate: null
		}
	},
	props: ['orgId', 'show', 'date'],
	created: function() {
		if (this.date) this.newEventDate = this.$moment(this.date).toDate();
		else this.newEventDate = this.$moment().toDate();
	},
	methods: {
		openCustomModal: function(day_date) {
			this.show = true;
			this.$nextTick(() => this.$refs.newName.focus());
		},
		closeCustomModal: function() {
			this.$emit('closeModal');
		},
		addEvent: function() {
			var that = this;
			if (this.newEventName=='') {
				messages.$emit('error', 'A name is required');
				this.showNameRequired = true;
			}
			else
			{
				var data = {
					"name": this.newEventName,
					"start_date":  this.$moment(this.newEventDate).format('YYYY-MM-DD'),
					"org_id" : this.orgId
				}
				window.axios.post('/api/events', data).then(function (response) {
					messages.$emit('success', 'Event ' + that.newEventName + ' added');
					that.closeCustomModal();
					that.$emit('eventAdded', response.data.data);
				});
			}
		}
	}
}
</script>
