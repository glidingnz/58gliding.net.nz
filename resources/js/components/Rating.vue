<style>
.fa-exclamation-circle {
	color: #A00;
}
</style>

<template>
	<div>
		<div v-if="allowsEdit">
		</div>

		<div v-if="loading">
			Loading...
		</div>

	</div>
</template>


<script>
	import common from '../mixins.js';
	import moment from 'moment';
	import VCalendar from 'v-calendar';
	Vue.prototype.$moment = moment;

	export default {
		mixins: [common],
		props: ['ratingId', 'memberId', 'allowsEdit'],
		data() {
			return {
				rating: [],
				loaded: false,
				loading: false
			}
		},
		mounted() {
			this.load();
		},
		methods: {
			load: function() {
				var that = this;
				window.axios.get('/api/v1/members/' + this.memberId + '/ratings/' + this.ratingId).then(function (response) {
					that.loaded = true;
					that.rating = response.data.data;
				});
			}
		}
	}
</script>
