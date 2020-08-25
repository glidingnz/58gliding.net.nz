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
		<div class="input-group ml-auto col-md-4 col-12 float-right row" role="group">

			<div class="input-group">

				<a href="/members/add" v-if="clubAdmin" class="mr-2 btn btn-outline-dark">Add New</a>

				<input type="text" class="form-control" placeholder="Search" name="srch-term" id="srch-term" v-model="state.search">
				<div class="input-group-append">
					<button class="btn btn-outline-dark" type="submit" v-on:click="state.search=''"><i class="fa fa-times"></i></button>
				</div>
			</div>

		</div>

		<h1 class="col-xs-6 results-title"><span v-if="current_org">{{current_org.name}}</span> Members</h1>
	</div>

	<div class="container-fluid clearfix">

		<select name="org" v-model="current_org" class="col-xs-12 col-sm-4 form-control custom-select custom-select-sm float-right" style="width: auto; margin-bottom: 20px;">
			<option v-bind:value="null">All Clubs</option>
			<option v-for="org in orgs" v-bind:value="org">{{org.name}}</option>
		</select>

		<div class="filter-buttons nav nav-pills col-xs-12 col-sm-8" role="group">

			<button type="button" class="btn btn-sm mr-1" v-bind:class="[ state.type=='all' ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="filterTo('all')">All</button>
			<!-- disabled buttons due to HAVING not working with pagination in earlier versions of Laravel.
			Need to upgrade to laravel v7 to enable in the API. -->
			<button type="button" class="btn btn-sm mr-1" v-bind:class="[ state.type=='instructors' ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="filterTo('instructors')">Instructors</button>
			<button type="button" class="btn btn-sm mr-1" v-bind:class="[ state.type=='tow-pilots' ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="filterTo('tow-pilots')">Tow Pilots</button>
			<button type="button" class="btn btn-sm mr-1" v-bind:class="[ state.type=='youth' ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="filterTo('youth')" title="">Youth</button>
			<button type="button" class="btn btn-sm mr-1" v-bind:class="[ state.type=='qgp' ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="filterTo('qgp')">QGP</button>
			<button type="button" class="btn btn-sm mr-1" v-bind:class="[ state.type=='xcp' ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="filterTo('xcp')">Cross Country Pilots</button>
			<button type="button" class="btn btn-sm mr-1" v-bind:class="[ state.type=='oo' ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="filterTo('oo')">OOs</button>
			<button type="button" class="btn btn-sm mr-1" v-bind:class="[ state.type=='coaches' ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="filterTo('coaches')">Coaches</button>
			<button type="button" class="btn btn-sm mr-1" v-bind:class="[ state.type=='contest_pilots' ? 'btn-secondary': 'btn-outline-dark' ]" v-on:click="filterTo('contest_pilots')">Contest Pilots</button>
		</div>


	</div>

	<div class="container-fluid">

		<div class="float-right">
			
			<div class="btn-group mr-2" role="group" v-if="(org && clubAdmin) || admin">
				<label for="ex_members"><input id="ex_members" type="checkbox" v-model="state.ex_members"> Ex Members</label>
			</div>

			<!-- <div class="btn-group mr-2" role="group" v-if="!org && admin">
				<label for="gnz_members"><input id="gnz_members" type="checkbox" v-model="state.gnz_members"> Only Current GNZ members</label>
			</div> -->

			<div class="btn-group mr-2" role="group">
				<button type="button" class="btn btn-outline-dark btn-sm" v-on:click="previous()">&lt;</button>
				<button type="button" class="btn btn-outline-dark btn-sm disabled">Page {{ state.page }} of {{ last_page }}</button>
				<button type="button" class="btn btn-outline-dark btn-sm" v-on:click="next()">Next &gt;</button>
			</div>

			<div class="btn-group  mr-2" role="group">
				<button type="button" class="btn btn-outline-dark btn-sm disabled">Export</button>
				<button class="btn btn-outline-dark btn-sm" v-on:click="exportData('xls')">XLS</button>
				<button class="btn btn-outline-dark btn-sm" v-on:click="exportData('xlsx')">XLSX</button>
				<button class="btn btn-outline-dark btn-sm" v-on:click="exportData('csv')">CSV</button>
			</div>

			<div class="btn-group ">
				<button class="btn btn-outline-dark btn-sm" v-on:click="toggleEmail()">Email</button>
			</div>

		</div>

		<h2 v-if="!loading">{{ total }} Results </h2>
		<h2 v-if="loading"><span class="fa  fa-spin fa-sync"></span> Loading</h2>

	</div>

	<div class="row" v-show="showEmail" style="margin-bottom: 20px;">

		<div class="col-xs-12 col-sm-6">
			<div class="card">
				<h5 class="card-header">Create Email</h5>

				<div class="card-body">

					<b>From</b><br>
					<input type="text" class="form-control" v-model="emailFrom" placeholder="Your email e.g. jim@pear.co.nz">

					<b>Subject</b><br>
					<input type="text" class="form-control" v-model="emailSubject" placeholder="Subject">

					<b>Message</b><br>
					<textarea type="text" class="form-control" rows="5" v-model="emailMessage"></textarea>

				</div>
				<div class="card-footer">

					<span class="float-right">
						<i class="fa fa-info-circle"></i>
						<a href="javascript:void(0)"  v-on:click="toggleTips()"> Show Email Tips...</a>
					</span>

					<input type="submit" v-bind:value="'Send Email to ' + total + ' members'" class=" btn btn-primary" v-on:click="sendEmail()"  v-show="!emailSending">
					<input type="submit" v-bind:value="'Send Email to ' + total + ' members'" class=" btn btn-disabled" v-show="emailSending">

					<span v-show="emailSending"><i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i> Sending</span>
				</div>
			</div>
		</div>
		<div class="col-xs-12 col-sm-6 ">

			<div class="card mb-4">

				<h5 class="card-header">Preview: {{emailSubject}} <span v-if="!emailSubject" class="text-muted">(No Subject)</span></h5>
				<div class="card-body" v-html="compiledMarkdown"></div>
			</div>

			<div class="card" v-show="tipsShowing">
				<div class="card-header">Email Tips 
					<a href="javascript:void(0)" class="float-right " v-on:click="toggleTips()"><span class="fa fa-times"> </span> Close Tips</a></div>
				<div class="card-body">

					<ul class="list-group list-group-flush">
						<li class="list-group-item">This supports Markdown. Markdown makes it easy to add styles to the email. <a href="https://github.com/adam-p/markdown-here/wiki/Markdown-Cheatsheet">Check this Markdown Cheatsheet</a> for more on how this works. Some examples:

						<p style="font-family: monospace;">
						# Main Heading<br>
						## Sub Heading (up to ####)<br>
						*italics*<br>
						**bold text**<br>
						[https://www.google.com](https://www.google.com)<br>
						--- (Horizontal Rule)
						</p>

						</li>
						<li class="list-group-item">
							<b>Include links</b>
							<p>Always include a link when referring to something on the internet. People are lazy, so make life easy for them e.g.</p>
							<pre>Don't forget about our classifieds system<br>http://gliding.co.nz/classifies</pre>
						</li>
						<li class="list-group-item">
							<b>Put links on a fresh new line with no punctuation</b>
							<p>Most email readers will turn a link into a working clickable link, but only if it doesn't have a full stop at the end.</p>
						</li>
					</ul>
				</div>
			</div>

		</div>

	</div>

	<div class="container-fluid">
		<table class="table results-table table-striped collapsable">
			<tr>
				<th class="d-none d-lg-table-cell">GNZ ID</th>
				<th>Firstname</th>
				<th>Lastname</th>
				<th>Club</th>
				<th>GNZ Status</th>
				<th>City</th>
				<th>Mobile</th>
				<th>Email</th>
				<th>OO</th>
				<th>Inst.</th>
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
				<td><span style="text-transform: uppercase;">{{ result.rating_instructor_level }}</span></td>
				<td>
					<a v-bind:href="'/members/' + result.id + '/achievements/'" class="btn btn-outline-dark btn-sm mr-1 mb-1"><i class="fa fa-trophy"></i></a>
					<a v-if="clubAdmin" v-bind:href="'/members/' + result.id + '/edit'" class="btn mr-1 mb-1 btn-outline-dark btn-sm">Edit</a>
					<a v-if="clubAdmin" v-bind:href="'/members/' + result.id + '/ratings'" class="btn mb-1 btn-outline-dark btn-sm">Ratings</a>
				</td>

			</tr>
		</table>


		<div class="btn-group mr-2" role="group">
			<button type="button" class="btn btn-outline-dark btn-sm" v-on:click="previous()">&lt;</button>
			<button type="button" class="btn btn-outline-dark btn-sm disabled">Page {{ state.page }} of {{ last_page }}</button>
			<button type="button" class="btn btn-outline-dark btn-sm" v-on:click="next()">Next &gt;</button>
		</div>

	</div>
</div>
</template>




<script>
	import common from '../mixins.js';
	var marked = require('marked');

	export default {
		mixins: [common],
		props: [],
		data() {
			return {
				org: null, // the org's of the site we're on
				current_org: null, // the currently displayed org
				loading: true,
				state: {
					type: 'all',
					page: 1,
					search: '',
					org_id: null,
					ex_members: false,
					gnz_members: true,
				},
				last_page: 1,
				total: 0,
				results: [],
				orgs: [],
				dont_reload: false,
				clubAdmin: false,
				showEmail: false,
				clubMember: false,
				admin: false,
				emailFrom: '',
				emailSubject: '',
				emailMessage: '',
				emailSending: false,
				tipsShowing: true,
			}
		},
		watch: {
			'state': {
				handler: 'stateChanged',
				deep: true
			},
			'current_org': {
				handler: 'orgChanged',
				deep: true
			}
		},
		computed: {
			compiledMarkdown: function () {
				return marked(this.emailMessage, { sanitize: true })
			}
		},
		mounted() {

			this.loadOrgs();

			if (window.Laravel.admin) this.admin=true;
			if (window.Laravel.clubAdmin) this.clubAdmin=true;
			if (window.Laravel.clubMember) this.clubMember=true;

			// check if we are showing the GNZ level list, or a club list
			if (window.Laravel.org) {
				if (window.Laravel.org.short_name!='GNZ') {
					this.org=window.Laravel.org;
					this.current_org=window.Laravel.org;
					this.state.org_id = this.current_org.id;
				} else {
					this.state.gnz_members = true; // if showing the GNZ list, only include GNZ members
				}
			}

			if (this.get_url_param('search')) this.state.search = this.get_url_param('search');
			if (this.get_url_param('page')) this.state.page = this.get_url_param('page');
			if (this.get_url_param('type')) this.state.type = this.get_url_param('type');

			if (this.get_url_param('ex_members')) this.get_url_param('ex_members')=='true' ? this.state.ex_members = true : this.state.ex_members = false;

			var that = this;
			History.replaceState(this.state, null, "?search=" + this.state.search + "&type=" + this.state.type + "&page=" + this.state.page + "&gnz_members=" + this.state.gnz_members + "&ex_members=" + this.state.ex_members);

			History.Adapter.bind(window, 'statechange', function() {
				var state = History.getState();
				that.state = state.data;
				if (!that.dont_reload) {
					that.loadSelected();
				}
				that.dont_reload=false;
			});

			// initiagte the first load
			that.loadSelected();
		},
		methods: {
			orgChanged: function() {
				if (this.current_org) {
					this.state.org_id = this.current_org.id;
				} else {
					this.state.org_id = null;
				}
				
				this.loadSelected();
			},
			createExportUrl: function(format) {
				var extras = { 'format': format };
				return this.createUrl(this.state, extras);
			},
			exportData: function(format) {

				// create the url
				var url='/api/v1/members/export/' + format + '?' + this.createExportUrl(format);
				axios({
					url: url,
					method: 'GET',
					responseType: 'blob',
				}).then((response) => {
					 var fileURL = window.URL.createObjectURL(new Blob([response.data]));
					 var fileLink = document.createElement('a');
					 fileLink.href = fileURL;
					 fileLink.setAttribute('download', 'members.' + format);
					 document.body.appendChild(fileLink);
					 fileLink.click();
				});

				// download it!
				// window.location.href = url;
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
				History.pushState(this.state, null, "?search=" + this.state.search + "&type=" + this.state.type + "&page=" + this.state.page + "&ex_members=" + this.state.ex_members);
			},
			loadSelected: function() {
				this.loading = true;

				//var that = this;
				window.axios.get('/api/v1/members', {params: this.state}).then( (response) => {

					this.loading = false;
					this.results = response.data.data;
					this.last_page = response.data.last_page;
					this.total = response.data.total;

					if (this.state.page > this.last_page && this.last_page>0) {
						this.state.page = 1;
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
				var that = this;

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
					that.emailSending=false;
					var responseJson = response.data;

					if (responseJson.success==true) messages.$emit('success', 'Message Sent');
					else messages.$emit('error', 'Message not sent. Something went wrong.');
				});
			}
		}
	}
</script>