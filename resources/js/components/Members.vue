<style>
.btn-group {
	margin-bottom: 20px;
}
.filter-buttons {
	margin-bottom: 15px;
}
.filter-buttons .btn {
	margin-bottom: 5px;
}
.filter-buttons .btn-group {
	margin-bottom: 0;
}
</style>

<template>
<div>
	<div class="row">
		<h1 class="col-xs-6 results-title">Members</h1>
		<div class="btn-group col-xs-6 col-md-4  pull-right" role="group">

			<div class="input-group">
				<input type="text" class="form-control" placeholder="Search" name="srch-term" id="srch-term" v-model="state.search" debounce="300">
				<div class="input-group-btn">
					<button class="btn btn-default" type="submit" v-on:click="state.search=''"><i class="fa fa-times"></i></button>
				</div>
			</div>

		</div>
	</div>

	<div class="row clearfix">

		<div class="filter-buttons nav nav-pills col-xs-12 col-sm-8" role="group">

			<div class="btn-group" role="group">
				<button type="button" class="btn btn-default btn-sm" v-bind:class="{ 'btn-primary': state.type=='all' }" v-on:click="filterTo('all')">All</button>
				<button type="button" class="btn btn-default btn-sm" v-bind:class="{ 'btn-primary': state.type=='instructors' }" v-on:click="filterTo('instructors')">Instructors</button>
				<button type="button" class="btn btn-default btn-sm" v-bind:class="{ 'btn-primary': state.type=='tow-pilots' }" v-on:click="filterTo('tow-pilots')">Tow Pilots</button>
				<button type="button" class="btn btn-default btn-sm" v-bind:class="{ 'btn-primary': state.type=='youth' }" v-on:click="filterTo('youth')" title="">Youth</button>
				<button type="button" class="btn btn-default btn-sm" v-bind:class="{ 'btn-primary': state.type=='non-qgp' }" v-on:click="filterTo('non-qgp')" title="Non QGP who are flying members">Non QGP</button>
				<button type="button" class="btn btn-default btn-sm" v-bind:class="{ 'btn-primary': state.type=='qgp' }" v-on:click="filterTo('qgp')">QGP</button>
				<button type="button" class="btn btn-default btn-sm" v-bind:class="{ 'btn-primary': state.type=='oo' }" v-on:click="filterTo('oo')">OOs</button>
				<button type="button" class="btn btn-default btn-sm" v-bind:class="{ 'btn-primary': state.type=='coaches' }" v-on:click="filterTo('coaches')">Coaches</button>
				<button type="button" class="btn btn-default btn-sm" v-bind:class="{ 'btn-primary': state.type=='contest_pilots' }" v-on:click="filterTo('contest_pilots')">Contest Pilots</button>
			</div>
		</div>

		<select name="org" v-model="state.org" class="col-xs-12 col-sm-4 form-control input-sm pull-right" style="width: auto; margin-bottom: 20px;">
			<option v-bind:value="null">All Clubs</option>
			<option v-for="org in orgs" v-bind:value="org.gnz_code">{{org.name}}</option>
		</select>
	</div>

	
	<div class="row">
		<div class="col-xs-12 col-sm-4 hidden-xs">

			<h2 class="results-title">{{ total }} Results</h2>

		</div>

		<div class="col-xs-12 col-sm-8">


			<div class="btn-group pull-right" role="group">
				<button type="button" class="btn btn-default btn-sm" v-on:click="previous()">&lt;</button>
				<button type="button" class="btn btn-default btn-sm disabled">Page {{ state.page }} of {{ last_page }}</button>
				<button type="button" class="btn btn-default btn-sm" v-on:click="next()">Next &gt;</button>
			</div>

			<div class="btn-group pull-right" role="group" style="margin-right: 20px;">
				<button type="button" class="btn btn-default btn-sm disabled">Export</button>
				<button class="btn btn-default btn-sm" v-on:click="exportData('xls')">XLS</button>
				<button class="btn btn-default btn-sm" v-on:click="exportData('csv')">CSV</button>
			</div>

			<div class="btn-group pull-right" style="margin-right: 20px;">
				<button class="btn btn-default btn-sm" v-on:click="toggleEmail()">Email</button>
			</div>

		</div>
	</div>

	<div class="row" v-show="showEmail" style="margin-bottom: 20px;">

		<div class="col-xs-12 col-sm-6">
			<b>From</b><br>
			<input type="text" class="form-control" v-model="emailFrom" placeholder="Your email e.g. jim@pear.co.nz">

			<b>Subject</b><br>
			<input type="text" class="form-control" v-model="emailSubject" placeholder="Subject">

			<span class="pull-right"><i class="fa fa-info-circle"></i><a href="javascript:void(0)"  v-on:click="toggleTips()"> Email Tips...</a></span>

			<b>Message</b><br>
			<textarea type="text" class="form-control" rows="5" v-model="emailMessage"></textarea>

			<br>
			<input type="submit" value="Send Email to {{total}} members" class=" btn btn-primary" v-on:click="sendEmail()"  v-show="!emailSending">
			<input type="submit" value="Send Email to {{total}} members" class=" btn btn-disabled" v-show="emailSending"> 
			<span v-show="emailSending"><i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i> Sending</span>
		</div>
		<div class="col-xs-12 col-sm-6 ">

			<div class="panel panel-default" v-show="tipsShowing">
				<div class="panel-heading">Tips <a href="javascript:void(0)" class="pull-right fa fa-times" v-on:click="toggleTips()"></a></div>
				<div class="panel-body">
					
						<ul>
							<li>
								<b>Include links</b>
								<p>Always include a link when referring to something on the internet. People are lazy, so make life easy for them e.g.</p>
								<pre>Don't forget about our classifieds system<br>http://gliding.co.nz/classifies</pre>
							</li>
							<li>
								<b>Put links on a fresh new line with no punctuation</b>
								<p>Most email readers will turn a link into a working clickable link, but only if it doesn't have a full stop at the end.</p>
							</li>
							<li>This supports Markdown. Markdown makes it easy to add styles to the email. <a href="https://github.com/adam-p/markdown-here/wiki/Markdown-Cheatsheet">Check this Markdown Cheatsheet</a> for more on how this works.</li>
						</ul>
				</div>
			</div>


			<b>Preview</b><br>
			<div class="panel panel-default">
				<div class="panel-heading">{{emailSubject}} <span v-if="!emailSubject" class="text-muted">(No Subject)</span></div>
				<div  class="panel-body" v-html="emailMessage | marked"></div>
			</div>
		</div>

	</div>

	<table class="row table results-table table-striped">
		<tr>
			<th class="hidden-xs hidden-sm">GNZ ID</th>
			<th>Firstname</th>
			<th>Lastname</th>
			<th>Club</th>
			<th>Member Type</th>
			<th>City</th>
			<th>Mobile</th>
			<th>Email</th>
			<th>OO</th>
			<th></th>
			<th v-if="showEdit" colspan=3></th>
		</tr>
		<tr v-for="result in results">
			<td class="hidden-xs hidden-sm nowrap"><a href="/members/{{result.id}}">{{ result.nzga_number }}</a></td>
			<td><a href="/members/{{result.id}}">{{ result.first_name }}</a></td>
			<td><a href="/members/{{result.id}}">{{ result.last_name }}</a></td>
			<td>{{ result.club }}</td>
			<td>{{ result.membership_type }}</td>
			<td>{{ result.city }}</td>
			<td>{{ result.mobile_phone }}</td>
			<td><a href="mailto:{{result.email}}">{{ result.email }}</a></td>
			<td>{{ result.observer_number }}</td>
			<td><a href="/members/{{result.id}}/achievements/" class="btn btn-primary btn-xs"><i class="fa fa-trophy"></i></a></td>
			<td class="center" v-if="showEdit"><a href="http://members.gliding.co.nz/index.php?r=member/update&id={{ result.id }}" class="btn btn-default btn-xs">Old Edit</a></td>
			<td class="center" v-if="showEdit"><a href="/members/{{result.id}}/edit" class="btn btn-primary btn-xs">Edit</a></td>
			<td class="center" v-if="showEdit"><a href="/members/{{result.id}}/ratings" class="btn btn-primary btn-xs">Ratings</a></td>

		</tr>
	</table>
</div>
</div>
</template>




<script>
	import common from '../mixins.js';
	var marked = require('marked');

	export default {
		mixins: [common],
		props: ['orgCode'],
		data() {
			return {
				state: {
					type: 'all',
					page: 1,
					search: '',
					org: null
				},
				last_page: 1,
				total: 0,
				results: [],
				orgs: [],
				dont_reload: false,
				showEdit: false,
				showEmail: false,
				emailFrom: '',
				emailSubject: '',
				emailMessage: '',
				emailSending: false,
				tipsShowing: false
			}
		},
		watch: {
			'state': {
				handler: 'stateChanged',
				deep: true
			}
		},
		filters: {
			marked: marked
		},
		ready() {
			if (this.orgCode) {
				this.state.org=this.orgCode;
			}

			this.loadOrgs();

			if (window.Laravel.clubAdmin) this.showEdit=true;

			if (this.get_url_param('search')) this.state.search = this.get_url_param('search');
			if (this.get_url_param('page')) this.state.page = this.get_url_param('page');
			if (this.get_url_param('type')) this.state.type = this.get_url_param('type');

			var that = this;
			History.Adapter.bind(window, 'statechange', function() {
				var state = History.getState();
				that.state = state.data;
				if (!that.dont_reload) {
					that.loadSelected();
				}
				that.dont_reload=false;
			});

			History.replaceState(this.state, null, "?search=" + this.state.search + "&type=" + this.state.type + "&page=" + this.state.page);
			this.loadSelected();
		},
		methods: {
			createExportUrl: function(format) {
				var extras = { 'format': format };
				return this.createUrl(this.state, extras);
			},
			exportData: function(format) {

				this.$http.get('/api/v1/members?' + this.createExportUrl(format)).then(function (response) {

					var responseJson = response.json();

					if (typeof responseJson.data.url!='undefined') {
						window.location.href = responseJson.data.url;
					}
				});
			},
			createUrl: function(obj, extras) {
				var parts = [];
				for (var i in extras) {
					if (extras.hasOwnProperty(i)) {
						parts.push(encodeURIComponent(i) + "=" + encodeURIComponent(extras[i]));
					}
				}
				for (var i in obj) {
					if (obj.hasOwnProperty(i)) {
						parts.push(encodeURIComponent(i) + "=" + encodeURIComponent(obj[i]));
					}
				}
				return parts.join("&");
			},
			filterTo: function(type) {
				this.state.type = type;
				this.state.page=1;
			},
			stateChanged: function() {
				History.pushState(this.state, null, "?search=" + this.state.search + "&type=" + this.state.type + "&page=" + this.state.page);
			},
			loadSelected: function() {
				this.$http.get('/api/v1/members', {params: this.state}).then(function (response) {
					
					var responseJson = response.json();
					this.results = responseJson.data;
					this.last_page = responseJson.last_page;
					this.total = responseJson.total;

					if (this.state.page > this.last_page && this.last_page>0) {
						this.state.page = 1;
					}
				});
			},
			loadOrgs: function() {
				this.$http.get('/api/v1/orgs/').then(function (response) {
					var responseJson = response.json();
					this.orgs = responseJson.data;
				});
			},
			toggleEmail: function() {
				this.showEmail = !this.showEmail;
			},
			toggleTips: function() {
				this.tipsShowing = !this.tipsShowing;
			},
			sendEmail: function() {

				var data = this.state;
				data.from = this.emailFrom;
				data.subject = this.emailSubject;
				data.message = this.emailMessage;

				if (this.emailFrom=='') {
					messages.$emit('error', 'A from email address is required');
					return false;
				}
				if (this.emailSubject=='') {
					messages.$emit('error', 'A subject is required');
					return false;
				}
				if (this.emailMessage=='') {
					messages.$emit('error', 'A message is required');
					return false;
				}

				this.emailSending = true;

				this.$http.post('/api/v1/members/email', data).then(function (response) {
					this.emailSending=false;
					var responseJson = response.json();

					if (responseJson.success==true) messages.$emit('success', 'Message Sent');
					else messages.$emit('error', 'Message not sent. Something went wrong.');
				});
			}
		}
	}
</script>