import axios from "axios";
import moment from "moment";
import VeeValidate from "vee-validate";
import Vue from "vue";
import VueIziToast from "vue-izitoast";
import App from "./App.vue";
import router from "./router";

import "izitoast/dist/css/iziToast.css";
import "./bootstrap";

VeeValidate.Validator.extend("after_now", {
    getMessage(field/*, args*/) {
        const now = moment().format("DD/MM/YYYY  HH:mm");
        return `The ${ field } must be after now (${ now }).`;
    },
    validate(value/*, args*/) {
        const now = moment().format("YYYY-MM-DDTHH:mm");
        return moment(value).isAfter(moment(now));
    }
});

Vue.use(VeeValidate, {
    classes: true,
    classNames: {
        valid: 'is-valid',
        invalid: 'is-invalid'
    }
});
Vue.use(VueIziToast, {
    position: 'topCenter',
});

Vue.prototype.$http = axios;

Vue.mixin({
    data() {
        return {
            registerLogin(response) {
                const token = response.data.success.token;

                localStorage.setItem("user", JSON.stringify(response.data.success.user));
                localStorage.setItem("jwt", token);
                localStorage.setItem("expiresAt", JSON.stringify(response.data.success.expiresAt));

                if (token !== null) {
                    this.$http.defaults.headers.common["Content-Type"] = "application/json";
                    this.$http.defaults.headers.common["Authorization"] = `Bearer ${token}`;
                    this.$router.push({ name: "home" });
                }
            },
            getUser() {
                const lsUser = localStorage.getItem("user");

                if (lsUser) {
                    return JSON.parse(lsUser);
                }

                return null;
            },
            isAuth() {
                const jwt = localStorage.getItem("jwt");
                const exp = JSON.parse(localStorage.getItem("expiresAt"));
                return jwt !== null && moment(exp.date).isSameOrAfter(moment());
            },
        }
    }
});

new Vue({
    router,
    render: h => h(App),
}).$mount("#app");
