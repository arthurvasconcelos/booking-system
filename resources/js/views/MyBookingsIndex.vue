<template>
    <div class="container py-4">
        <h1 class="mb-4">My Bookings</h1>

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
                            <th scope="col">Control</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(booking, bIndex) in roomBookings" :key="`booking_${booking.id}`">
                            <th scope="row">{{ bIndex + 1 }}</th>
                            <td>{{ booking.start_at }}</td>
                            <td>{{ booking.end_at }}</td>
                            <td>
                                <div class="btn-group" role="group" aria-label="control">
                                    <button type="button" class="btn btn-outline-info btn-sm" @click.prevent="gotoBookingEdition(booking.id)">edit</button>
                                    <button type="button" class="btn btn-outline-danger btn-sm" @click.prevent="deleteBooking(booking.id)">delete</button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </li>
        </ul>
    </div>
</template>

<script>
    import swal from 'sweetalert';

    export default {
        name: "my-bookings-index",
        data() {
            return {
                bookings: {},
            };
        },
        beforeMount() {
            this.$http.get("/api/my-bookings")
                .then((response) => this.bookings = response.data)
                .catch((error) => console.error(error));
        },
        methods: {
            gotoBookingEdition(id) {
                this.$router.push({ name: "edit-my-booking", params: { id } });
            },
            deleteBooking(id) {
                swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover it!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                    .then((willDelete) => {
                        if (willDelete) {
                            this.$http.delete(`/api/booking/${ id }`)
                                .then(() => {
                                    for (const key in this.bookings) {
                                        this.bookings[key] = this.bookings[key].filter((booking) => booking.id !== id);
                                    }
                                    swal("Your booking has been deleted!", {
                                        icon: "success",
                                    });
                                })
                                .catch((error) => console.error(error));
                        }
                    });
            }
        }
    };
</script>

<style scoped>
    /**/
</style>
