import setup from ".";
import All from "../pages/All.vue";
import Form from "../pages/Form.vue";
import Details from "../pages/Details.vue";
import Layout from "../pages/Layout.vue";

import User from "../pages/User.vue";
import Daily from "../pages/Daily.vue";
import Monthly from "../pages/Monthly.vue";
import MonthlyDetails from "../pages/MonthlyDetails.vue";
// import MonthlyDetailsInvoice from "../pages/MonthlyDetailsInvoice.vue";
import UserDetails from "../pages/UserDetails.vue";

let route_prefix = setup.route_prefix;
let route_path = setup.route_path;

const routes = {
    path: route_path,
    component: Layout,
    children: [
        {
            path: "all",
            name: "All" + route_prefix,
            component: All,
        },
        {
            path: "create",
            name: "Create" + route_prefix,
            component: Form,
        },
        {
            path: "details/:id",
            name: "Details" + route_prefix,
            component: Details,
        },
        {
            path: "edit/:id",
            name: "Edit" + route_prefix,
            component: Form,
        },


        {
            path: "user-report",
            name: "User" + route_prefix,
            component: User,
        },
        {
            path: "daily-report",
            name: "Daily" + route_prefix,
            component: Daily,
        },
        {
            path: "monthly-report",
            name: "Monthly" + route_prefix,
            component: Monthly,
        },
        {
            path: "monthly-details/:month",
            name: "MonthlyDetails" + route_prefix,
            component: MonthlyDetails,
        },
        // {
        //     path: "monthly-details-invoice/:month",
        //     name: "MonthlyDetailsInvoice" + route_prefix,
        //     component: MonthlyDetailsInvoice,
        // },
        {
            path: "user-details/:slug/:date",
            name: "UserDetails" + route_prefix,
            component: UserDetails,
        },
    ],
};

export default routes;

