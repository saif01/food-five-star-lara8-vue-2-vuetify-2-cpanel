require('./../../../bootstrap');
window.Vue = require('vue').default;

// Router
import router from './routes';

import CHECKAUTH from '../../../auth';
Vue.prototype.$CHECKAUTH = CHECKAUTH

// Vuex File
import store from './store';



// mixin global added
import common from './common/mixin';
Vue.mixin(common);

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


// toCurrency
Vue.filter('toCurrency', function (value, sign = '৳') {
  let val = (value / 1).toFixed(2).replace(",", ".");
  return sign + ' ' + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
})

Vue.filter('toCurrencyBL', function (value, sign = '৳') {
  let val = (value / 1).toFixed(2).replace(",", ".");
  let formatedCurrVal = val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  // English to bangla
  const toBn = n => n.replace(/\d/g, d => "০১২৩৪৫৬৭৮৯"[d])
  let englishToBanglaCurr = toBn(formatedCurrVal)
  return sign + ' ' + englishToBanglaCurr
})

// Random number generate by range
Vue.prototype.randomKey = (min = 1, max = 999) => {
  return min + Math.floor(Math.random() * (max - min + 1));
}

//vue-moment
Vue.use(require('vue-moment'));

// pagination
// Vue.component('pagination', require('laravel-vue-pagination'));
// cart
Vue.component('sale-cart', require('../pages/cart/index.vue').default);
// burger loader
Vue.component('burger-loader', require('../pages/common/burgerLoader.vue').default);

// tbl data not available
Vue.component('data-not-available', require('../../../common/data_not_available.vue').default);
// tbl data loader
Vue.component('tbl-data-loader', require('../../../common/tbl_loader.vue').default);
// scroll-to-top
Vue.component('scroll-to-top', require('../../../common/scroll-to-top.vue').default);
// emergency
Vue.component('emergency', require('../../../common/emergency.vue').default);



Vue.component('index-component', require('../index.vue').default);

// internalization
import i18n from '../../../../Plugins/i18n';



const app = new Vue({
  router,
  CHECKAUTH,
  store,
  i18n,
  vuetify: new Vuetify(),

  data() {
    return {
      // For Preloader
      preloader: false
    }
  }

}).$mount('#app')

