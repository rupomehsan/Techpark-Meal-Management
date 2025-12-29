//app layout
import Layout from "../Layouts/Layout.vue";
//Dashboard
import Dashboard from "../Management/Dashboard/Dashboard.vue";
//SettingsRoutes
import SettingsRoutes from "../Management/Settings/setup/routes.js";
//routes

import EmployeePaymentRoutes from '../Management/StudentPayment/setup/routes.js';
import UserMealRoutes from '../Management/MealManagement/UserMeal/setup/routes.js';



const routes = {
    path: '',
    component: Layout,
    children: [
        {
            path: 'dashboard',
            component: Dashboard,
            name: 'adminDashboard',
        },
        UserMealRoutes,
        EmployeePaymentRoutes,
        SettingsRoutes,
    ],
};

export default routes;

