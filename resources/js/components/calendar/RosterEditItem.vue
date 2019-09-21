<style>
	.showMember .btn {
		padding: 0;
	}
</style>

<template>
	<div class="showMember">
		<div v-if="member">
			<div v-if="roster!=null">
				<button class="btn fa fa-times-circle" v-on:click="deleteRosterItem()"></button>
				{{member.first_name}} {{member.last_name}}
			</div>
		</div>
	</div>
</template>


<script>
	import common from '../../mixins.js';

	export default {
		mixins: [common],
		props: ['roster', 'member'],
		data() {
			return {
				memberSearch: '',
				selectedMember: null,
			}
		},
		methods: {
			deleteRosterItem: function() {
				var that = this;
				window.axios.delete('/api/roster/' + this.roster.id).then(function (response) {
					messages.$emit('success', 'Deleted');
					that.$emit('delete');
				});
			}
		}

	}
</script>