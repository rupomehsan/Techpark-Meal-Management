import setup from ".";
import All from "../pages/All.vue";
import Form from "../pages/Form.vue";
import Edit from "../pages/Edit.vue";
import MonthlyMeal from "../pages/MonthlyMeal.vue";
// import PaymentHistory from "../pages/PaymentHistory.vue";
import Details from "../pages/Details.vue";
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
            component: Edit,
        },
        {
            path: "monthlymeal/:date",
            name: "MonthlyMeal" + route_prefix,
            component: MonthlyMeal,
        },
        // {
        //     path: "payment",
        //     name: "Payment" + route_prefix,
        //     component: PaymentHistory,
        // },
    ],
};

export default routes;

