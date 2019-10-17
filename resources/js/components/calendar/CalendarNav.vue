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

		<div class="ml-auto">

			<ul class="nav nav-pills">
				<li class="nav-item" v-bind:class="{ active: active=='calendar' }">
					<a class="nav-link" v-bind:class="[active=='calendar' ? 'active' : '']" href="/flying-days">Flying Days</a>
				</li>
				<li v-if="Laravel.clubAdmin==true" class="nav-item" v-bind:class="{ active: active=='edit-calendar' }">
					<a class="nav-link" v-bind:class="[active=='edit-calendar' ? 'active' : '']"  href="/flying-days/edit">Edit Flying Days</a>
				</li>
				<li v-if="Laravel.clubAdmin==true" class="nav-item" v-bind:class="{ active: active=='edit-roster' }">
					<a class="nav-link" v-bind:class="[active=='edit-roster' ? 'active' : '']"  href="/flying-days/roster/edit">Edit Roster</a>
				</li>
				<li v-if="Laravel.clubAdmin==true" class="nav-item" v-bind:class="{ active: active=='edit-duties' }">
					<a class="nav-link" v-bind:class="[active=='edit-duties' ? 'active' : '']" href="/flying-days/duties/edit">Configure Duties</a>
				</li>
			</ul>
		</div>


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