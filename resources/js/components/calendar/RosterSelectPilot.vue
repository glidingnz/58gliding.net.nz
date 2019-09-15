<template>
	<div>
		<div v-if="!memberChosen">
			<input v-model="memberSearch" class="form-control">
			<select v-model="selectedMember" v-show="searchResults.length>0" class="form-control">
				<option :value="null">{{searchResults.length}} result{{searchResults.length==1?'':'s'}}</option>
				<option :value="member" v-for="member in searchResults">{{member.first_name}} {{member.last_name}}</option>
			</select>
		</div>
		<div v-if="memberChosen && selectedMember">
			{{selectedMember.first_name}} {{selectedMember.last_name}}
			<button class="btn fa fa-cross" v-on:click="clearMember()"></button>
		</div>
	</div>
</template>


<script>
	import common from '../../mixins.js';

	export default {
		mixins: [common],
		props: ['member', 'day', 'duty'],
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
				this.saveMember();
			}
		},
		methods: {
			saveMember: function() {
				this.memberChosen = true;
				console.log(this.member);
				console.log(this.day);
				console.log(this.duty);
			},
			clearMember: function() {
				this.memberChosen = false;
				this.selectedMember = null;
				this.memberSearch = '';
			},
			searchMembers: function() {
				var that = this;
				if (this.memberSearch=='') {
					that.selectedMember = null;
					that.searchResults = [];
					return;
				}

				window.axios.get('/api/v1/members', {params: {"search":this.memberSearch}}).then(function (response) {
					that.searchResults = response.data.data;

					// select the first item in the list if possible
					// if (that.searchResults==0) {
					// 	that.selectedMember = null;
					// } else {

					// select the one and only member
					if (that.searchResults.length==1) {
						console.log('one person');
						that.selectedMember = that.searchResults[0];
						console.log(that.selectedMember);
					}
				});
			}
		}

	}
</script>