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
	<div class="container-fluid">
		<div class="input-group ml-auto col-md-4 col-6 float-right" role="group">

			<div class="input-group">
				<input type="text" class="form-control" placeholder="Search" name="srch-term" id="srch-term" v-model="state.search">
				<div class="input-group-append">
					<button class="btn btn-outline-dark" type="submit" v-on:click="state.search=''"><i class="fa fa-times"></i></button>
				</div>
			</div>

		</div>

		<h1 class="col-xs-6 results-title">Members</h1>
	</div>

	<div class="container-fluid clearfix">

		<select name="org" v-model="state.org" class="col-xs-12 col-sm-4 form-control input-sm float-right" style="width: auto; margin-bottom: 20px;">
			<option v-bind:value="null">All Clubs</option>
			<option v-for="org in orgs" v-bind:value="org.gnz_code">{{org.name}}</option>
		</select>

		<div class="filter-buttons nav nav-pills col-xs-12 col-sm-8" role="group">

			<button type="button" class="btn btn-sm mr-1" v-bind:class="[ state.type=='all' ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="filterTo('all')">All</button>
			<button type="button" class="btn btn-sm mr-1" v-bind:class="[ state.type=='instructors' ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="filterTo('instructors')">Instructors</button>
			<button type="button" class="btn btn-sm mr-1" v-bind:class="[ state.type=='tow-pilots' ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="filterTo('tow-pilots')">Tow Pilots</button>
			<button type="button" class="btn btn-sm mr-1" v-bind:class="[ state.type=='youth' ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="filterTo('youth')" title="">Youth</button>
			<button type="button" class="btn btn-sm mr-1" v-bind:class="[ state.type=='non-qgp' ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="filterTo('non-qgp')" title="Non QGP who are flying members">Non QGP</button>
			<button type="button" class="btn btn-sm mr-1" v-bind:class="[ state.type=='qgp' ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="filterTo('qgp')">QGP</button>
			<button type="button" class="btn btn-sm mr-1" v-bind:class="[ state.type=='oo' ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="filterTo('oo')">OOs</button>
			<button type="button" class="btn btn-sm mr-1" v-bind:class="[ state.type=='coaches' ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="filterTo('coaches')">Coaches</button>
			<button type="button" class="btn btn-sm mr-1" v-bind:class="[ state.type=='contest_pilots' ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="filterTo('contest_pilots')">Contest Pilots</button>
		</div>

	</div>

	
	<div class="container-fluid">

		<div class="float-right">

			<div class="btn-group mr-2" role="group">
				<button type="button" class="btn btn-outline-dark btn-sm" v-on:click="previous()">&lt;</button>
				<button type="button" class="btn btn-outline-dark btn-sm disabled">Page {{ state.page }} of {{ last_page }}</button>
				<button type="button" class="btn btn-outline-dark btn-sm" v-on:click="next()">Next &gt;</button>
			</div>

			<div class="btn-group  mr-2" role="group">
				<button type="button" class="btn btn-outline-dark btn-sm disabled">Export</button>
				<button class="btn btn-outline-dark btn-sm" v-on:click="exportData('xls')">XLS</button>
				<button class="btn btn-outline-dark btn-sm" v-on:click="exportData('csv')">CSV</button>
			</div>

			<div class="btn-group ">
				<button class="btn btn-outline-dark btn-sm" v-on:click="toggleEmail()">Email</button>
			</div>

		</div>

		<h2>{{ total }} Results</h2>

	</div>

	<div class="row" v-show="showEmail" style="margin-bottom: 20px;">

		<div class="col-xs-12 col-sm-6">
			Sorry, email not working at the moment. To be fixed soon.
		</div>

		<div class="col-xs-12 col-sm-6" v-if="false">
			<b>From</b><br>
			<input type="text" class="form-control" v-model="emailFrom" placeholder="Your email e.g. jim@pear.co.nz">

			<b>Subject</b><br>
			<input type="text" class="form-control" v-model="emailSubject" placeholder="Subject">

			<span class="pull-right"><i class="fa fa-info-circle"></i><a href="javascript:void(0)"  v-on:click="toggleTips()"> Email Tips...</a></span>

			<b>Message</b><br>
			<textarea type="text" class="form-control" rows="5" v-model="emailMessage"></textarea>

			<br>

			<input type="submit" v-bind:value="'Send Email to ' + total + ' members'" class=" btn btn-primary" v-on:click="sendEmail()"  v-show="!emailSending">
			<input type="submit" v-bind:value="'Send Email to ' + total + ' members'" class=" btn btn-disabled" v-show="emailSending">

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
				<div  class="panel-body" v-html="compiledMarkdown"></div>
			</div>
		</div>

	</div>

	<div class="container-fluid">
		<table class="table results-table table-striped">
			<tr>
				<th class="d-none d-lg-table-cell">GNZ ID</th>
				<th>Firstname</th>
				<th>Lastname</th>
				<th>Club</th>
				<th>Member Type</th>
				<th>City</th>
				<th>Mobile</th>
				<th>Email</th>
				<th>OO</th>
				<th></th>
			</tr>
			<tr v-for="result in results">
				<td class="d-none d-lg-table-cell nowrap"><a v-bind:href="'/members/' + result.id">{{ result.nzga_number }}</a></td>
				<td><a v-bind:href="'/members/' + result.id">{{ result.first_name }}</a></td>
				<td><a v-bind:href="'/members/' + result.id">{{ result.last_name }}</a></td>
				<td>{{ result.club }}</td>
				<td>{{ result.membership_type }}</td>
				<td>{{ result.city }}</td>
				<td>{{ result.mobile_phone }}</td>
				<td><a v-bind:href="'mailto:' + result.email">{{ result.email }}</a></td>
				<td>{{ result.observer_number }}</td>
				<td>
					<a v-bind:href="'/members/' + result.id + '/achievements/'" class="btn btn-primary btn-sm mr-1 mb-1"><i class="fa fa-trophy"></i></a>
					<a v-if="showEdit" v-bind:href="'http://members.gliding.co.nz/index.php?r=member/update&id=' + result.id" class="btn mr-1 mb-1 btn-outline-dark btn-sm">Old Edit</a>
					<a v-if="showEdit" v-bind:href="'/members/' + result.id + '/edit'" class="btn mr-1 mb-1 btn-outline-dark btn-sm">Edit</a>
					<a v-if="showEdit" v-bind:href="'/members/' + result.id + '/ratings'" class="btn mb-1 btn-outline-dark btn-sm">Ratings</a>
				</td>

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
		computed: {
			compiledMarkdown: function () {
				return marked(this.emailMessage, { sanitize: true })
			}
		},
		mounted() {
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

				window.axios.get('/api/v1/members?' + this.createExportUrl(format)).then(function (response) {

					if (typeof response.data.data.url!='undefined') {
						window.location.href = response.data.data.url;
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
				var that = this;
				window.axios.get('/api/v1/members', {params: this.state}).then(function (response) {
					
					that.results = response.data.data;
					that.last_page = response.data.last_page;
					that.total = response.data.total;

					if (that.state.page > that.last_page && that.last_page>0) {
						that.state.page = 1;
					}
				});
			},
			loadOrgs: function() {
				var that = this;
				window.axios.get('/api/v1/orgs/').then(function (response) {
					that.orgs = response.data.data;
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

				window.axios.post('/api/v1/members/email', data).then(function (response) {
					this.emailSending=false;
					var responseJson = response.json();

					if (responseJson.success==true) messages.$emit('success', 'Message Sent');
					else messages.$emit('error', 'Message not sent. Something went wrong.');
				});
			}
		}
	}
</script>