module.exports = {
	methods: {
		get_url_param: function(val) {
			var result = "",
			tmp = [];
			location.search.substr(1).split("&").forEach(function (item) {
				tmp = item.split("=");
				if (tmp[0] === val) result = decodeURIComponent(tmp[1]);
			});
			return result;
		},
		next: function() {
			if (this.state.page<this.last_page) this.state.page = +this.state.page + 1;
		},
		previous: function() {
			if (this.state.page>1) this.state.page = +this.state.page - 1;
		},
		createDateFromMysql: function(mysql_string)
		{
			var t, result = null;
			if( typeof mysql_string === 'string' )
			{
				t = mysql_string.split(/[- :]/);
				result = new Date(Date.UTC(t[0], t[1] - 1, t[2], t[3] || 0, t[4] || 0, t[5] || 0));
			}
			return result;
		},
		ratingExpired: function(expires) {
			if (expires==null) return false;
			return this.dateDifference(null, expires)<0;
		},
		ratingNearlyExpired: function(expires) {
			return this.dateDifference(null, expires)<50 && this.dateDifference(null, expires)>0;
		},
		dateDifference(dateString1=null, dateString2=null)
		{
			if (dateString1==null) {
				var date1 = new Date();
			} else {
				var date1 = this.createDateFromMysql(dateString1);
			}

			if (dateString2==null) {
				var date2 = new Date();
			} else {
				var date2 = this.createDateFromMysql(dateString2);
			}
			return (date2-date1)/(1000*60*60*24);
		},
		formatDate: function(date) {
			return Vue.prototype.$moment(date).format('ddd Do MMM YYYY');
		},
		dateToNow: function(date) {
			return Vue.prototype.$moment(date).fromNow();
		},
		dateDifferenceMoment: function(moment_date) {
			var difference = Vue.prototype.$moment().startOf('day').diff(moment_date, 'days');
			return difference;
		},
		formatStartsIn: function(date, starts='Starts', started='Started') {
			var days_away = this.dateDifferenceMoment(date);
			var string = '';
			if (days_away<=0) string += starts + ' ';
			if (days_away>0) string += started + ' ';
			if (days_away!=0) string += this.dateToNow(date);
			if (days_away==0) string += 'Today!'; 
			return string;
		},
		dateDiffDays: function(date1, date2) {
			if (date2==null || date2=='') return '';
			var date1 = Vue.prototype.$moment(date1);
			var date2 = Vue.prototype.$moment(date2);
			var days = date2.diff(date1, 'days') + 1;
			days==1 ? day_string = 'day' : day_string = 'days';
			return days + ' ' + day_string;
		},
		slug: function(slug) {
			var regex = /[^a-z0-9]/g;
			var regex_replace_multiple_dashes = /-+/g;
			return slug.toLowerCase().replace(regex, '-').replace(regex_replace_multiple_dashes, '-');
		},
		apiDateFormat: function(date) {
			var newdate = Vue.prototype.$moment(date);
			if (newdate.isValid()) return newdate.format('YYYY-MM-DD');
			return null;
		},
		eventTypes: function() {
			return [
				{'code': 'competition', 'name': 'Competition', 'icon':'trophy', 'shortname':'Comp'},
				{'code': 'training', 'name': 'Training', 'icon':'paper-plane', 'shortname':'Training'},
				{'code': 'course', 'name': 'Course', 'icon':'paper-plane', 'shortname':'Course'},
				{'code': 'dinner', 'name': 'Dinner', 'icon':'utensils', 'shortname':'Dinner'},
				{'code': 'bbq', 'name': 'BBQ', 'icon':'utensils', 'shortname':'BBQ'},
				{'code': 'working-bee', 'name': 'Working Bee', 'icon':'tractor', 'shortname':'Working Bee'},
				{'code': 'cadets', 'name': 'Cadets', 'icon':'users', 'shortname':'Cadets'},
				{'code': 'school-group', 'name': 'School Group', 'icon':'users', 'shortname':'School'},
				{'code': 'meeting', 'name': 'Meeting', 'icon':'handshake', 'shortname':'Meeting'},
				{'code': 'other', 'name': 'Other', 'icon':'calendar-alt', 'shortname':'Other'}
			]
		},
		formatEventType: function(event) {
			var eventType = this.eventTypes().filter(function findEvent(value) {
				if (value.code==event) return true;
			});
			if (eventType.length==0) return 'Other';
			return eventType[0].name;
		},
		formatEventTypeIcon: function(event) {
			var eventType = this.eventTypes().filter(function findEvent(value) {
				if (value.code==event) return true;
			});
			if (eventType.length>0) return '<span class="fa fa-' + eventType[0].icon + '"></span>';
			else return '<span class="fa fa-calendar-alt"></span>';
		}
	}
}
