<template>
    <ul class="list-group">
        <li class="list-group-item" v-for="aircraft in aircrafts">{{aircraft.rego}} {{aircraft.spot_feed_id}}</li>
    </ul>
</template>

<script>
export default {
    data: function() {
        return {
            aircrafts: []
        }
    },
    created: function () {
        this.loadSpots();
    },
    methods: {
        loadSpots: function() {
            this.$http.get('/api/v1/aircraft', {params:{'spots':true}}).then(function (response) {
                // success callback
                var responseJson = response.json();
                this.aircrafts = responseJson.data;
            });
        }
    }
}
</script>

<style>
</style>