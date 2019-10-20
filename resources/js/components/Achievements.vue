<style>
	.badges {
		overflow: auto;
	}
	.badge-panel {
		float: left;
		vertical-align: top;
		height: 180px;
		text-align: center;
		padding-right: 5px;
		width: 230px;
	}
</style>

<template>
	<div>

		<div class="badges">
			<div v-for="result in results" class="badge-panel">
				<img v-bind:src="'/images/badges_256/' + result.badge.slug + '.png'" v-bind:alt="result.badge.name" width="128" height="128"><br>
				{{result.badge.name}}<br>
				<span v-if="result.badge_number">#</span>{{result.badge_number}} {{result.awarded_date}}
			</div>
		</div>

		<div v-if="results.length==0">
			None yet!
		</div>

	</div>
</template>


<script>
	import common from '../mixins.js';

	export default {
		mixins: [common],
		props: ['memberId'],
		data() {
			return {
				results: [],
				showEdit: false
			}
		},
		mounted() {
			this.load();
		},
		methods: {
			load: function() {
				var that = this;
				window.axios.get('/api/v1/achievements?member_id=' + this.memberId).then(function (response) {
					that.results = response.data.data;
				});
			}
		}
	}
</script>