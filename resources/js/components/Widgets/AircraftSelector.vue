<template>
	<div class="selectAircraft">

			<input v-model="aircraftSearch" v-if="!selectedAircraft || edit" @keydown="aircraftSearchType" class="form-control" placeholder="Aircraft Search Rego or Type e.g. GOP...">

			<button class="btn btn-success" v-if="selectedAircraft && !edit" v-on:click="edit=true">
				{{selectedAircraft.rego}} &nbsp; {{selectedAircraft.model}} <div class="badge badge-light badge-pill ml-2">Change</div>
			</button>

			<span class="error" v-show="noResults">Aircraft not found</span>
			<select v-if="!selectedAircraft || edit" v-model="selectedAircraft" v-show="searchResults.length>0"  class="form-control" @change="selectAircraft()">
				<option :value="null">{{searchResults.length}} result{{searchResults.length==1?'':'s'}}...</option>
				<option :value="aircraft" v-for="aircraft in searchResults">{{aircraft.rego}} {{aircraft.model}} {{aircraft.manufacturer}}</option>
			</select>

	</div>
</template>


<script>
	import common from '../../mixins.js';

	export default {
		mixins: [common],
		props: ['value'],
		data() {
			return {
				selectedAircraft: null,
				aircraftSearch: '',
				searchResults: [],
				edit: false,
				noResults: false
			}
		},
		created: function () {
			if (this.value) {
				this.loadAircraft(this.value);
			}
			this.debouncedSave = _.debounce(this.searchAircrafts, 500);
		},
		watch: {
			value: function(newVal) {
				console.log('new val: ' + newVal);
				if (newVal!='' && newVal!=null) {
					this.loadAircraft(newVal);
				}
			}
		},
		methods: {
			aircraftSearchType: function(a, b) { 
				this.noResults = false;
				this.debouncedSave() 
			},
			selectAircraft: function() {
				this.$emit('selected', this.selectedAircraft);
				if (this.selectedAircraft) this.$emit('input', this.selectedAircraft.id);
				this.edit = false;
			},
			searchAircrafts: function() {
				var that = this;
				this.selectedAircraft = null;
				if (this.aircraftSearch=='') {
					that.searchResults = [];
					this.$emit('input', null);
					return;
				}

				window.axios.get('/api/v1/aircraft', {params: {"search":this.aircraftSearch}}).then(function (response) {
					that.searchResults = response.data.data;

					if (response.data.data.length==0) {
						that.noResults = true;
					}

					// select the one and only aircraft
					if (response.data.data.length==1) {
						that.selectedAircraft = that.searchResults[0];
						that.selectAircraft();
					} else {
						that.selectedAircraft=null;
					}
				});
			},
			loadAircraft: function(id) {
				var that = this;
				// if we're given an ID, try and load it
				if (id) {
					window.axios.get('/api/v1/aircraft/' + id).then(function (response) {
						that.selectedAircraft = response.data.data;
					});
				}
				
			}
		}

	}
</script>