//app layout
import Layout from "../Layouts/Layout.vue";
//Dashboard
import Dashboard from "../Management/Dashboard/Dashboard.vue";
//SettingsRoutes
import SettingsRoutes from "../Management/Settings/setup/routes.js";
//routes

import UserRoutes from '../Management/UserManagement/User/setup/routes.js';
import BlogCategroy from '../Management/BasicTestModule/BlogCategory/setup/routes.js';
// Meal management
import UserMeal from '../Management/MealManagement/UserMeal/setup/routes.js';




const routes = {
    path: '',
    component: Layout,
    children: [
        {
            path: 'dashboard',
            component: Dashboard,
            name: 'adminDashboard',
        },
        //management routes
        UserRoutes,
        BlogCategroy,
        // Meal Management
        UserMeal,
        //settings
        SettingsRoutes,
    ],
};

export default routes;

