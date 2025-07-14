import setup from ".";
import All from "../pages/All.vue";
import Form from "../pages/Form.vue";
import Details from "../pages/Details.vue";
import Expense from "../pages/Expense.vue";
import Show from "../pages/Show.vue";
import DateWiseData from "../pages/DateWiseData.vue";
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
            path: "datewisedata/:id",
            name: "DateWiseData" + route_prefix,
            component: DateWiseData,
        },
        {
            path: "expense",
            name: "Expense" + route_prefix,
            component: Expense,
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
            path: "details/:id",
            name: "Show" + route_prefix,
            component: Show,
        },
        {
            path: "edit/:id",
            name: "Edit" + route_prefix,
            component: Form,
        },
    ],
};

export default routes;

