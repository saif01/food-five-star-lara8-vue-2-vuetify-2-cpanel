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

            allData: {},
            paginate: 10,
            search: '',
            search_field: 'All',
            sort_direction: 'desc',
            sort_field: 'id',
            currentPageNumber: null,
            v_total: null,
            // Our data object that holds the Laravel paginator data
            totalValue: '',
            dataShowFrom: '',
            dataShowTo: '',
            start_date: "",
            end_date: "",
            by_zone: "All",
            by_days: "",
            by_type: "All",
            sort_direction_custom: 'desc',
            sort_field_custom: 'id',
            totalOnline: 0,
            

            dataByDaysOptions: [{
                text: 'All',
                value: 'All'
            }, {
                text: 'Last 7 Days',
                value: '7'
            }, {
                text: 'Last 15 Days',
                value: '15'
            }, {
                text: 'Last 30 Days',
                value: '30'
            }, {
                text: 'Last 60 Days',
                value: '60'
            }, {
                text: 'Last 90 Days',
                value: '90'
            }],

            // For Modal Dilog
            dataModalDialog: false,
            // Loading Animation
            dataModalLoading: false,

            editmode: false,
            dataModelTitle: '',
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
            orderLoading: false,

            // Outlets
            allOutlets: '',
            allOutletsOptions: [],

            foodItems2: '',
            previousOrders: '',
            allZones: [],
            allZoneShort: [],
            allAssignZones: [],

            allOutletsByAssignedZone: [],

            // Report Select
            productSaleItems: [],
            selectSaleItem: null,

            zoneSelectItem: [],
            selectZoneItemObj: null,
            currShopInfo: null,
            selectZoneItem: null,

            exportLoading: false,

            category: '',

            title: '',

            setPrevOrderCount: 1,

            menu: false,
            menu2: false,


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

        // toogle online offline status
        toogleStatus() {
            if (this.sort_field == 'id') {
                this.sort_field = 'last_activity';
            } else {
                this.sort_field = 'id';
            }

        },


        // calculate total price
        subTotal() {
            const price = [];
            if (this.orderList.length) {
                this.orderList.forEach(element => {
                    price.push(element.quantity_order * element.tp).toFixed(2);
                });

                const initialValue = 0;
                const sumWithInitial = price.reduce(
                    (previousValue, currentValue) => previousValue + currentValue,
                    initialValue
                );

                return sumWithInitial;
            }

        },
        // calculate total weight
        subWeight() {
            const weight = [];
            if (this.orderList.length) {
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
            }

        },
        // calculate total quantity
        subQuantity() {
            const quantity = [];
            if (this.orderList.length) {
                this.orderList.forEach((element) => {
                    quantity.push(parseInt(element.quantity_order));
                });

                const initialValue = 0;
                const sumWithInitial = quantity.reduce(
                    (previousValue, currentValue) => previousValue + currentValue,
                    initialValue
                );

                return sumWithInitial;

            }

        },

        // All Outlets
        AllOutlets() {
            axios.get('/common/all_outlets').then(response => {
                //console.log(response)
                this.allOutlets = response.data
                if (response.data) {
                    response.data.forEach(element => {
                        this.allOutletsOptions.push({
                            value: element.cv_code,
                            text: "CV: " + element.cv_code + " -- " + element.shop_name + " -- " + element.shop_address,
                        })
                    });
                }
                this.cv_code = this.allOutletsOptions[0].value
                this.currShopInfo = this.allOutletsOptions[0]
                // this.cv_code = '182189'
                //console.log(this.currShopInfo)
            }).catch(error => {
                console.log(error)
            })
        },

        AllOutletsAsync() {
            return axios.get('/common/all_outlets').then(response => {
                //console.log(response)
                this.allOutlets = response.data
                if (response.data) {
                    response.data.forEach(element => {
                        this.allOutletsOptions.push({
                            value: element.cv_code,
                            text: "CV: " + element.cv_code + " -- " + element.shop_name + " -- " + element.shop_address,
                        })
                    });
                }
                this.cv_code = this.allOutletsOptions[0].value
                this.currShopInfo = this.allOutletsOptions[0]
                // this.cv_code = '182189'
                //console.log(this.currShopInfo)
            }).catch(error => {
                console.log(error)
            })
        },


        // get all food item
        getFoodItem() {
            this.loading = true;
            axios
                .get(
                    "/admin/orders/food_item_list?sort_type=" +
                    this.sort_type +
                    "&search=" +
                    this.search2
                )
                .then((response) => {
                    this.foodItems = response.data;
                    this.loading = false;
                });
        },

        getFoodItemAsync() {
            this.loading = true;
            return axios
                .get(
                    "/admin/orders/food_item_list?sort_type=" +
                    this.sort_type +
                    "&search=" +
                    this.search2
                )
                .then((response) => {
                    this.foodItems = response.data;
                    this.loading = false;
                });
        },

        // get all food item
        getFoodItemWithModifyOrder() {
            this.loading = true;
            return axios
                .get(
                    "/admin/orders/food_item_with_modify_order?sort_type=" +
                    this.sort_type +
                    "&search=" + this.search2 +
                    "&order_number=" + this.CurrOrderSummary.order_number
                )
                .then((response) => {

                    this.foodItems2 = response.data.allData;
                    if (this.setPrevOrderCount <= 1) {
                        this.setPrevOrderCount++;
                        this.orderList = response.data.previousorder;
                    }
                    this.loading = false;
                });
        },

        // get all food type
        getType() {
            axios.get("/admin/product/type").then((response) => {
                this.typesData = response.data;
                const allType = {
                    name_en: "All",
                    id: "All",
                };
                this.typesData.unshift(allType);
            });
        },
        // get all food order product type
        getOrderProductType() {
            axios.get("/admin/product/order_product/type").then((response) => {
                this.category = response.data;
            });
        },

        getOrderProductTypeAsync() {
            return axios.get("/admin/product/order_product/type").then((response) => {
                this.category = response.data;
            });
        },

        getOrderProductTypeAll() {
            axios.get("/admin/product/order_product/type").then((response) => {
                this.category = response.data;

                const allType = {
                    name_en: "All",
                    id: "All",
                };
                this.category.unshift(allType);
            });
        },

        getOrderProductTypeAllAsync() {
            return axios.get("/admin/product/order_product/type").then((response) => {
                this.category = response.data;

                const allType = {
                    name_en: "All",
                    id: "All",
                };
                this.category.unshift(allType);
            });
        },

        // all_zones
        getAllZone() {
            axios.get("/common/all_zones").then(response => {
                // For store zone in option
                this.allZones = response.data.zoneData;
                // For data short in option 
                this.allZoneShort = response.data.zoneShortData;
            }).catch(error => {
                console.log(error)
            });
        },

        getAllZoneAsync() {
            return axios.get("/common/all_zones").then(response => {
                // For store zone in option
                this.allZones = response.data.zoneData;
                // For data short in option 
                this.allZoneShort = response.data.zoneShortData;
            }).catch(error => {
                console.log(error)
            });
        },

        //get all assigned zone by user  
        getAllAssignedZones() {
            axios.get("/common/all_assigned_zones_cvcode").then(response => {
                // For store zone in option
                this.allAssignZones = response.data

            }).catch(error => {
                console.log(error)
            });
        },

        getAllAssignedZonesAsync() {
            return axios.get("/common/all_assigned_zones_cvcode").then(response => {
                // For store zone in option
                this.allAssignZones = response.data

            }).catch(error => {
                console.log(error)
            });
        },

        //get all Outlet assigned zone by user  
        getAllOutletsByAssignedZones() {
            axios.get("/common/all_assigned_outlets_byzone").then(response => {
                // For store zone in option
                this.allOutletsByAssignedZone = response.data
            }).catch(error => {
                console.log(error)
            });
        },

        getAllOutletsByAssignedZonesAsync() {
            return axios.get("/common/all_assigned_outlets_byzone").then(response => {
                // For store zone in option
                this.allOutletsByAssignedZone = response.data
            }).catch(error => {
                console.log(error)
            });
        },

        // getSaleProductItemOptions
        getSaleProductItemOptions() {
            axios.get("/admin/report/product_sale_items").then(response => {
                if (response.data) {
                    response.data.forEach(element => {
                        this.productSaleItems.push({
                            value: element.id,
                            text: element.name_en + " -- " + element.type.name_en
                        });
                    });
                    this.selectSaleItem = this.productSaleItems[0].value;
                    // console.log( this.foodItems[0].value)
                }
            });
        },

        // getAllZones
        getAllZones() {
            axios.get("/admin/report/get_all_zone").then(response => {
                this.zoneSelectItem = response.data;
                this.selectZoneItem = this.zoneSelectItem[0].value;
                this.selectZoneItemObj = this.zoneSelectItem[0];
                // console.log(this.zoneSelectItem)
            });
        },


        // exportExcel
        exportExcel(url) {

            this.exportLoading = true;

            axios({
                method: "get",
                url:
                    "/admin/report/" +
                    url +
                    "/export_data?cv_code=" +
                    this.cv_code +
                    "&food_item=" +
                    this.selectSaleItem +
                    "&zone=" +
                    this.selectZoneItem +
                    "&start_date=" +
                    this.start_date +
                    "&end_date=" +
                    this.end_date +
                    "&date=" +
                    this.date,

                responseType: "blob", // important
            })
                .then((response) => {
                    // let repName = new Date();
                    // console.log('title', this.title)
                    const url = URL.createObjectURL(new Blob([response.data]));
                    const link = document.createElement("a");
                    link.href = url;
                    link.setAttribute("download", `${this.title}.xlsx`);
                    document.body.appendChild(link);
                    link.click();

                    this.exportLoading = false;
                })
                .catch((error) => {
                    //stop Loading
                    this.exportLoading = false;
                    console.log(error);
                    Swal.fire({
                        icon: "error",
                        title: "Error !!",
                        text: "Somthing going wrong !!",
                    });
                });


        },

        warning() {
            Swal.fire("Failed!", 'No data found', "warning")
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
        }

        // End Methods
    },



    watch: {

        //Excuted When make change value 
        paginate: function () {
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
        search_field: function () {
            this.$Progress.start();
            this.getResults();
            this.$Progress.finish();
        },

        // clear dialog on close
        dataModal: function (newValue, old) {
            if (!newValue) {
                this.resetForm();
            }
        },

        // By Zone
        by_zone: function () {
            this.$Progress.start();
            this.getResults();
            this.$Progress.finish();
        },

        // by_type
        by_type: function () {
            this.$Progress.start();
            this.getResults();
            this.$Progress.finish();
        },

        sort_field: function () {
            this.$Progress.start();
            this.getResults();
            this.$Progress.finish();
        }

    },




    computed: {

        // map this.count to store.state.count getLoading 
        ...mapGetters({
            'auth': 'getAuth',
            'roles': 'getRoles',
            'order': 'getOrder',
        }),

    },





}
