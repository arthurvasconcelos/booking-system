<template>
    <div class="container py-4">
        <h1 class="mb-4">Editing booking: {{ $route.params.id }}</h1>
        <BookingForm :booking="booking" v-if="booking"></BookingForm>
    </div>
</template>

<script>
    import BookingForm from "../components/BookingForm";

    export default {
        name: "edit-my-booking",
        components: { BookingForm },
        data() {
            return {
                booking: null,
            };
        },
        beforeMount() {
            this.$http.get(`/api/booking/${ this.$route.params.id }`)
                .then((response) => this.booking = response.data)
                .catch((error) => console.error(error));
        }
    };
</script>

<style scoped>
    /**/
</style>
