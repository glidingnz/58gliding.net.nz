
<template>
	<tr>
		<td class="date">{{renderDate(day_date)}}</td>
		<td class="align-middle ">
			<autosize-textarea>
				<textarea type="text" class="form-control" v-model="description" rows="1"></textarea>
			</autosize-textarea>
		</td>
		<td class="align-baseline ">
			<div class="form-check-inline">
				<input :id="'towing'+id" type="checkbox" v-model="towing" class="form-check-input">
				<label :for="'towing'+id">Tow</label>
			</div>
			<div class="form-check-inline">
				<input :id="'winching'+id" type="checkbox" v-model="winching" class="form-check-input">
				<label :for="'winching'+id">Winch</label>
			</div>
			<div class="form-check-inline">
				<input :id="'trialflights'+id" type="checkbox" v-model="trialflights" class="form-check-input">
				<label :for="'trialflights'+id">Trial Flights</label>
			</div>
			<div class="form-check-inline">
				<input :id="'training'+id" type="checkbox" v-model="training" class="form-check-input">
				<label :for="'training'+id">Training</label>
			</div>
		</td>
		<td class="align-middle">
			<div class="form-row form-check-inline">
				<div class="col-auto">
					<input :id="'cancelled'+id" type="checkbox" v-model="cancelled" class="form-check-input ">
				</div>
				
				<div class="col-auto">
					<input type="text" v-model="cancelled_reason" :disabled="!cancelled" class="form-control" placeholder="Reason">
				</div>
			</div>

		</td>
	</tr>
</template>

<script>
	import common from '../../mixins.js';
	import moment from 'moment';
	Vue.prototype.$moment = moment;

	export default {
		mixins: [common],
		props: ['row', 'orgId'],
		data() {
			return {
				id: this.row.id,
				day_date: this.row.day_date,
				description: this.row.description,
				towing: this.row.towing,
				winching: this.row.winching,
				trialflights: this.row.trialflights,
				training: this.row.training,
				competition: this.row.competition,
				cancelled: this.row.cancelled,
				cancelled_reason: this.row.cancelled_reason
			}
		},
		created: function () {
			this.debouncedSaveRow = _.debounce(this.saveRow, 500)
		},
		watch: {
			day_date: function(a, b) { this.debouncedSaveRow() } ,
			description: function(a, b) { this.debouncedSaveRow() } ,
			towing: function(a, b) { this.debouncedSaveRow() } ,
			winching: function(a, b) { this.debouncedSaveRow() } ,
			trialflights: function(a, b) { this.debouncedSaveRow() } ,
			training: function(a, b) { this.debouncedSaveRow() } ,
			competition: function(a, b) { this.debouncedSaveRow() } ,
			cancelled: function(a, b) { this.debouncedSaveRow() } ,
			cancelled_reason: function(a, b) { this.debouncedSaveRow() } ,
		},
		methods: {
			saveRow: function() {
				var that = this;
				// insert a day into the database
				var data = {
					id: this.id,
					org_id: this.orgId,
					day_date: this.$moment(this.day_date).format('YYYY-MM-DD'),
					description: this.description,
					towing: this.towing,
					winching: this.winching,
					trialflights: this.trialflights,
					training: this.training,
					competition: this.competition,
					cancelled: this.cancelled,
					cancelled_reason: this.cancelled_reason
				};
				window.axios.put('/api/days/' + this.id, data).then(function (response) {
					that.$emit('rowupdated', response.data.data);
				});
			},
			renderDate: function(date) {
				return this.$moment(date).format('ddd, MMM Do YY');
			}
		}

	}
</script>