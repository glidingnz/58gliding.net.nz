<template>
<div>
	
	<calendar-nav active="events" title="Edit Event"></calendar-nav>

</div>
</template>

<script>
export default {
	data: function() {
		return {
			event: [],
			newDutyName: '',
		}
	},
	props: ['orgId', 'eventId'],
	created: function() {
		this.load();
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
