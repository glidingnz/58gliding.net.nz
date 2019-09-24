<style>
	.nav-container {
		padding-left: 0;
		padding-right: 0;
	}
</style>

<template>
	<nav class="nav-container container-fluid d-flex">

		<h1 class="mr-2">{{title}}</h1>

		<ul class="nav ml-auto nav-pills" v-if="Laravel.clubAdmin==true">
			<li class="nav-item" v-bind:class="{ active: active=='events' }">
				<a class="nav-link" v-bind:class="[active=='events' ? 'active' : '']" href="/calendar">Events</a>
			</li>
			<li class="nav-item" v-bind:class="{ active: active=='events' }">
				<a class="nav-link" v-bind:class="[active=='events' ? 'active' : '']" href="/calendar">Events</a>
			</li>
		</ul>

	</nav>
</template>


<script>
	import common from '../../mixins.js';

	export default {
		mixins: [common],
		props: ['active', 'title'],
		data() {
			return {
			}
		},
		methods: {
		}

	}
</script>