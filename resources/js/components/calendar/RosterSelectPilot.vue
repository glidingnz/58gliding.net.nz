<style>
	.selectMember .btn {
		padding: 0;
	}
</style>

<template>
	<div class="selectMember">
		<div v-if="!memberChosen">
			<input v-model="memberSearch" class="form-control" :tabindex="tabindex" :ref="'in' + tabindex">
			<select v-model="selectedMember" v-show="searchResults.length>0" :tabindex="tabindex+1" class="form-control" @change="foundMember()">
				<option :value="null">{{searchResults.length}} result{{searchResults.length==1?'':'s'}}</option>
				<option :value="member" v-for="member in searchResults">{{member.first_name}} {{member.last_name}}</option>
			</select>
		</div>
		<div v-if="memberChosen && selectedMember">
			<button class="btn fa fa-times-circle" v-on:click="clearMember()" :ref="'cl' + tabindex" :tabindex="tabindex+2"></button>
			{{selectedMember.first_name}} {{selectedMember.last_name}}
		</div>

	</div>
</template>


<script>
	import common from '../../mixins.js';

	export default {
		mixins: [common],
		props: ['orgId', 'roster', 'day', 'duty', 'tabindex'],
		data() {
			return {
				memberSearch: '',
				searchResults: [],
				selectedMember: null,
				selectedRoster: null,
				memberChosen: false
			}
		},
		created: function () {
			this.debouncedSave = _.debounce(this.searchMembers, 500);
		},
		watch: {
			memberSearch: function(a, b) { this.debouncedSave() },
			roster: {
				immediate: true,
				handler(newVal, oldVal) {
					var roster_array = this.getRosterItem();
					if ( roster_array.length>0 ) {
						this.selectedMember = roster_array[0].member;
						this.selectedRoster = roster_array[0];
						this.memberChosen = true;
					}
				}
			}
		},
		methods: {
			foundMember: function() {
				if (this.selectedMember!=null) {
					// move the focus to the close icon, so tab goes to the next item easily
					this.$nextTick(() => this.$refs['cl' + this.tabindex].focus());
					this.saveMember();
				}
			},
			saveMember: function() {
				var that=this;

				this.memberChosen = true;

				var data = {
					'day_id': this.day.id,
					'day_date': this.day.day_date,
					'org_id': this.orgId,
					'duty_id': this.duty.id,
					'member_id': this.selectedMember.id
				}

				window.axios.post('/api/roster', data).then(function (response) {
					console.log(response);

					if (response.data.success) {
						messages.$emit('success', 'Saved');
						that.selectedRoster = response.data.data;
					}
					
				});
			},
			clearMember: function() {


				this.selectedMember = null;
				this.memberChosen = false;
				this.searchResults = [];
				this.memberSearch = '';

				// move the focus to the input box for re-searching
				this.$nextTick(() => this.$refs['in' + this.tabindex].focus());

				this.deleteRosterItem();
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
						that.selectedMember = that.searchResults[0];
						that.foundMember();
					}
				});
			},
			getRosterItem: function() {
				var that = this;
				var result = this.roster.filter(function(rost) {
					if (rost.day_id==that.day.id && rost.duty_id==that.duty.id) {
						return true;
					}
					return false;
				});
				return result;
			},
			deleteRosterItem: function() {
				window.axios.delete('/api/roster/' + this.selectedRoster.id).then(function (response) {
						messages.$emit('success', 'Deleted');
				});
			}
		}

	}
</script>