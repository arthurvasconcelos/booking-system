<template>
    <div class="jumbotron">
        <h1 class="display-4">Welcome!</h1>
        <p class="lead">This is our booking system.</p>
        <hr class="my-4">
        <p>Some usefull links:</p>
        <router-link class="btn btn-primary btn-lg" :to="{ name: 'rooms' }">Rooms</router-link>
        <router-link class="btn btn-primary btn-lg" :to="{ name: 'my-bookings' }">My bookings</router-link>
        <router-link class="btn btn-primary btn-lg" :to="{ name: 'new-booking' }">Book a room</router-link>
    </div>
</template>

<script>
    export default {
        name: "home",
    };
</script>

<style scoped>
    /**/
</style>
