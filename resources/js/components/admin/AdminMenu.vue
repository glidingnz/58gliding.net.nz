<template>
	<div>

		<div v-if="clubAdmin" class="mb-4">
			<p>{{org.name}} Admin</p>

			<div class="list-group">
				<a class="list-group-item" v-bind:class="[page==menuItem.page ? 'active' : '']" :href="menuItem.url" v-for="menuItem in clubMenu">{{menuItem.label}}</a>
			</div>
		</div>

		<div v-if="admin">
			<p>Global Admin</p>

			<div class="list-group">
				<a class="list-group-item" v-bind:class="[page==menuItem.page ? 'active' : '']" :href="menuItem.url" v-for="menuItem in adminMenu">{{menuItem.label}}</a>
			</div>
		</div>

	</div>
</template>


<script>

	/**
	 * To add a new admin menu item:
	 * - Add into the menu array below
	 * - Create a Vue component in the 'admin' folder
	 * - Add to app.js and ensure the 'page' parameter matches the tag name.
	 * It will automatically load. No web route or PHP page is needed.
	 * Some pages do have their own web routes, this is fine too if needed.
	 */

	import common from '../../mixins.js';

	export default {
		mixins: [common],
		props: ['page'],
		data() {
			return {
				org: null,
				admin: false,
				clubAdmin: false,
				adminMenu: [
					{page: 'users', label: 'Users', url: '/admin/users'},
					{page: 'oauth', label: 'OAuth', url: '/oauth'},
					{page: 'admin-imports', label: 'Admin Imports', url: '/admin/admin-imports'},
				],
				clubMenu: [
					{page: 'member-types', label: 'Club Member Types', url: '/admin/member-types'},
					{page: 'general-settings', label: 'General Settings', url: '/admin/general-settings'},
				]
			}
		},
		mounted() {
			this.org = window.Laravel.org;
			this.admin = window.Laravel.admin;
			this.clubAdmin = window.Laravel.clubAdmin;
		}
	}
</script>