require('../../bootstrap');
window.Vue = require('vue').default;


import Vuetify from 'vuetify'
Vue.use(Vuetify)


import VueRouter from 'vue-router';
Vue.use(VueRouter);

// tbl data loader
Vue.component('tbl-data-loader', require('../../common/tbl_loader.vue').default);


import Login from '../pages/login.vue'
import Maintenance from '../pages/maintenance.vue'
import MaintenanceLogin from '../pages/maintenance-login.vue'

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/login',
            name: 'Login',
            component: Login,
            meta: {
                title: 'Login',
            },
        },

        {
            path: '/maintenance-mode',
            name: 'maintenance',
            component: Maintenance,
            meta: {
                title: 'Maintenance',
            },
        },

        {
            path: '/maintenance-login',
            name: 'maintenance-login',
            component: MaintenanceLogin,
            meta: {
                title: 'Maintenance Login',
            },
        },

        {
            path: '/login/reset-password',
            name: 'resetPassword',
            component: () => import('./../pages/reset_password.vue'),
            meta: {
                title: 'Reset Password',
            },
        },

        {
            path: '/login/input-otp',
            name: 'otp_input',
            component: () => import('./../pages/otp_input.vue'),
            meta: {
                title: 'Input OTP',
            },
        },

        {
            path: '/login/new-password',
            name: 'new_password',
            component: () => import('./../pages/new_password.vue'),
            meta: {
                title: 'New Password',
            },
        },

        {
            path: '/login/*',
            component: Login,
            name: 'error',
            meta: {
                title: 'Login',
            },
        },

    ],
});


// Run brfore every route request
router.beforeEach((to, from, next) => {
    // console.log(to, to.meta);

    let appName = 'CP Five Star';
    let title = to.meta && to.meta.title ? to.meta.title : '';
    // set current title
    document.title = `${appName} ${title}`;

    next();
});



// VueProgressBar
import VueProgressBar from 'vue-progressbar';

Vue.use(VueProgressBar, {
    color: '#66FE5E',
    failedColor: 'red',
    thickness: '3px',
});

// sweetalert2
import Swal from 'sweetalert2';
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
})
window.Swal = Swal;
window.Toast = Toast;


Vue.component('index-component', require('../index.vue').default);

//vue-moment
Vue.use(require('vue-moment'));

const app = new Vue({
    el: '#app',
    router,
    vuetify: new Vuetify(),

    data() {
        return {
            // For Preloader
            preloader: false
        }
    },

    // created() {
    //     // this.$vuetify.theme.dark = true
    //     this.$vuetify.theme.dark =
    //         window.matchMedia("(prefers-color-scheme)").media !== "not all";
    // },


});
