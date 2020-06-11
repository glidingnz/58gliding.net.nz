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

		<p v-if="allowsEdit"><a class="btn btn-outline-dark"  :href="'/members/' + memberId + '/achievements/edit'"">Edit Achievements</a></p>

		<div v-if="achievements.length==0">
			<p>No achievements yet!</p>
		</div>

		<div class="badges">
			<div v-for="result in achievements" class="badge-panel">
				<a :href="'/images/badges_512/' + result.badge.slug + '.png'"><img v-bind:src="'/images/badges_256/' + result.badge.slug + '.png'" v-bind:alt="result.badge.name" width="128" height="128"></a><br>
				{{result.badge.name}}<br>
				<span v-if="result.badge_number">#</span>{{result.badge_number}} {{result.awarded_date}}
			</div>
			<div v-for="badge in unachieved" class="badge-panel">
				<img v-bind:src="'/images/badges_256/' + badge.slug + '.png'" v-bind:alt="badge.name" width="128" height="128" style="opacity: .1"><br>
				{{badge.name}}
			</div>
		</div>

	</div>
</template>


<script>
	import common from '../mixins.js';

	export default {
		mixins: [common],
		props: ['memberId', 'allowsEdit'],
		data() {
			return {
				achievements: [],
				badges: [],
				awardsOfficer: false,
				clubAdmin: false
			}
		},
		mounted() {
			// get permissions we need to show things
			if (window.Laravel.awardsOfficer) this.awardsOfficer=true;
			if (window.Laravel.clubAdmin) this.clubAdmin=true;
			console.log(this.allowsEdit);
			this.load();
		},
		computed: {
			unachieved: function() {
				var that = this;
				return that.badges.filter(function(badge) {
					var found = that.achievements.filter(achievement => (achievement.badge_id === badge.id));
					if (found.length>0) return false;
					return true;
				});
			}
		},
		methods: {
			load: function() {
				var that = this;

				// get all badges
				window.axios.get('/api/v1/badges').then(function (response) {
					that.badges = response.data.data;
				});

				// get this members achievements
				window.axios.get('/api/v1/achievements?member_id=' + this.memberId).then(function (response) {
					that.achievements = response.data.data;
				});
			}
		}
	}
</script>