require('./../../../bootstrap');
window.Vue = require('vue').default;

// Router
import router from './routes';

// Vuex File
import store from './store';


// mixin global added
import common from './common/mixin';
Vue.mixin(common);


// import  BootstrapVue from 'bootstrap-vue';
// Vue.use(BootstrapVue)

// Vuetify
import Vuetify from 'vuetify'
Vue.use(Vuetify)

// VueProgressBar
import VueProgressBar from 'vue-progressbar';

Vue.use(VueProgressBar, {
    color: '#66FE5E',
    failedColor: 'red',
    thickness: '3px',
});


// tbl data not available
Vue.component('data-not-available', require('../../../common/data_not_available.vue').default);
// tbl data loader
Vue.component('tbl-data-loader', require('../../../common/tbl_loader.vue').default);
// scroll-to-top
Vue.component('scroll-to-top', require('../../../common/scroll-to-top.vue').default);

// toCurrency
Vue.filter('toCurrency', function (value, sign = '৳') {
  let val = (value / 1).toFixed(2).replace(",", ".");
  return sign + ' ' + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
})

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




//vue-moment
Vue.use(require('vue-moment'));

// pagination
// Vue.component('pagination', require('laravel-vue-pagination'));


Vue.component('index-component', require('../index.vue').default);



const app = new Vue({
  router,
  store,
  vuetify: new Vuetify(),

  data(){
    return{
      // For Preloader
      preloader:false
    }
  }
  
}).$mount('#app')

