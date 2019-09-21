<style>
	.showMember .btn {
		padding: 0;
	}
	.nav-container {
		padding-left: 0;
		padding-right: 0;
	}
</style>

<template>
	<nav class="nav-container container-fluid d-flex">

		<h1 class="mr-2">{{title}}</h1>

		<ul class="nav ml-auto nav-pills" v-if="Laravel.clubAdmin==true">
			<li class="nav-item" v-bind:class="{ active: active=='calendar' }">
				<a class="nav-link" v-bind:class="[active=='calendar' ? 'active' : '']" href="/calendar">Calendar</a>
			</li>
			<li class="nav-item" v-bind:class="{ active: active=='edit-calendar' }">
				<a class="nav-link" v-bind:class="[active=='edit-calendar' ? 'active' : '']"  href="/calendar/edit">Edit Flying Days</a>
			</li>
			<li class="nav-item" v-bind:class="{ active: active=='edit-roster' }">
				<a class="nav-link" v-bind:class="[active=='edit-roster' ? 'active' : '']"  href="/calendar/roster/edit">Edit Roster</a>
			</li>
			<li class="nav-item" v-bind:class="{ active: active=='edit-duties' }">
				<a class="nav-link" v-bind:class="[active=='edit-duties' ? 'active' : '']" href="/calendar/duties/edit">Configure Duties</a>
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