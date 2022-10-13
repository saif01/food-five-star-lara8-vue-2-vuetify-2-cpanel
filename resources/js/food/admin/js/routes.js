import Vue from 'vue';
import VueRouter from 'vue-router';
Vue.use(VueRouter);

import CHECKAUTH from '../../../auth';

import Dashboard from '../pages/dashboard.vue'

// user
import Admin from '../pages/user/index.vue'
import Operators from '../pages/user/operators.vue'
import Owners from '../pages/user/owners.vue'
import Zone from '../pages/user/zones.vue'
import Role from '../pages/user/roles.vue'
import Log from '../pages/user/log.vue'



// Product Type
import ProductSaleType from '../pages/product_type/sale.vue'
import ProductOrderType from '../pages/product_type/order.vue'

// Product Type
import SaleProduct from '../pages/product/sale.vue'
import OrderProduct from '../pages/product/order.vue'


// outlet
import Outlet from '../pages/outlet/index.vue'

// Orders
import Orders from './../pages/order/index.vue'
import AdjustOrder from '../pages/order/adjust_order.vue'


// Announcement
import Announcement from './../pages/announcement/index.vue'

// Message
import Message from './../pages/message/index.vue'


// report
import OutletReport from '../pages/report/outlet_report.vue'
import ZoneReport from '../pages/report/zone_report.vue'
import HourlyReport from '../pages/report/hourly_report.vue'
import Sku from '../pages/report/sku.vue'

// extra
import er404 from '../pages/common/404.vue'

// profile
import ShowProfile from '../pages/profile/showProfile.vue'


// settings
import scheduleTimer from '../pages/setting/schedule_timer.vue'
import transferData from '../pages/setting/transfer_data.vue'
import sync from '../pages/setting/sync.vue'


const router = new VueRouter({
    mode: 'history',
    routes: [

        {
            path: '/admin',
            component: Dashboard,
            name: 'Dashboard',
            meta: {
                title: 'Admin Dashboard',
            },
        },

        {
            path: '/admin/all-admins',
            component: Admin,
            name: 'admin',
            meta: {
                title: 'Admin Users',
            },
        },
        {
            path: '/admin/owners',
            component: Owners,
            name: 'owners',
            meta: {
                title: 'Admin Owners',
            },
        },
        {
            path: '/admin/operators',
            component: Operators,
            name: 'operators',
            meta: {
                title: 'Admin Operators',
            },
        },
        {
            path: '/admin/zone',
            component: Zone,
            name: 'zone',
            meta: {
                title: 'Admin Zones',
            },
        },

        {
            path: '/admin/role',
            component: Role,
            name: 'role',
            meta: {
                title: 'Admin Roles',
            },
        },

        {
            path: '/admin/log',
            component: Log,
            name: 'log',
            meta: {
                title: 'Admin Logs',
            },
        },





        {
            path: '/admin/sale-category',
            component: ProductSaleType,
            name: 'ProductSaleType',
            meta: {
                title: 'Admin Sale Category',
            },
        },
        {
            path: '/admin/order-category',
            component: ProductOrderType,
            name: 'productOrderType',
            meta: {
                title: 'Admin Order Category',
            },
        },
        {
            path: '/admin/sales-product',
            component: SaleProduct,
            name: 'saleProduct',
            meta: {
                title: 'Admin All Food',
            },
        },
        {
            path: '/admin/order-product',
            component: OrderProduct,
            name: 'orderProduct',
            meta: {
                title: 'Admin All Order Products',
            },
        },

        {
            path: '/admin/outlet',
            component: Outlet,
            name: 'outlet',
            meta: {
                title: 'Admin Outlets',
            },
        },




        {
            path: '/admin/orders',
            component: Orders,
            name: 'orders',
            meta: {
                title: 'Admin Orders',
            },
        },






        {
            path: '/admin/announcement',
            component: Announcement,
            name: 'announcement',
            meta: {
                title: 'Admin Announcement',
            },
        },
        {
            path: '/admin/message',
            component: Message,
            name: 'message',
            meta: {
                title: 'Admin Message',
            },
        },




        {
            path: '/admin/outlet_report',
            component: OutletReport,
            name: 'outletReport',
            meta: {
                title: 'Admin Outlet Report',
            },
        },
        {
            path: '/admin/zone_report',
            component: ZoneReport,
            name: 'zoneReport',
            meta: {
                title: 'Admin Zone Report',
            },
        },
        {
            path: '/admin/hourly_report',
            component: HourlyReport,
            name: 'hourlyReport',
            meta: {
                title: 'Admin Hourly Report',
            },
        },
        {
            path: '/admin/sku',
            component: Sku,
            name: 'sku',
            meta: {
                title: 'Admin SKU Report',
            },
        },
        {
            path: '/admin/adjust-order',
            component: AdjustOrder,
            name: 'adjust_order',
            meta: {
                title: 'Admin Adjust Order',
            },
        },




        {
            path: '/admin/show-profile',
            component: ShowProfile,
            name: 'showProfile',
            meta: {
                title: 'Admin Show Profile',
            },
        },

        {
            path: '/admin/transfer-data',
            component: transferData,
            name: 'transferData',
            meta: {
                title: 'Admin DB Transfer',
            },
        },

        {
            path: '/admin/schedule-timer',
            component: scheduleTimer,
            name: 'scheduleTimer',
            meta: {
                title: 'Admin DB Transfer',
            },
        },

        {
            path: '/admin/sync',
            component: sync,
            name: 'sync',
            meta: {
                title: 'Admin Sync',
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
router.beforeEach( (to, from, next) => {
    // console.log(to, to.meta);
    // await CHECKAUTH.LOGGEDIN();

    let appName = 'CP Five Star';
    let title = to.meta && to.meta.title ? to.meta.title : '';
    // set current title
    document.title = `${appName} ${title}`;

    next();
});


export default router;
