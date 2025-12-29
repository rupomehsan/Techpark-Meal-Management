import setup from ".";
import All from "../pages/All.vue";
import Form from "../pages/Form.vue";
import Details from "../pages/Details.vue";
import PaymentHistory from '../../User/pages/PaymentHistory.vue';
import MealHistory from '../../User/pages/MealHistory.vue';
import Layout from "../pages/Layout.vue";

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
            path: "paymenthistory/:id",
            name: "PaymentHistory" + route_prefix,
            component: PaymentHistory,
        },
        {
            path: "mealhistory/:id",
            name: "MealHistory" + route_prefix,
            component: MealHistory,
        },
    ],
};

export default routes;

