import Vue from "vue";
import VueRouter from "vue-router";
import Login from "./views/Login";
import Register from "./views/Register";
import Home from "./views/Home";
import Rooms from "./views/Rooms";
import NewBooking from "./views/NewBooking";
import Me from "./views/Me";
import MyBookings from "./views/MyBookings";
import MyBookingsIndex from "./views/MyBookingsIndex";
import EditMyBooking from "./views/EditMyBooking";

Vue.use(VueRouter);

const router = new VueRouter({
    mode: "history",
    base: process.env.BASE_URL,
    linkActiveClass: "is-active",
    linkExactActiveClass: "is-active-exact",
    scrollBehavior(to, from, savedPosition) {
        if (to.hash) {
            return { selector: to.hash };
        } else if (savedPosition) {
            return savedPosition;
        } else {
            return { x: 0, y: 0 };
        }
    },
    routes: [
        {
            path: "/",
            name: "home",
            component: Home,
        },
        {
            path: "/login",
            name: "login",
            component: Login,
        },
        {
            path: "/register",
            name: "register",
            component: Register,
        },
        {
            path: "/rooms",
            name: "rooms",
            component: Rooms,
        },
        {
            path: "/new-booking",
            name: "new-booking",
            component: NewBooking,
        },
        {
            path: "/me",
            component: Me,
            children: [
                {
                    path: "my-bookings",
                    component: MyBookings,
                    children: [
                        {
                            path: "",
                            name: "my-bookings",
                            component: MyBookingsIndex,
                        },
                        {
                            path: "edit/:id",
                            name: "edit-my-booking",
                            component: EditMyBooking,
                        },
                    ],
                }
            ],
        },
    ]
});

router.beforeEach((to, from, next) => {
    if (to.name !== "login" && to.name !== "register" && !localStorage.getItem("jwt")) {
        return next("login");
    }

    if ((to.name === "login" || to.name === "register") && localStorage.getItem("jwt")) {
        return next(from.path || "/");
    }

    next();
});

export default router;
