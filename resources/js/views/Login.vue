<template>
    <div  class="container d-flex flex-column justify-content-center align-items-center" style="height: 100vh;">
        <div class="row justify-content-center w-100">
            <h1 class="col-md-8 text-center">Booking System</h1>
        </div>
        <div class="row justify-content-center w-100">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Login</div>

                    <div class="card-body">
                        <form @submit.prevent="login()" :class="{ 'was-validated': isFormValid && this.errors.items.length === 0 }">
                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">Email</label>
                                <div class="col-md-6">
                                    <input
                                        id="email"
                                        type="email"
                                        class="form-control"
                                        name="email"
                                        v-model="email"
                                        v-validate="'required|email'"
                                        autofocus>
                                    <!--<div v-show="!errors.has('email')" class="valid-feedback">Looks good!</div>-->
                                    <div v-show="errors.has('email')" class="invalid-feedback">{{ errors.first('email') }}</div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
                                <div class="col-md-6">
                                    <input
                                        id="password"
                                        type="password"
                                        class="form-control"
                                        name="password"
                                        v-model="password"
                                        v-validate="'required|min:6'">
                                    <div v-show="errors.has('password')" class="invalid-feedback">{{ errors.first('password') }}</div>
                                </div>
                            </div>

                            <!--<div class="form-group row">-->
                                <!--<div class="col-md-6 offset-md-4">-->
                                    <!--<div class="form-check">-->
                                        <!--<input class="form-check-input" type="checkbox" name="remember" id="remember" v-model="remember">-->
                                        <!--<label class="form-check-label" for="remember">Remember Me</label>-->
                                    <!--</div>-->
                                <!--</div>-->
                            <!--</div>-->

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary" :disabled="this.errors.items.length > 0">Login</button>
                                    <router-link class="btn btn-link" :to="{ name: 'register' }">Create new account.</router-link>
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
        name: "login",
        data() {
            return {
                isFormValid: false,
                email: "",
                password: "",
                remember: false
            };
        },
        methods: {
            login() {
                this.$validator.validateAll()
                    .then((result) => {
                        if (result) {
                            this.isFormValid = true;
                            return this.$http
                                .post("/api/login", {
                                    email: this.email,
                                    password: this.password,
                                    remember: this.remember
                                })
                                .then((response) => this.registerLogin(response))
                                .catch((error) => this.$toast.error(error.response.data.message, 'Something went wrong.'));
                        }

                        this.isFormValid = false;
                    });
            }
        }
    };
</script>

<style scoped>
    /**/
</style>
