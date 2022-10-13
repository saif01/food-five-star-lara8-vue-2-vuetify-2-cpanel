import Vue from 'vue';
import VueRouter from 'vue-router';
Vue.use(VueRouter);

import CHECKAUTH from '../../../auth';

import Dashboard from '../pages/dashboard.vue'
import outlet_information from '../pages/profile/outlet_information.vue'
import reset from '../pages/profile/resetPass.vue'
import showProfile from '../pages/profile/showProfile.vue'
import currentStockOrder from '../pages/stock/index.vue'

import reportSales from '../pages/report/sales.vue'


// extra
import er404 from '../pages/common/404.vue'






const router = new VueRouter({
    mode: 'history',
    routes: [

        {
            path: '/owner',
            component: Dashboard,
            name: 'Dashboard',
            meta: {
                title: 'Owner Dashboard',
            },
        },
        {
            path: '/owner/outlet-information',
            component: outlet_information,
            name: 'OutletInformation',
            meta: {
                title: 'Owner Outlet Information',
            },
        },
        {
            path: '/owner/reset-password',
            component: reset,
            name: 'Reset',
            meta: {
                title: 'Owner reset password',
            },
        },

        {
            path: '/owner/profile',
            component: showProfile,
            name: 'ShowProfile',
            meta: {
                title: 'Owner Profile',
            },
        },

        {
            path: '/owner/current-stock',
            component: currentStockOrder,
            name: 'CurrentStockOrder',
            meta: {
                title: 'Current Stock Order',
            },
        },
        {
            path: '/owner/report-sales',
            component: reportSales,
            name: 'ReportSales',
            meta: {
                title: 'Report Sales',
            },
        },






        {
            path: '/admin/*',
            component: er404,
            name: 'er404',
            meta: {
                title: 'Admin 404',
            },
        },

    ]
});


// Run brfore every route request
router.beforeEach((to, from, next) => {
    // console.log(to, to.meta);
    // await CHECKAUTH.LOGGEDIN();

    let appName = 'CP Five Star';
    let title = to.meta && to.meta.title ? to.meta.title : '';
    // set current title
    document.title = `${appName} ${title}`;

    next();
});


export default router;
