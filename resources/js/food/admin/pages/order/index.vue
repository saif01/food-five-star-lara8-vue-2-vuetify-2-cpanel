<template>
  <div>
    <div class="d-flex">
      <router-link
        link
        route
        :to="{ name: 'Dashboard' }"
        small
        exact
        class="primary--text text-decoration-none"
        >Dashboard</router-link
      >&nbsp;/ <span class="text-muted">&nbsp;Order&nbsp;</span> /
      <span class="font-weight-bold">&nbsp;All</span>
    </div>
    <v-card elevation="0">
      <v-card-title class="justify-content-between">
        <div>All Order List</div>
        <div>
          <!-- <v-btn
            class="btn_excel float-right ml-3"
            elevation="20"
            small
            @click="
              allSkuData.length && allData.data ? exportExcel() : warning()
            "
            :loading="exportLoading"
            v-if="isPermit('order-export-order')"
          >
            <v-icon left>mdi-file-excel</v-icon>Export
          </v-btn> -->

          <v-btn
            @click="(placeOrderModal = true), orderKey++"
            class="btn_order float-right"
            elevation="20"
            small
            v-if="isPermit('order-place-order')"
          >
            <v-icon left>mdi-food-fork-drink</v-icon>Place Order
          </v-btn>
        </div>
      </v-card-title>

      <v-card-text>
        <div>
          <v-row>
            <v-col cols="6" lg="2">
              <v-select
                prepend-icon="mdi-database-eye"
                :items="tblItemNumberShow"
                v-model="paginate"
                label="Show:"
                dense
              ></v-select>
            </v-col>

            <v-col cols="6" lg="3" v-if="allTypeOption">
              <!-- Type -->
              <v-autocomplete
                :items="allTypeOption"
                v-model="by_type"
                label="Type"
                placeholder="Select Type"
                prepend-icon="mdi-format-list-bulleted"
                dense
              ></v-autocomplete>
            </v-col>

            <v-col cols="6" lg="2" v-if="allZoneShort">
              <!-- zone -->
              <v-autocomplete
                :items="allAssignZones"
                item-text="name"
                item-value="cv_code"
                v-model="by_zone"
                label="Zone"
                prepend-icon="mdi-map-marker-outline"
                placeholder="Select Zone"
                dense
              ></v-autocomplete>
            </v-col>

            <v-col cols="6" lg="2">
              <!-- <v-text-field
                type="date"
                label="Date"
                v-model="start_date"
                dense
              ></v-text-field>-->
              <v-menu v-model="menu" min-width="auto">
                <template v-slot:activator="{ on, attrs }">
                  <v-text-field
                    v-model="computedDate"
                    label="Date"
                    prepend-icon="mdi-calendar"
                    readonly
                    v-bind="attrs"
                    v-on="on"
                    dense
                  ></v-text-field>
                </template>

                <v-date-picker v-model="start_date" no-title scrollable>
                  <v-spacer></v-spacer>
                  <v-btn text color="primary" @click="menu = false"
                    >Cancel</v-btn
                  >
                </v-date-picker>
              </v-menu>
            </v-col>

            <v-col cols="12" lg="3">
              <v-text-field
                prepend-icon="mdi-clipboard-text-search"
                v-model="search"
                label="Search:"
                placeholder="Search ..."
                dense
              ></v-text-field>
            </v-col>
          </v-row>

          <v-expansion-panels
            class="mb-5"
            v-if="allSkuData.length && allData.data && !dataLoading"
          >
            <v-expansion-panel>
              <v-expansion-panel-header>
                <div class="table-responsive">
                  <table class="table table-bordereless">
                    <thead>
                      <tr>
                        <td>
                          Date:
                          <span class="font-weight-bold">{{
                            start_date | moment("dddd, MMMM Do YYYY")
                          }}</span>
                        </td>

                        <td>
                          Quantity:
                          <span class="font-weight-bold">{{
                            totalQuantity
                          }}</span>
                        </td>

                        <td>
                          Weight:
                          <span class="font-weight-bold"
                            >{{ totalWeight / 1000 }} Kg</span
                          >
                        </td>

                        <td>
                          Amount:
                          <span class="font-weight-bold">{{
                            totalPrice | toCurrency
                          }}</span>
                        </td>
                      </tr>
                    </thead>
                  </table>
                </div>

                <template v-slot:actions>
                  <v-icon color="indigo">$expand</v-icon>
                </template>
              </v-expansion-panel-header>
              <v-expansion-panel-content>
                <div class="table-responsive">
                  <table class="table table-bordered text-right table-sm">
                    <thead>
                      <tr class="table_head">
                        <th class="text-left">Product Code</th>
                        <th class="text-left">Product Name</th>
                        <th>Price</th>
                        <th>Quantity (Set)</th>
                        <th>Weight (kg)</th>
                        <th>Amount</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="item in allSkuData" :key="item.id">
                        <td class="text-left">{{ item.product_code }}</td>
                        <td class="text-left">
                          {{ item.product_name }} ({{ item.product_type }})
                          <v-btn
                            v-if="isPermit('order-adjust-order-view')"
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="Adjust Order"
                            icon
                            @click="
                              $router.push({
                                name: 'adjust_order',
                                params: {
                                  selectedDate: start_date,
                                  product_id: item.product_id,
                                },
                              })
                            "
                          >
                            <v-icon right
                              >mdi-file-document-edit-outline</v-icon
                            >
                          </v-btn>
                        </td>
                        <td>{{ item.product_unit_price | toCurrency }}</td>
                        <td>{{ item.quantity }}</td>
                        <td>
                          <span v-if="item.weight / 1000">{{
                            item.weight / 1000
                          }}</span>
                          <span v-else class="error--text">N/A</span>
                        </td>
                        <td>{{ item.price | toCurrency }}</td>
                      </tr>
                      <tr
                        style="background-color: #004d40"
                        class="text-white font-weight-bold"
                      >
                        <td class="text-right" colspan="3">Subtotal:</td>
                        <td>{{ totalQuantity }}</td>
                        <td>{{ totalWeight / 1000 }}</td>
                        <td>{{ totalPrice | toCurrency }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </v-expansion-panel-content>
            </v-expansion-panel>
          </v-expansion-panels>

          <div
            class="table-responsive"
            v-if="!dataLoading && allData.data.length"
          >
            <table class="table table-bordered text-center">
              <thead>
                <tr class="table_head">
                  <th class="col-3">Outlet Details</th>
                  <th class="col-4">Order Details</th>
                  <th class="col-5">Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(singleData, index) in allData.data" :key="index">
                  <td
                    class="text-left"
                    :class="
                      Object.keys(singleData.invoice).length > 0
                        ? 'active_bg'
                        : ''
                    "
                  >
                    <b>Order Date:</b>
                    {{ singleData.order_date | moment("dddd, MMMM Do YYYY") }}
                    <br />
                    <b>Outlet:</b>
                    <span
                      v-if="
                        singleData.outlet_details &&
                        singleData.outlet_details.shop_name
                      "
                      >{{ singleData.outlet_details.shop_name }}</span
                    >
                    <span v-else class="error--text">N/A</span>
                    <br />
                    <b>Address:</b>
                    <span
                      v-if="
                        singleData.outlet_details &&
                        singleData.outlet_details.shop_address
                      "
                      >{{ singleData.outlet_details.shop_address }}</span
                    >
                    <span v-else class="error--text">N/A</span>
                    <br />
                    <b>CV Code:</b>
                    {{ singleData.cv_code }}
                    <br />
                    <b>Order Num:</b>
                    {{ singleData.order_number }}
                  </td>
                  <td
                    class="text-left"
                    :class="
                      Object.keys(singleData.invoice).length > 0
                        ? 'active_bg'
                        : ''
                    "
                  >
                    <b>Total Price:</b>
                    {{ singleData.total_price | toCurrency }}
                    <br />
                    <b>Payment Amount:</b>
                    <span v-if="singleData.payment_amount">{{
                      singleData.payment_amount | toCurrency
                    }}</span>
                    <span v-else class="error--text">N/A</span>
                    <br />
                    <b>Payment:</b>
                    <span v-if="singleData.payment_amount">
                      Completed
                      <v-btn
                        x-small
                        class="btn_preview_image"
                        @click="viewDoc(singleData.payment_doc)"
                      >
                        <v-icon left>mdi-eye</v-icon>view
                      </v-btn>
                    </span>
                    <span v-else class="error--text">Pending</span>
                    <br />
                    <b>Invoice:</b>
                    <span v-if="Object.keys(singleData.invoice).length > 0">
                      <span
                        v-for="(invItem, indx) in singleData.invoice"
                        :key="indx"
                      >
                        <v-chip class="m-1">
                          {{ invItem.INVOICE_NO }}
                          <small class="ml-1"
                            >( {{ invItem.SumAMOUNT | toCurrency }} )</small
                          >
                          <small class="ml-1">
                            {{ invItem.INVOICE_DATE | moment("MMMM Do") }}
                          </small>
                        </v-chip>
                      </span>
                    </span>

                    <span v-else class="error--text">N/A</span>
                  </td>

                  <td
                    :class="
                      Object.keys(singleData.invoice).length > 0
                        ? 'active_bg'
                        : ''
                    "
                  >
                    <v-btn
                      v-if="singleData.status"
                      @click="statusChange(singleData)"
                      small
                      elevation="10"
                      class="m-1 btn_active"
                      :style="
                        isPermit('order-status')
                          ? 'pointer-events: auto;'
                          : 'pointer-events: none'
                      "
                    >
                      <v-icon left>mdi-check-decagram</v-icon>Active
                    </v-btn>
                    <v-btn
                      v-else
                      @click="statusChange(singleData)"
                      small
                      elevation="10"
                      class="m-1 btn_inactive"
                      :style="
                        isPermit('order-status')
                          ? 'pointer-events: auto;'
                          : 'pointer-events: none'
                      "
                    >
                      <v-icon left>mdi-close-octagon</v-icon>Inactive
                    </v-btn>

                    <v-btn
                      v-if="
                        singleData.payment_doc == '' &&
                        isPermit('order-payment')
                      "
                      color="blue-grey"
                      small
                      class="m-1 btn_payment"
                      @click="makePayment(singleData)"
                    >
                      <v-icon left>mdi-currency-bdt</v-icon>Make Payment
                    </v-btn>

                    <v-btn
                      v-if="
                        !singleData.manager_approve &&
                        !singleData.admin_approve &&
                        isPermit('order-modify-order')
                      "
                      @click="modifyOrder(singleData)"
                      small
                      elevation="10"
                      class="m-1 btn_update"
                    >
                      <v-icon left>mdi-circle-edit-outline</v-icon>Modify
                    </v-btn>

                    <v-btn
                      v-if="isPermit('order-details-view')"
                      small
                      class="m-1 btn_details"
                      @click="getOrderDetails(singleData)"
                    >
                      <v-icon left>mdi-card-text-outline</v-icon>Details
                    </v-btn>

                    <!-- Manager Approve -->
                    <v-btn
                      v-if="!singleData.manager_approve"
                      color="green darken-1"
                      small
                      class="m-1 btn_approve1"
                      @click="managerApprove(singleData)"
                      :style="
                        isPermit('order-manager-approve')
                          ? 'pointer-events: auto;'
                          : 'pointer-events: none'
                      "
                    >
                      <v-icon left>mdi-check</v-icon>Manager Approve
                    </v-btn>

                    <v-chip
                      color="green darken-1 text-light"
                      class="m-1"
                      v-if="singleData.manager_approve"
                    >
                      (M)&nbsp;
                      <span v-if="singleData.managerby">
                        {{ singleData.managerby.name }} </span
                      >&nbsp;Approved
                      <span class="small ml-1">
                        ({{
                          singleData.manager_approve
                            | moment("D-MMM-YY, h:mm a")
                        }})
                      </span>
                    </v-chip>

                    <!-- Admin Approve -->

                    <v-btn
                      v-if="!singleData.admin_approve"
                      small
                      class="m-1 btn_approve2"
                      @click="adminApprove(singleData)"
                      order-manager-approve
                      :style="
                        isPermit('order-admin-approve') &&
                        singleData.manager_approve
                          ? 'pointer-events: auto;'
                          : 'pointer-events: none'
                      "
                    >
                      <v-icon left>mdi-check-all</v-icon>Admin Approve
                    </v-btn>

                    <v-chip
                      color="pink darken-1 text-light"
                      class="m-1"
                      v-if="singleData.admin_approve"
                    >
                      (A)&nbsp;
                      <span v-if="singleData.adminby">
                        {{ singleData.adminby.name }} </span
                      >&nbsp;Approved
                      <span class="small ml-1">
                        ({{
                          singleData.manager_approve
                            | moment("D-MMM-YY, h:mm a")
                        }})
                      </span>
                    </v-chip>
                  </td>
                </tr>
              </tbody>
            </table>

            <v-pagination
              circle
              v-model="currentPageNumber"
              :length="v_total"
              :total-visible="7"
              @input="getResults"
            ></v-pagination>
            <div>
              <span>Total Records: {{ totalValue }}</span>
            </div>
          </div>

          <div v-else>
            <div v-if="dataLoading">
              <tbl-data-loader />
            </div>
          </div>

          <data-not-available v-if="!totalValue && !dataLoading" />
        </div>
      </v-card-text>
    </v-card>

    <!-- place order dialog -->
    <order-component
      v-if="placeOrderModal"
      :key="orderKey"
      @getResults="getResults"
      @getSkuReport="getSkuReport"
    ></order-component>

    <!-- modify -->
    <modify-component
      v-if="modifyOrderModal"
      :CurrOrderSummary="currOrderSummary"
      :previousOrders="currOrders"
      :key="modifyKey"
      @getResults="getResults"
      @getSkuReport="getSkuReport"
    ></modify-component>

    <!-- manager-approve -->
    <manager-approve
      v-if="managerApproveModal"
      :CurrOrderSummary="currOrderSummary"
      :previousOrders="currOrders"
      :key="managerApproveKey"
      @getResults="getResults"
      @getSkuReport="getSkuReport"
    ></manager-approve>

    <!-- admin-approve -->
    <admin-approve
      v-if="adminApproveModal"
      :CurrOrderSummary="currOrderSummary"
      :previousOrders="currOrders"
      :key="adminApproveKey"
      @getResults="getResults"
      @getSkuReport="getSkuReport"
    ></admin-approve>

    <!-- current order details dialog -->
    <order-details
      v-if="orderDetailsDialog"
      :orderDetails="orderDetails"
      :key="orderDetailsKey"
    ></order-details>

    <!-- make payment dialog -->
    <make-payment
      v-if="makePaymentDialog && currentOrderNumber"
      :currentOrderNumber="currentOrderNumber"
      :key="makePaymentDialogKey"
      @getResults="getResults"
      @getSkuReport="getSkuReport"
    ></make-payment>

    <!-- image file -->
    <v-dialog
      v-model="docView"
      width="auto "
      :fullscreen="$vuetify.breakpoint.xsOnly"
    >
      <v-card>
        <v-card-title class="justify-content-between">
          <a :href="imageFile" class="btn btn_download" download>
            <v-icon color="white" small>mdi-paperclip</v-icon>Download
          </a>
          <v-btn class="btn_close" small icon @click="docView = false">
            <v-icon>mdi-close-circle</v-icon>
          </v-btn>
        </v-card-title>
        <v-img :src="imageFile" alt="image" contain></v-img>
      </v-card>
    </v-dialog>
  </div>
</template>



<script>
import order from "./order.vue";
import modify from "./modify.vue";
import orderDetails from "../common/order_details.vue";
import payment from "./makePayment.vue";

import managerApprove from "./approve_manager.vue";
import adminApprove from "./approve_admin.vue";

export default {
  components: {
    "order-component": order,
    "modify-component": modify,
    "order-details": orderDetails,
    "make-payment": payment,
    "manager-approve": managerApprove,
    "admin-approve": adminApprove,
  },
  data() {
    return {
      //current page url
      currentUrl: "/admin/orders",

      imagePathSm: "/images/admin/small/",
      imagePath: "/images/admin/",
      imageMaxSize: "2111775",

      // placeOrderModal
      placeOrderModal: false,
      modifyOrderModal: false,
      currentShopInfo: [],

      imagePath: "/images/users/",

      orderKey: 1,
      modifyKey: 1,

      //manager Approve
      managerApproveModal: false,
      managerApproveKey: 1,

      //admin Approve
      adminApproveModal: false,
      adminApproveKey: 1,

      selected_cv_code: "",
      currOrderSummary: "",

      currOrders: "",
      currOrderNumber: "",

      // currentOrderDetails
      orderDetails: [],
      orderDetailsDialog: false,
      orderDetailsKey: 1,
      orderDetailsLoading: false,

      // make payment
      currOrders: "",
      makePaymentDialog: false,
      makePaymentDialogKey: 1,

      // imageFile
      imageFile: "",
      docView: false,

      allTypeOption: [
        {
          text: "All",
          value: "All",
        },
        {
          text: "Manager Approved",
          value: "appm",
        },
        {
          text: "Manager & Admin Approved",
          value: "app",
        },
        {
          text: "Not Approved",
          value: "notapp",
        },
        {
          text: "Payment Completed",
          value: "pmtcom",
        },
        {
          text: "Payment Not Completed",
          value: "pmtnotcom",
        },
      ],

      //start_date: "2022-07-19",
      start_date: this.$moment(new Date()).format("YYYY-MM-DD"),

      // sku report data
      allSkuData: "",
      totalWeight: "",
      totalPrice: "",
      totalQuantity: "",
    };
  },

  methods: {
    modifyOrder(val) {
      this.currOrderSummary = val;
      this.currOrders = val.all_orders;
      this.modifyKey = this.randomKey();
      this.modifyOrderModal = true;
    },

    // managerApprove
    managerApprove(val) {
      this.currOrderSummary = val;
      this.currOrders = val.all_orders;
      this.managerApproveKey = this.randomKey();
      this.managerApproveModal = true;
    },

    // adminApprove
    adminApprove(val) {
      this.currOrderSummary = val;
      this.currOrders = val.all_orders;
      this.adminApproveKey = this.randomKey();
      this.adminApproveModal = true;
    },

    // makePayment
    makePayment(data) {
      this.makePaymentDialog = true;
      this.makePaymentDialogKey = this.randomKey();
      this.currentOrderNumber = data.order_number;
    },

    // viewDoc
    viewDoc(image) {
      this.imageFile = "/images/order/" + image;
      this.docView = true;
    },

    // getOrderDetails
    getOrderDetails(data) {
      this.orderDetailsLoading = true;
      axios
        .post(this.currentUrl + "/order_details", {
          order_number: data.order_number,
        })
        .then((resoponse) => {
          this.orderDetails = resoponse.data;
          this.orderDetailsLoading = false;
          this.orderDetailsDialog = true;
          this.orderDetailsKey = this.randomKey();
        });
    },

    //  getSkuReport
    getSkuReport() {
      return axios
        .get(
          this.currentUrl +
            "/sku?zone=" +
            this.by_zone +
            "&start_date=" +
            this.start_date
        )
        .then((response) => {
          this.allSkuData = response.data.allData;
          this.totalWeight = response.data.totalWeight;
          this.totalPrice = response.data.totalPrice;
          this.totalQuantity = response.data.totalQuantity;
        });
    },

    // exportExcel
    exportExcel() {
      this.exportLoading = true;

      axios({
        method: "get",
        url:
          this.currentUrl +
          "/export_data?search=" +
          this.search +
          "&start_date=" +
          this.start_date +
          "&by_zone=" +
          this.by_zone +
          "&by_type=" +
          this.by_type,

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
  },

  computed: {
    computedDate() {
      return this.formatDate(this.start_date);
    },
  },

  watch: {
    start_date: function () {
      this.allData = {};
      this.$Progress.start();
      this.getResults(this.currentPageNumber);
      this.getSkuReport();
      this.$Progress.finish();
    },

    by_zone: function () {
      console.log("index");
      this.$Progress.start();
      // this.getResults(this.currentPageNumber);
      this.getSkuReport();
      this.$Progress.finish();
    },
    // end_date: function () {
    //   this.allData = {};
    //   this.$Progress.start();
    //   this.getResults(this.currentPageNumber);
    //   this.$Progress.finish();
    // },
  },

  async created() {
    this.$Progress.start();
    // Fetch initial results
    await this.getResults(this.currentPageNumber);
    await this.getSkuReport();
    await this.getAllAssignedZonesAsync();
    this.$Progress.finish();
  },
};
</script>

<style scoped>
.active_bg {
  color: white;
  background-color: #009688;
}

.active_bg_2 {
  color: white;
  background-color: #595859;
}
</style>
