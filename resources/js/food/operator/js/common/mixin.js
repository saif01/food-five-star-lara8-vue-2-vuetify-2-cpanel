import axios from "axios";
import store from '../store'

import {
    mapGetters
} from 'vuex'


import paginateMethods from './paginate_methods'
import imageMethods from './image_methods'
import createUpdate from './crud'
import translateToBangla from './englishToBangla'
import translateToBanglaCalendar from './calendarToBangla'

import i18n from '../../../../../Plugins/i18n';



import {
    debounce
} from './../../../../helpers'


export default {
    data() {
        return {

            // DataTbl Common Featurs 
            paginate: 10,
            search: '',
            search_field: '',
            sort_direction: 'desc',
            sort_field: 'id',
            currentPageNumber: null,
            v_total: null,
            // Tbl number of data show
            tblItemNumberShow: [5, 10, 15, 25, 50, 100],
            // Our data object that holds the Laravel paginator data
            allData: {},
            totalValue: '',
            dataShowFrom: '',
            dataShowTo: '',
            editmode: false,
            dataModelTitle: 'Store Data',
            // Loading Animation
            dataLoading: false,

            imageMaxSize: '2111775',
            fileMaxSize: '5111775',
            overlayshow: false,

            purchaseItem: [],

            // sales cart
            bottomSheet: false,
            bottomSheetKey: 1,
            // sales cart end

            foodItems2: '',
            previousOrders: '',

            // sheet,
            sheet: true,

            setPrevOrderCount: 1,

            category: '',

            menu: false,
            menu2: false,


        }
    },

    methods: {


        // Permission Role check
        // ...globalRolePermissions,

        // Paginate Methods
        ...paginateMethods,

        // Image Upload Methods
        ...imageMethods,

        // create Update Methods
        ...createUpdate,

        // english to bangla
        ...translateToBangla,
        ...translateToBanglaCalendar,

        // For Translate 
        translate(lang) {
            axios.post("/setLanguage/" + lang).then((response) => {
                store.commit('setLang', lang);
            });
            i18n.locale = lang;
        },



        // get all food item
        getFoodItem() {
            this.loading = true;
            axios
                .get(
                    "/item/index?sort_field=" +
                    this.sort_field +
                    "&search=" +
                    this.search2
                )
                .then((response) => {
                    this.foodItems = response.data;
                    this.loading = false;
                });
        },


        // get all food item for order
        getFoodItemForOrder() {
            this.dataLoading = true;
            return axios
                .get(
                    "/order/food_item_for_order?sort_field=" +
                    this.sort_field +
                    "&search=" +
                    this.search2
                )
                .then((response) => {
                    this.foodItems = response.data;
                    this.dataLoading = false;
                });
        },


        // get all food item
        getFoodItemWithModifyOrder() {
            //this.orderLoading = true;
            return axios
                .get(
                    "/order/food_item_with_modify_order?sort_type=" +
                    this.sort_type +
                    "&search=" + this.search2 +
                    "&order_number=" + this.CurrOrderSummary.order_number
                )
                .then((response) => {

                    this.foodItems2 = response.data.food_items;
                    if (this.setPrevOrderCount <= 1) {
                        this.setPrevOrderCount++;
                        this.orderList = response.data.previousorder;
                    }

                    //this.orderLoading = false;
                });
        },

        // get all food type
        // getType() {

        //     var timer = setInterval(() => {
        //         if (this.typesData.length == 0) {
        //             axios.get("/item/type").then((response) => {
        //                 this.typesData = response.data;

        //                 const allType = {
        //                     name_en: "All",
        //                     name_bn: "সব",
        //                     id: "All",
        //                 };

        //                 this.typesData.unshift(allType);
        //             }).catch(error => {
        //                 console.log(error);
        //             });
        //         } else {
        //             clearInterval(timer);
        //         }

        //     }, 1000);

        // },

        logout() {
            sessionStorage.counter = 0;
        },


        subTotal() {
            const price = [];
            this.orderList.forEach((element) => {
                price.push(element.quantity_order * element.tp);
            });

            const initialValue = 0;
            const sumWithInitial = price.reduce(
                (previousValue, currentValue) => previousValue + currentValue,
                initialValue
            );

            return sumWithInitial;
        },

        subWeight() {
            const weight = [];
            this.orderList.forEach((element) => {
                if (element.weight_type == "kg") {
                    weight.push(element.quantity_order * (element.weight * 1000));
                } else {
                    weight.push(element.quantity_order * element.weight);
                }
            });

            const initialWeight = 0;
            const sumWithInitialWeight = weight.reduce(
                (previousValue, currentValue) => previousValue + currentValue,
                initialWeight
            );
            return sumWithInitialWeight / 1000;
        },

        // subquantity
        subQuantity() {
            const quantity = [];
            this.orderList.forEach((element) => {
                quantity.push(parseInt(element.quantity_order));
            });

            const initialValue = 0;
            const sumWithInitial = quantity.reduce(
                (previousValue, currentValue) => previousValue + currentValue,
                initialValue
            );
            return sumWithInitial;
        },

        getProductOrderTypeAll() {
            return axios.get("/item/order_type").then((response) => {
                this.category = response.data.allData;
            });

        },


        formatDate(date) {
            if (!date) return null;

            const [year, month, day] = date.split("-");
            return `${day}/${month}/${year}`;
        },


        numbersOnly(evt) {
            if ((evt.charCode > 31 && (evt.charCode < 48 || evt.charCode > 57)) && evt.charCode !== 46) {
                evt.preventDefault();
            } else {
                return true;
            }
        },


        blockUser() {
            this.logout();
            location.href = 'https://food.cpbangladesh.com/'
            axios.post('/block_user/' + this.auth.id).then((response) => { });
        },


        // Check Auth By Code
        CheckAuthByCode(code) {
            
        }




        // End Methods
    },

    watch: {

        //Excuted When make change value 
        paginate: function (value) {
            this.$Progress.start();
            this.getResults();
            this.$Progress.finish();
        },

        //Excuted When make change value 
        search: debounce(function () {
            this.$Progress.start();
            this.getResults();
            this.$Progress.finish();
        }, 500),

        //Excuted When make change value 
        search_field: function (value) {
            this.$Progress.start();
            this.getResults();
            this.$Progress.finish();
        },






    },


    computed: {

        // map this.count to store.state.count getLoading 
        ...mapGetters({
            'auth': 'getAuth',
            'roles': 'getRoles',
            'cart': 'getCart',
            'cDetails': 'getCdetails',
            'currentLang': 'getLang',
            'announce': 'getAnnounce',
            'cartTrigger': 'getCartTrigger'
        }),

    },



}
