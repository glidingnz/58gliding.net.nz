
<template>
    <div class="list-group">
        <a class="list-group-item" v-for="org in orgs" v-bind:href="'http://' + org.slug + '.' + getDomain() + '/'">{{org.name}}</a>
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
            var that = this;
            window.axios.get('/api/v1/orgs').then(function (response) {
                console.log(response);

                // success callback
                //ar responseJson = response;
                that.orgs = response.data.data;
            });
        }
    }
}
</script>

<style>
</style>