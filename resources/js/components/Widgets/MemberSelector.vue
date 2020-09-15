<template>
	<div class="selectMember">
		<input v-model="memberSearch" v-if="!selectedMember || edit" @keydown="memberSearchType" class="form-control" placeholder="Member Search..." >

		<button class="btn btn-success" v-if="selectedMember && !edit" v-on:click="edit=true">
			{{selectedMember.first_name}} {{selectedMember.last_name}}  &nbsp; {{selectedMember.nzga_number}} <div class="badge badge-light badge-pill ml-2">Change</div>
		</button>

		<span class="error" v-show="noResults">Member not found</span>
		<select v-if="!selectedMember || edit" v-model="selectedMember" v-show="searchResults.length>0"  class="form-control" @change="selectMember()">
			<option :value="null">{{searchResults.length}} result{{searchResults.length==1?'':'s'}}</option>
			<option :value="member" v-for="member in searchResults">{{member.first_name}} {{member.last_name}} {{member.city}} {{member.nzga_number}} <span v-if="member.membership_type=='Resigned'">(resigned)</span></option>
		</select>
	</div>
</template>


<script>
	import common from '../../mixins.js';

	export default {
		mixins: [common],
		props: ['value', 'resigned'],
		data() {
			return {
				selectedMember: null,
				memberSearch: '',
				searchResults: [],
				edit: false,
				noResults: false,
				memberId: null
			}
		},
		created: function () {
			if (this.value) {
				this.loadMember(this.value);
			}
			this.debouncedSave = _.debounce(this.searchMembers, 500);
		},
		watch: {
			value: function(newVal) {
				if (newVal!='' && newVal!=null) {
					this.loadMember(newVal);
				}
			}
		},
		methods: {
			memberSearchType: function(a, b) { 
				this.noResults = false;
				this.debouncedSave() 
			},
			selectMember: function() {
				this.$emit('selected', this.selectedMember);
				if (this.selectedMember) this.$emit('input', this.selectedMember.id);
				this.edit = false;
			},
			searchMembers: function() {
				var that = this;
				this.$emit('searching', this.memberSearch);

				if (this.memberSearch=='') {
					that.searchResults = [];
					this.$emit('input', null);
					this.$emit('selected', null);
					return;
				}

				window.axios.get('/api/v1/members', {params: {"search":this.memberSearch, 'ex_members':this.resigned, 'limit':100}}).then(function (response) {
					that.searchResults = response.data.data;

					if (response.data.data.length==0) {
						that.noResults = true;
					}

					// select the one and only member
					if (response.data.data.length==1) {
						that.selectedMember = that.searchResults[0];
						that.selectMember();
					} else {
						that.selectedMember=null;
					}
				});
			},
			loadMember: function(id) {
				var that = this;
				// if we're given an ID, try and load it
				window.axios.get('/api/v1/members/' + id).then(function (response) {
					that.selectedMember = response.data.data;
				});
			}
		}

	}
</script>