<template>
    <form class="py-4" @submit.prevent="sendBooking()" :class="{ 'was-validated': isFormValid && this.errors.items.length === 0 }">
        <div class="form-group">
            <label for="room">Select the room</label>
            <select
                class="form-control"
                id="room"
                name="room"
                v-model="selectedRoom"
                v-validate="'required'">
                <option v-for="room in rooms" :key="room.id" :value="room.id">{{ room.name }}</option>
            </select>
            <div v-show="errors.has('room')" class="invalid-feedback">{{ errors.first('room') }}</div>
        </div>

        <div class="form-group">
            <label for="startAt">Start when</label>
            <input
                type="datetime-local"
                class="form-control"
                id="startAt"
                name="startAt"
                v-model="startAt"
                v-validate="'required|after_now'">
            <div v-show="errors.has('startAt')" class="invalid-feedback">{{ errors.first('startAt') }}</div>
        </div>

        <div class="form-group">
            <label for="lengthOfBooking">Length of booking (in minutes)</label>
            <input
                type="number"
                class="form-control"
                id="lengthOfBooking"
                min="10"
                name="lengthOfBooking"
                v-model="duration"
                v-validate="'required|numeric|min_value:10'">
            <div v-show="errors.has('lengthOfBooking')" class="invalid-feedback">{{ errors.first('lengthOfBooking') }}</div>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
        <button class="btn btn-outline-secondary" v-if="booking" @click.prevent="goBack()">Back</button>
    </form>
</template>

<script>
    import moment from 'moment';

    export default {
        name: "booking-form",
        props: {
            booking: {
                type: Object,
                default: null
            },
        },
        data() {
            return {
                isFormValid: false,
                rooms: [],
                selectedRoom: null,
                startAt: null,
                duration: null,
            };
        },
        beforeMount() {
            this.$http.get("/api/room")
                .then((response) => this.rooms = response.data)
                .catch((error) => console.log(error));
        },
        mounted() {
            if (this.booking) {
                this.selectedRoom = this.booking.room_id;
                this.startAt = moment(this.booking.start_at).format("YYYY-MM-DDTHH:mm");
                this.duration = moment(this.booking.end_at).diff(this.booking.start_at, 'minutes');
            }
        },
        methods: {
            sendBooking() {
                const data = {
                    room: this.selectedRoom,
                    startAt: this.startAt,
                    duration: this.duration,
                };

                this.$validator.validateAll()
                    .then((result) => {
                        if (result) {
                            this.isFormValid = true;

                            if (!this.booking) {
                                this.$http
                                    .post("/api/booking", data)
                                    .then(() => {
                                        this.$toast.success('Room booked!', 'Success');
                                        this.$router.push({ name: 'my-bookings' });
                                    })
                                    .catch((error) => this.$toast.error(error.response.data.message, 'Something went wrong.'));
                            } else {
                                this.$http
                                    .patch(`/api/booking/${ this.booking.id }`, data)
                                    .then(() => {
                                        this.$toast.success('Booking edited!', 'Success');
                                        this.$router.back();
                                    })
                                    .catch((error) => this.$toast.error(error.response.data.message, 'Something went wrong.'));
                            }
                        }

                        this.isFormValid = false;
                    });
            },
            goBack() {
                this.$router.back();
            },
        },
    };
</script>

<style scoped>
    /**/
</style>
