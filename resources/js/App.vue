<template>
    <div :class="{ 'pt-6': isAuth() }">
        <nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark" v-if="isAuth()">
            <router-link class="navbar-brand mb-0 h1" :to="{ name: 'home' }">Booking System</router-link>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <router-link class="nav-link" :to="{ name: 'home' }">Home</router-link>
                    </li>

                    <li class="nav-item">
                        <router-link class="nav-link" :to="{ name: 'rooms' }">Rooms</router-link>
                    </li>
                </ul>

                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Hi, {{ getUser().name }}
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <router-link class="dropdown-item" :to="{ name: 'my-bookings' }">My bookings</router-link>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" @click.prevent="logout()">Logout</a>
                        </div>
                    </li>

                    <li class="nav-item">
                        <router-link class="btn btn-outline-success my-2 my-sm-0" :to="{ name: 'new-booking' }">Book room</router-link>
                    </li>
                </ul>
            </div>
        </nav>

        <router-view></router-view>
    </div>
</template>

<script>
    export default {
        name: "app",
        beforeMount() {
            if (this.isAuth()) {
                this.$http.defaults.headers.common["Content-Type"] = "application/json";
                this.$http.defaults.headers.common["Authorization"] = `Bearer ${localStorage.getItem("jwt")}`;
            }
        },
        methods: {
            logout() {
                localStorage.clear();
                delete this.$http.defaults.headers.common["Content-Type"];
                delete this.$http.defaults.headers.common["Authorization"];
                this.$router.push({ name: "login" });
            }
        },
    };
</script>

<style scoped>
    .pt-6 {
        padding-top: calc(1rem * 3.5);
    }
</style>
