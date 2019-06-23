
<template>
    <div class="list-group">
        <a class="list-group-item" v-for="org in orgs" href="http://{{org.slug}}.{{getDomain()}}">{{org.name}}</a>
    </div>
</template>

<script>
export default {
    data: function() {
        return {
            orgs: []
        }
    },
    created: function () {
        this.loadClubs();
    },
    methods: {
        getDomain: function() {
            return window.Laravel.APP_DOMAIN;
        },
        loadClubs: function() {
            this.$http.get('/api/v1/orgs').then(function (response) {
                // success callback
                var responseJson = response.json();
                this.orgs = responseJson.data;
            });
        }
    }
}
</script>

<style>
</style>