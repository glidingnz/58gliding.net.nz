<style>
	.event-details .card-body {
		font-size: 120%;
	}
</style>

<template>
<div>
	
	<div class="float-right">
		<a class="btn btn-outline-dark mr-2" :href="'/events/' + event.slug + '/edit'" v-if="event.can_edit">Edit</a>
		<!-- <a class="btn btn-outline-dark" :href="'/events/' + event.slug + '/delete'" v-if="event.can_edit">Delete</a> -->
	</div>

	<h1><a href="/events">Events</a> &raquo; <a :href="'/events/' + event.slug">{{event.name}}</a> &raquo; Enter</h1>


</div>
</template>

<script>
import common from '../../mixins.js';
var marked = require('marked');
export default {
	mixins: [common],
	data: function() {
		return {
			event: [],
			attributes: [],
			calendar: null
		}
	},
	props: ['orgId', 'eventId'],
	created: function() {
		this.load();
	},
	mounted: function() {
		this.calendar = this.$refs.calendar;
	},
	computed: {
		compiledMarkdown: function () {
			return marked(this.event.details, { sanitize: true })
		},
		compiledTermsMarkdown: function () {
			return marked(this.event.terms, { sanitize: true })
		},
		flyingEvent: function() {
			switch (this.event.type)
			{
				case 'competition':
				case 'xcountry': 
					return true;
					break;
			}
			return false;
		}
	},
	methods: {
		load: function() {
			var that = this;
			window.axios.get('/api/events/' + this.eventId).then(function (response) {
				that.event = response.data.data;
				
			});
		}
	}
}
</script>
