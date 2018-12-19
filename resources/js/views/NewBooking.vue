<template>
    <div class="container py-4">
        <h1>New Booking</h1>
        <BookingForm></BookingForm>
    </div>
</template>

<script>
    import BookingForm from "../components/BookingForm";

    export default {
        name: "new-booking",
        components: { BookingForm },
    };
</script>

<style scoped>
    /**/
</style>
