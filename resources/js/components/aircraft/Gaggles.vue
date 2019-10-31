<template>
<div>
	<h1><a href="/aircraft">Aircraft</a> &raquo; Gaggles</h1>

	<table class="table table-striped">
		<tr>
			<th>Name</th>
		</tr>
		<tr v-for="gaggle in gaggles">
			<td>{{gaggle.name}}</td>
		</tr>
	</table>
</div>
</template>



<script>
	import common from '../../mixins.js';

	export default {
		mixins: [common],
		data() {
			return {
				gaggles: [],
				showEdit: false,
				showAdmin: false
			}
		},
		mounted() {
			this.load();
			if (window.Laravel.allowsEdit==true) this.showEdit=true;
			if (window.Laravel.admin==true) this.showAdmin=true;
		},
		methods: {
			load: function() {
				var that=this;
				window.axios.get('/api/v1/gaggles', {params: this.state}).then(function (response) {
					that.gaggles = response.data.data;
				});
			}
		}
	}
</script>