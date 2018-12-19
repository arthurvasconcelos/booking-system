<template>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="m-0">Rooms</h1>
            <div class="form-group d-flex align-items-center m-0">
                <label for="selectDate" class="mb-0 mr-2">Date:</label>
                <input type="date" id="selectDate" class="form-control" v-model="selectedDate">
            </div>
        </div>

        <div class="alert alert-info" role="alert" v-if="Object.keys(bookings).length <= 0">
            No bookings available...
        </div>

        <ul class="list-group" v-if="Object.keys(bookings).length > 0">
            <li class="list-group-item" v-for="(roomBookings, key, index) in bookings" :key="`room_${index}_${key}`">
                <div class="mt-3">
                    <h5>{{ key }}</h5>
                </div>
                <table class="table table-hover mt-3">
                    <thead class="thead-light">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Start At</th>
                        <th scope="col">End At</th>
                        <th scope="col">Owner Name</th>
                        <th scope="col">Owner Email</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(booking, bIndex) in roomBookings" :key="`booking_${booking.id}`">
                        <th scope="row">{{ bIndex + 1 }}</th>
                        <td>{{ booking.start_at }}</td>
                        <td>{{ booking.end_at }}</td>
                        <td>{{ booking.user_name }}</td>
                        <td>{{ booking.email }}</td>
                    </tr>
                    </tbody>
                </table>
            </li>
        </ul>
    </div>
</template>

<script>
    import moment from "moment";

    export default {
        name: "rooms",
        data() {
            return {
                bookings: {},
                selectedDate: moment().format("YYYY-MM-DD"),
            };
        },
        beforeMount() {
            this.fetchBookings(this.selectedDate);
        },
        watch: {
            selectedDate(newDate, oldDate) {
                this.fetchBookings(newDate);
            }
        },
        methods: {
            fetchBookings(date) {
                this.$http.get(`/api/booking/by-date/${ date }`)
                    .then((response) => this.bookings = response.data)
                    .catch((error) => console.error(error));
            }
        }
    };
</script>

<style scoped>
    /**/
</style>
