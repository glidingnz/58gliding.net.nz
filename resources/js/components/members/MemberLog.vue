<template><div>

	
	<h1 v-if="member" class="results-title"><a href="/members">Members</a> &raquo; <a :href="'/members/' + member.id">{{member.first_name}} {{member.last_name}}</a> &raquo; Change Log</h1>

	<ul v-if="memberLogs" class="list-group">
		<li v-for="memberLog in memberLogs" class="list-group-item">
			<span :title="memberLog.created" class="badge badge-primary badge-pill float-right">{{dateToNow(memberLog.created)}}</span>
			<h6>{{memberLog.description}}</h6>
			{{memberLog.field}} <span class="text-secondary">from</span> '{{memberLog.oldval}}' <span class="text-secondary">to</span> '{{memberLog.newval}}'
		</li>
	</ul>

</div></template>

<script>
	import common from '../../mixins.js';

	export default {
		mixins: [common],
		props: ['memberId'],
		data() {
			return {
				member: null,
				memberLogs: null
			}
		},
		mounted() {
			this.loadMember();
			this.loadMemberLog();
		},
		methods: {
			loadMember: function()
			{
				var that=this;
				window.axios.get('/api/v1/members/'+ this.memberId).then(function (response) {
					that.member = response.data.data;
				});
			},
			loadMemberLog: function()
			{
				var that=this;
				window.axios.get('/api/v1/members/'+ this.memberId +'/log').then(function (response) {
					that.memberLogs = response.data.data;
				});
			}
		}
	}
</script>