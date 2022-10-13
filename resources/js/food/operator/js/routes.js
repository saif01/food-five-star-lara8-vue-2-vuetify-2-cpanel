import Vue from 'vue';
import VueRouter from 'vue-router';
Vue.use(VueRouter);

import CHECKAUTH from '../../../auth';

import Dashboard from '../pages/dashboard.vue'
import Announce from '../pages/announce/index.vue'

import EditProfile from '../pages/profile/editProfile.vue'
import ResetPass from '../pages/profile/resetPass.vue'

// import DailySales from '../pages/report/dailySales.vue'
// import MonthlySales from '../pages/report/monthlySales.vue'

import Report from '../pages/report/index.vue'
import AllSales from '../pages/allsales/index.vue'

import AllOrder from '../pages/order/index.vue'
import NewOrder from '../pages/order/order.vue'
import CurrentStockOrder from '../pages/stock/currentStock.vue'

import er404 from '../pages/common/404.vue'


const router = new VueRouter({
    mode: 'history',
    routes: [

        {
            path: '/',
            component: Dashboard,
            name: 'Dashboard',
            meta: {
                title: 'Dashboard',
            },
        },

        {
            path: '/announce',
            component: Announce,
            name: 'Announce',
            meta: {
                title: 'Announce',
            },
        },

        {
            path: '/profile',
            component: EditProfile,
            name: 'EditProfile',
            meta: {
                title: 'Edit Profile',
            },
        },
        {
            path: '/reset-password',
            component: ResetPass,
            name: 'ResetPass',
            meta: {
                title: 'Reset Password',
            },
        },

        {
            path: '/report',
            component: Report,
            name: 'Report',
            meta: {
                title: 'Report',
            },
        },

        {
            path: '/all-sales',
            component: AllSales,
            name: 'AllSales',
            meta: {
                title: 'All Sales',
            },
        },

        {
            path: '/all-order',
            component: AllOrder,
            name: 'AllOrder',
            meta: {
                title: 'All Order',
            },
        },

        {
            path: '/new-order',
            component: NewOrder,
            name: 'NewOrder',
            meta: {
                title: 'New Order',
            },
        },

        {
            path: '/stock',
            component: CurrentStockOrder,
            name: 'CurrentStockOrder',
            meta: {
                title: 'Current Stock Order',
            },
        },



        {
            path: '/*',
            component: er404,
            name: 'er404',
            meta: {
                title: '404',
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