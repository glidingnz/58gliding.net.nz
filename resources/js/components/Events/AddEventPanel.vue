<template>
<div>

	<div class="custom-modal" v-show="show" v-on:click="closeCustomModal()" @keyup.esc="closeCustomModal()" tabindex="0">
		<div class="inner" v-on:click.stop="">

			<button v-on:click="closeCustomModal()" class="btn btn-outline-dark float-right">Cancel</button>
			
			<div class="form-group">
				<h2>Add Event</h2>
			</div>

			<div class="form-group">
				<label>Event Name</label> <span class="error" v-show="showNameRequired">Name is required</span>
				<input type="text" class="form-control" v-model="newEventName" ref="newName">
			</div>


			<div class="mr-2 " role="group">
				<button v-for="eventType in eventTypes()" type="button" class="btn btn-xs mr-2 mb-2" v-bind:class="[ newEventType==eventType.code ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="newEventType=eventType.code">{{eventType.name}}</button>
			</div>

			<div class="form-group form-inline">
				<label class="mr-3">Start Date</label>
				<v-date-picker v-model="newEventDate" :locale="{ id: 'nz', firstDayOfWeek: 2, masks: { weekdays: 'WW', L: 'DD/MM/YYYY' } }" :popover="{ visibility: 'click' }"></v-date-picker>
			</div>

			<div class="form-group form-inline">
				<label class="mr-3" for="same_day">
					<input class="form-control mr-1" id="same_day" type="checkbox" v-model="endDate" v-on:click="endDate = !endDate; newEventEndDate=newEventDate"> 
					End Date
				</label>
				<v-date-picker v-show="endDate" v-model="newEventEndDate" :locale="{ id: 'nz', firstDayOfWeek: 2, masks: { weekdays: 'WW', L: 'DD/MM/YYYY' } }" :popover="{ visibility: 'click' }"></v-date-picker>
				<span v-show="endDate" class="ml-2">{{dateDiffDays(newEventDate, newEventEndDate)}}</span>
			</div>


			<div class="form-group mt-2">
				<button v-on:click="addEvent()" class="btn btn-outline-dark">Add Event</button>
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
			newEventName: '',
			newEventDate: null,
			newEventEndDate: null,
			newEventType: 'other',
			endDate: false,
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
					"type": this.newEventType,
					"start_date":  this.$moment(this.newEventDate).format('YYYY-MM-DD'),
					"end_date": null,
					"org_id" : this.orgId
				}
				// check if we have an end date or not
				if (this.endDate && this.newEventEndDate!='') data.end_date = this.$moment(this.newEventEndDate).format('YYYY-MM-DD');

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
