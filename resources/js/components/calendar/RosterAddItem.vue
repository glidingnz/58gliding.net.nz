<template>
	<div class="selectMember">

		<input v-model="memberSearch" class="form-control">
		<select v-model="member" v-show="searchResults.length>0" class="form-control" @change="selectMember()">
			<option :value="null">{{searchResults.length}} result{{searchResults.length==1?'':'s'}}</option>
			<option :value="member" v-for="member in searchResults">{{member.first_name}} {{member.last_name}}</option>
		</select>

	</div>
</template>


<script>
	import common from '../../mixins.js';

	export default {
		mixins: [common],
		props: ['orgId', 'day', 'duty'],
		data() {
			return {
				memberSearch: '',
				member: null,
				searchResults: []
			}
		},
		created: function () {
			this.debouncedSave = _.debounce(this.searchMembers, 500);
		},
		watch: {
			memberSearch: function(a, b) { this.debouncedSave() },
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

				window.axios.get('/api/v1/members', {params: {"search":this.memberSearch}}).then(function (response) {
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