/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./vendor/native.history.js');
require('./bootstrap');

window.Vue = require('vue');
window.messages = new Vue();

// Passport Components
Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue').default
);

Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue').default
);

Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue').default
);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

Vue.component('messages', require('./components/Messages.vue').default);

Vue.component('orgs-component', require('./components/Orgs.vue').default);
Vue.component('aircraft', require('./components/Aircraft.vue').default);
Vue.component('edit-aircraft', require('./components/EditAircraft.vue').default);
Vue.component('members', require('./components/Members.vue').default);
Vue.component('member', require('./components/Member.vue').default);
Vue.component('edit-member', require('./components/EditMember.vue').default);
Vue.component('tracking', require('./components/tracking/Tracking.vue').default);
Vue.component('tracking2', require('./components/tracking/Tracking2.vue').default);
Vue.component('trackd', require('./components/Trackd.vue').default);
Vue.component('altitude-chart', require('./components/AltitudeChart.vue').default);
Vue.component('users-list', require('./components/admin/UsersList.vue').default);
Vue.component('user-roles', require('./components/admin/UserRoles.vue').default);
Vue.component('achievements', require('./components/Achievements.vue').default);
Vue.component('edit-achievements', require('./components/EditAchievements.vue').default);
Vue.component('ratings', require('./components/Ratings.vue').default);
Vue.component('rating', require('./components/Rating.vue').default);
Vue.component('ratings-report', require('./components/RatingsReport.vue').default);
Vue.component('flying-calendar', require('./components/calendar/Calendar.vue').default);
Vue.component('edit-calendar', require('./components/calendar/CalendarEdit.vue').default);
Vue.component('edit-roster', require('./components/calendar/RosterEdit.vue').default);
Vue.component('edit-calendar-row', require('./components/calendar/CalendarEditRow.vue').default);
Vue.component('edit-duties', require('./components/calendar/DutiesEdit.vue').default);
Vue.component('waypoints', require('./components/Waypoints.vue').default);
Vue.component('roster-edit-item', require('./components/calendar/RosterEditItem.vue').default);
Vue.component('roster-add-item', require('./components/calendar/RosterAddItem.vue').default);
Vue.component('calendar-nav', require('./components/calendar/CalendarNav.vue').default);
Vue.component('entry-add', require('./components/events/EntryAdd.vue').default);
Vue.component('entry-edit', require('./components/events/EntryEdit.vue').default);
Vue.component('edit-event', require('./components/events/EventEdit.vue').default);
Vue.component('view-event', require('./components/events/EventView.vue').default);
Vue.component('events', require('./components/events/Events.vue').default);
Vue.component('add-event-panel', require('./components/events/AddEventPanel.vue').default);
Vue.component('fleets', require('./components/aircraft/Fleets.vue').default);
Vue.component('add-fleet-panel', require('./components/aircraft/AddFleetPanel.vue').default);
Vue.component('edit-fleet', require('./components/aircraft/EditFleet.vue').default);
Vue.component('member-add', require('./components/members/AddMember.vue').default);
Vue.component('edit-settings', require('./components/admin/EditSettings.vue').default);
Vue.component('member-types', require('./components/admin/MemberTypes.vue').default);
Vue.component('admin-menu', require('./components/admin/AdminMenu.vue').default);
Vue.component('admin-imports', require('./components/admin/AdminImports.vue').default);

/** common components  */
Vue.component('autosize-textarea', require('./components/widgets/AutosizeTextarea.vue').default);
Vue.component('org-selector', require('./components/widgets/OrgSelector.vue').default);
Vue.component('member-selector', require('./components/widgets/MemberSelector.vue').default);
Vue.component('aircraft-selector', require('./components/widgets/AircraftSelector.vue').default);

Vue.component(
	'passport-clients',
	require('./components/passport/Clients.vue').default
);

Vue.component(
	'passport-authorized-clients',
	require('./components/passport/AuthorizedClients.vue').default
);

Vue.component(
	'passport-personal-access-tokens',
	require('./components/passport/PersonalAccessTokens.vue').default
);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

 Vue.prototype.Laravel = window.Laravel;
 
const app = new Vue({
    el: '#app',
});

