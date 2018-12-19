<template>
    <div class="container d-flex flex-column justify-content-center align-items-center" style="height: 100vh;">
        <div class="row justify-content-center w-100">
            <h1 class="col-md-8 text-center">Booking System</h1>
        </div>
        <div class="row justify-content-center w-100">
            <div class="col-md-8">
                <div class="card card-default">
                    <div class="card-header">Register</div>

                    <div class="card-body">
                        <form @submit.prevent="doRegister()" :class="{ 'was-validated': isFormValid && this.errors.items.length === 0 }">
                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>
                                <div class="col-md-6">
                                    <input
                                        id="name"
                                        type="text"
                                        class="form-control"
                                        name="name"
                                        v-model="name"
                                        v-validate="'required|alpha|min:3'"
                                        autofocus>
                                    <div v-show="errors.has('name')" class="invalid-feedback">{{ errors.first('name') }}</div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>
                                <div class="col-md-6">
                                    <input
                                        id="email"
                                        type="email"
                                        class="form-control"
                                        name="email"
                                        v-model="email"
                                        v-validate="'required|email'">
                                    <div v-show="errors.has('email')" class="invalid-feedback">{{ errors.first('email') }}</div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
                                <div class="col-md-6">
                                    <input id="password"
                                           type="password"
                                           class="form-control"
                                           name="password"
                                           v-model="password"
                                           v-validate="'required|min:6'">
                                    <div v-show="errors.has('password')" class="invalid-feedback">{{ errors.first('password') }}</div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Confirm Password</label>
                                <div class="col-md-6">
                                    <input
                                        id="password-confirm"
                                        type="password"
                                        class="form-control"
                                        name="passwordConfirmation"
                                        v-model="passwordConfirmation"
                                        v-validate="'required|confirmed:password'">
                                    <div v-show="errors.has('passwordConfirmation')" class="invalid-feedback">{{ errors.first('passwordConfirmation') }}</div>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary" :disabled="this.errors.items.length > 0">Register</button>
                                    <router-link class="btn btn-outline-secondary" :to="{ name: 'login' }">Back</router-link>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "register",
        data(){
            return {
                isFormValid: false,
                name: "",
                email: "",
                password: "",
                passwordConfirmation: "",
            };
        },
        methods : {
            doRegister() {
                this.$validator.validateAll()
                    .then((result) => {
                        if (result) {
                            this.isFormValid = true;
                            return this.$http
                                .post("/api/register", {
                                    name: this.name,
                                    email: this.email,
                                    password: this.password,
                                    c_password : this.passwordConfirmation
                                })
                                .then((response) => this.registerLogin(response))
                                .catch(error => console.error(error));
                        }

                        this.isFormValid = false;
                    });
            }
        },
    };
</script>

<style scoped>
    /**/
</style>
