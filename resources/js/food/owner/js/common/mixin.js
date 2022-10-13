import axios from "axios";
import {
    mapGetters
} from 'vuex'


import paginateMethods from './paginate_methods'
import imageMethods from './image_methods'
import createUpdate from './crud'


import globalRolePermissions from './../../../../role_permissions'
import {
    debounce
} from './../../../../helpers'




export default {
    data() {
        return {

            paginate: 10,
            search: '',
            search_field: '',
            sort_direction: 'desc',
            sort_field: 'id',
            currentPageNumber: null,
            // Our data object that holds the Laravel paginator data
            allData: {},
            totalValue: '',
            dataShowFrom: '',
            dataShowTo: '',

            // For Modal Dilog
            dataModalDialog: false,
            // Loading Animation
            dataModalLoading: false,

            editmode: false,
            dataModelTitle: 'Store Data',
            // Loading Animation
            dataLoading: false,

            imageMaxSize: '2111775',
            fileMaxSize: '5111775',

            // Tbl number of data show
            tblItemNumberShow: [5, 10, 15, 25, 50, 100],
            // v-form
            valid: false,

            dataModal: false,
            dataLodaing: false,
            currShopInfo: null,

            menu: false,
            menu2: false,
            menu3: false,
            menu4: false,
        }
    },

    methods: {


        // Permission Role check
        ...globalRolePermissions,

        // Paginate Methods
        ...paginateMethods,

        // Image Upload Methods
        ...imageMethods,

        // create Update Methods
        ...createUpdate,


        getAllShop() {
            return axios.get("/owner/report/get_all_shop").then((response) => {

                if (response.data) {
                    this.allShopDetails = response.data;

                    response.data.forEach((element) => {
                        this.allShop.push({
                            value: element.cv_code,
                            text:
                                "CV: " +
                                element.cv_code +
                                " -- " +
                                element.shop_name +
                                " -- " +
                                element.shop_address,
                        });
                    });
                }
                this.cv_code = this.allShop[0].value;
                this.currShopInfo = this.allShop[0]
            });
        },


        logout() {
            sessionStorage.counterOwner = 0;
        },

        formatDate(date) {
            if (!date) return null;

            const [year, month, day] = date.split("-");
            return `${day}/${month}/${year}`;
        },

        formatYear(date) {
            if (!date) return null;

            const [year, month, day] = date.split("-");
            return `${year}`;
        },



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
        }),

    },



}
