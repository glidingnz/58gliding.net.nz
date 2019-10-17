<template>
	<div class="selectMember">

		<input v-model="memberSearch" class="form-control"  :tabindex="tabindex + 10" :ref="'add_' + tabindex">
		<span class="error" v-if="searchResults.length==0 && memberSearch!='' && !searching">No Results Found</span>
		<span v-if="searching" class="caption">Searching...</span>
		<select v-model="member" v-show="searchResults.length>0"  :tabindex="tabindex + 11" class="form-control" @change="selectMember()">
			<option :value="null">{{searchResults.length}} result{{searchResults.length==1?'':'s'}}</option>
			<option :value="member" v-for="member in searchResults">{{member.first_name}} {{member.last_name}}</option>
		</select>

	</div>
</template>


<script>
	import common from '../../mixins.js';

	export default {
		mixins: [common],
		props: ['orgId', 'day', 'duty', 'tabindex', 'searchAllClubs'],
		data() {
			return {
				memberSearch: '',
				member: null,
				searching: false,
				searchResults: []
			}
		},
		created: function () {
			this.debouncedSave = _.debounce(this.searchMembers, 500);
		},
		watch: {
			memberSearch: function(a, b) { 
				this.searching = this.memberSearch!='' ?  true : false;
				this.debouncedSave() 
			},
			searchAllClubs: function (a, b) { this.memberSearch='' }
		},
		methods: {
			selectMember: function() {
				var that = this;

				// create this roster item
				var roster = {
					'day_id': this.day.id,
					'day_date': this.day.day_date,
					'org_id': this.orgId,
					'duty_id': this.duty.id,
					'member_id': this.member.id
				}

				window.axios.post('/api/roster', roster).then(function (response) {
					if (response.data.success) {
						messages.$emit('success', that.member.first_name + ' added to Roster');
						that.$emit('add', response.data.data);
						that.searchResults = [];
						that.member = null;
						that.memberSearch = '';
					}
					
				});
			},
			searchMembers: function() {
				var that = this;

				if (this.memberSearch=='') {
					that.searchResults = [];
					return;
				}

				var params = {"search":this.memberSearch};

				if (!this.searchAllClubs) {
					params.org_id = this.orgId;
				}

				window.axios.get('/api/v1/members', {params: params}).then(function (response) {
					that.searching = false;
					that.searchResults = response.data.data;

					// select the one and only member
					if (response.data.data.length==1) {
						that.member = that.searchResults[0];
						that.selectMember();
					}
				});
			}
		}

	}
</script>