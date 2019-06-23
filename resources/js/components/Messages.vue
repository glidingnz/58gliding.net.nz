<style>
.messages-panel {
	position: fixed;
	width: 30em;
	min-height: 50px;
	background-color: #FFF;
	border: 1px solid #EEE;
	padding: 10px;
	margin-left: -20px;
	margin-top: 6px;
	z-index: 9999;
	top: 0px;
}
.message-error { color: #A00; }
.message-warning { color: #DC9200; }
.message-success { color: #0A0; }
.message-note { color: #3C8DBC; }
a.no-messages { color: #CCC; }
a.new-messages { color: #A00; }
a.old-messages { color: #444; }
</style>

<template>
	<div>
		<a class="fa fa-bell-o no-messages" v-bind:class="{ 'no-messages': areNoMessages, 'new-messages': areNewMessages , 'old-messages': areOldMessages }" href="javascript:void(null)" v-on:click="togglePanel()"></a>
		<div class="messages-panel" v-show="panelOpen">

			<i class="fa fa-times" style="float:right;" v-on:click="togglePanel()"></i>
			<div v-for="message in newMessages" v-bind:class="'message message-' + message.type">
				<i class="fa" v-bind:class="{'fa-minus-circle':message.type=='error', 'fa-exclamation-triangle':message.type=='warning', 'fa-check-circle':message.type=='success', 'fa-info-circle':message.type=='note'}"></i> {{message.text}}
			</div>

			<div v-show="newMessages.length==0">
				<span class="small">Last 10 Notifications</span>
				<div v-for="message in messagesArchive" v-bind:class="'message message-' + message.type">
					<i class="fa" v-bind:class="{'fa-minus-circle':message.type=='error', 'fa-exclamation-triangle':message.type=='warning', 'fa-check-circle':message.type=='success', 'fa-info-circle':message.type=='note'}"></i> {{message.text}}
				</div>

				<div v-show="messagesArchive.length==0">
					<span class="grey small">None</span>
				</div>
			</div>


		</div>
	</div>
</template>

<script>
	export default {
		data: function() {
			return {
				panelOpen: false,
				newMessages: [],
				messagesArchive: []
			}
		},
		computed: {
			areNoMessages: function() {
				return (this.newMessages.length==0 && this.messagesArchive.length==0);
			},
			areOldMessages: function() {
				return (this.newMessages.length==0 && this.messagesArchive.length>0);
			},
			areNewMessages: function() {
				return this.newMessages.length > 0;
			}
		},
		mounted() {
			// copy messages from PHP into the arrays
			this.newMessages = window.Laravel.messages.slice(0);
			this.messagesArchive = window.Laravel.messages.slice(0);

			// initiate global JS message bus functions
			messages.$on('error', function(str) {
				this.error(str);
			}.bind(this));

			messages.$on('warning', function(str) {
				this.warning(str);
			}.bind(this));

			messages.$on('note', function(str) {
				this.note(str);
			}.bind(this));

			messages.$on('success', function(str) {
				this.success(str);
			}.bind(this));

		},
		methods: {
			togglePanel: function() {

				// discard >10 oldest items
				this.messagesArchive = this.messagesArchive.slice(0, 10);

				// on closing of the panel, empty the new messages array
				if (this.panelOpen) {
					this.newMessages = [];
				}

				// toggle the panel open/closed state
				this.panelOpen=!this.panelOpen;
			},
			openPanel: function() {
				this.panelOpen = true;
			},
			error: function(text) {
				this.messagesArchive.unshift({type:'error', text: text});
				this.newMessages.unshift({type:'error', text: text});
				this.openPanel();
			},
			success: function(text) {
				this.messagesArchive.unshift({type:'success', text: text});
				this.newMessages.unshift({type:'success', text: text});
				this.openPanel();
			},
			warning: function(text) {
				this.messagesArchive.unshift({type:'warning', text: text});
				this.newMessages.unshift({type:'warning', text: text});
				this.openPanel();
			},
			note: function(text) {
				this.messagesArchive.unshift({type:'note', text: text});
				this.newMessages.unshift({type:'note', text: text});
				this.openPanel();
			},
		}
	}
</script>
