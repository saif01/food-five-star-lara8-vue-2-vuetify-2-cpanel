<template>
  <div>
    <v-dialog v-model="placeOrderDialog" max-width="800">
      <v-card>
        <v-card-title>
          <v-row>
            <v-col cols="10">
              <div>Confirm Order</div>
            </v-col>
            <v-col cols="2">
              <v-btn
                @click="placeOrderDialog = false"
                small
                class="float-right btn_close"
              >
                <v-icon left dark>mdi-close-octagon</v-icon>Close
              </v-btn>
            </v-col>
          </v-row>
        </v-card-title>
        <v-card-text class="order__details" v-if="!orderLoading">
          <div class="table-responsive">
            <table class="table table-bordered text-right">
              <colgroup>
                <col span="4" />
                <col class="table_right" />
              </colgroup>
              <thead>
                <tr class="table_head">
                  <th class="text-left">Product</th>
                  <th>Price (Incl. Vat)</th>
                  <th>Quantity (Set)</th>
                  <th>Weight (kg)</th>
                  <th class="text-center">Total</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="item in orderList" :key="item.id">
                  <td class="text-left">{{ item.type }}</td>
                  <td>{{ item.tp | toCurrency }}</td>
                  <td>{{ item.quantity_order }}</td>
                  <td>
                    <span v-if="item.weight && item.weight_type"
                      >{{ item.weight }} ({{ item.weight_type }})</span
                    >
                    <span v-else class="error--text">N/A</span>
                  </td>
                  <td class="text-white">
                    {{ (item.quantity_order * item.tp) | toCurrency }}
                  </td>
                </tr>
                <tr class="table_bottom">
                  <td class="font-weight-bold" colspan="2">Subtotal:</td>
                  <td>{{ subQuantity() }}</td>
                  <td>{{ subWeight() }}</td>
                  <td class="text-white">{{ subTotal() | toCurrency }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <v-btn
            v-if="orderList.length"
            @click="from ? modifyOrder() : placeOrder()"
            block
            small
            class="btn_order"
          >
            <v-icon left>mdi-checkbox-marked-circle-plus-outline</v-icon>Place
            Order
          </v-btn>
        </v-card-text>
        <!-- order loading -->
        <v-card-text v-else>
          <tbl-data-loader />
        </v-card-text>
      </v-card>
    </v-dialog>
  </div>
</template>


<script>
import orderJs from "./js/order_methods";
export default {
  props: ["order_list", "date", "from", "ordernumber", "cvCode"],
  data() {
    return {
      orderLoading: false,
      placeOrderDialog: true,
      currentUrl: "/admin/orders",
      orderList: [],
      selected_date: "",
      order_number: "",
      selected_cv_code: "",
    };
  },

  methods: {
    // all order related methods
    ...orderJs,
  },

  mounted() {
    this.orderList = this.order_list;
    this.selected_date = this.date;
    this.order_number = this.ordernumber;
    this.selected_cv_code = this.cvCode;
  },
};
</script>