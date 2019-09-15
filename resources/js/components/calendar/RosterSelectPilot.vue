<template>
	<div>
		<div v-if="!memberChosen">
			<input v-model="memberSearch" class="form-control" :tabindex="tabindex" :ref="'in' + tabindex">
			<select v-model="selectedMember" v-show="searchResults.length>0" :tabindex="tabindex+1" class="form-control">
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
		props: ['member', 'day', 'duty', 'tabindex'],
		data() {
			return {
				memberSearch: '',
				searchResults: [],
				selectedMember: null,
				memberChosen: false
			}
		},
		created: function () {
			this.debouncedSave = _.debounce(this.searchMembers, 500);
		},
		watch: {
			memberSearch: function(a, b) { this.debouncedSave() },
			selectedMember: function() {
				if (this.selectedMember!=null) {
					console.log(this.$refs);

					this.$nextTick(() => this.$refs['cl' + this.tabindex].focus());
					
					this.saveMember();
				}
			}
		},
		methods: {
			saveMember: function() {
				this.memberChosen = true;
				console.log('saving');
				console.log(this.member);
				console.log(this.day);
				console.log(this.duty);
			},
			clearMember: function() {
				this.$emit('cleared-member');
				this.selectedMember = null;
				this.memberChosen = false;
				this.searchResults = [];
				this.memberSearch = '';
			},
			searchMembers: function() {
				console.log('CALLED searchMembers');
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
					}
				});
			}
		}

	}
</script>