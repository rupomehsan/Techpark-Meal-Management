//app layout
import Layout from "../Layouts/Layout.vue";
//Dashboard
import Dashboard from "../Management/Dashboard/Dashboard.vue";
//SettingsRoutes
import SettingsRoutes from "../Management/Settings/setup/routes.js";
//routes

import UserRoutes from '../Management/UserManagement/User/setup/routes.js';
import UserPaymentsRoutes from '../Management/UserManagement/UserPayments/setup/routes.js';
import DueListRoutes from '../Management/DueListManagement/DueList/setup/routes.js';
import DailyBajarRoutes from '../Management/DailyBajarManagement/DailyBajar/setup/routes.js';
import BatchRoutes from '../Management/BatchManagement/Batch/setup/routes.js';


import MonthlyMealRate from '../Management/MealManagement/MonthlyMealRate/setup/routes.js';




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
        UserPaymentsRoutes,
        DueListRoutes,
        DailyBajarRoutes,
        BatchRoutes,
        
        MonthlyMealRate,
        //settings
        SettingsRoutes,
    ],
};

export default routes;

