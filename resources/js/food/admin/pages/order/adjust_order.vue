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
      <span class="font-weight-bold">&nbsp;Adjust</span>
    </div>
    <v-card elevation="0">
      <v-card-title>Adjust Order List</v-card-title>
      <v-card-text>
        <v-row>
          <v-col cols="12" lg="4" v-if="allZoneShort">
            <!-- zone -->
            <v-autocomplete
              :items="allAssignZones"
              item-text="name"
              v-model="selectZoneItemObj"
              label="Zone"
              prepend-icon="mdi-map-marker-outline"
              placeholder="Select Zone"
              return-object
            ></v-autocomplete>
          </v-col>
          <v-col cols="12" lg="4">
            <v-autocomplete
              v-model="product_name"
              label="Product"
              placeholder="Choose Product List"
              prepend-icon="mdi-food-drumstick-outline"
              :items="saleProductsList"
              item-text="text"
              item-value="value"
            ></v-autocomplete>
          </v-col>
          <v-col cols="12" lg="4">
            <v-menu v-model="menu" min-width="auto">
              <template v-slot:activator="{ on, attrs }">
                <v-text-field
                  v-model="computedDate"
                  label="Date"
                  prepend-icon="mdi-calendar"
                  readonly
                  v-bind="attrs"
                  v-on="on"
                ></v-text-field>
              </template>

              <v-date-picker v-model="date" no-title scrollable>
                <v-spacer></v-spacer>
                <v-btn text color="primary" @click="menu = false">Cancel</v-btn>
              </v-date-picker>
            </v-menu>
          </v-col>
        </v-row>

        <div v-if="allOrder.length && !dataLoading">
          <div class="table-responsive">
            <table class="table table-bordered text-center">
              <thead class="table_head">
                <tr>
                  <th>Product</th>
                  <th>Order Number</th>
                  <th>CV Code</th>
                  <th>Outlet Name</th>
                  <th class="text-right">Price</th>
                  <th class="text-right">Weight (kg)</th>
                  <th>Quantity (Set)</th>
                  <!-- <th>Action</th> -->
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) in allOrder" :key="index">
                  <td>
                    <span>{{ item.type }}</span>
                  </td>
                  <td>
                    <span>{{ item.order_number }}</span>
                  </td>
                  <td>
                    <span>{{ item.cv_code }}</span>
                  </td>
                  <td>
                    <span>{{ item.outlet_name }}</span>
                  </td>

                  <td class="text-right">
                    <span v-if="item.tp">{{
                      (item.tp * item.quantity_order) | toCurrency
                    }}</span>

                    <span v-else class="error--text">N/A</span>
                  </td>
                  <td class="text-right">
                    <span v-if="item.weight">{{
                      (item.weight * item.quantity_order).toFixed(2)
                    }}</span>
                    <span v-else class="error--text">N/A</span>
                  </td>
                  <td>
                    <div
                      class="d-flex align-items-center justify-content-center"
                    >
                      <v-btn v-if="item.quantity_order == 1" icon>
                        <v-icon>mdi-minus</v-icon>
                      </v-btn>
                      <v-btn
                        v-else
                        icon
                        @click="
                          item.quantity_order--, ModifyAdjustQuantityOrder(item)
                        "
                      >
                        <v-icon>mdi-minus</v-icon>
                      </v-btn>
                      <span class="mx-3">{{ item.quantity_order }}</span>
                      <v-btn
                        icon
                        @click="
                          item.quantity_order++, ModifyAdjustQuantityOrder(item)
                        "
                      >
                        <v-icon>mdi-plus</v-icon>
                      </v-btn>
                    </div>
                  </td>
                  <!-- <td>
                    <v-btn v-if="findId(item)" class="btn_delete" small>
                      <v-icon left>mdi-cart-remove</v-icon>Remove
                    </v-btn>

                    <v-btn v-else class="btn_add" small>
                      <v-icon left>mdi-cart-plus</v-icon>Add
                    </v-btn>
                  </td>-->
                </tr>
                <tr class="table_bottom font-weight-bold text-right">
                  <td colspan="4">Subtotal:</td>
                  <td>{{ subTotal() | toCurrency }}</td>
                  <td>{{ subWeight() }}</td>
                  <td class="text-center">{{ subQuantity() }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <v-btn
            v-if="orderList.length"
            @click="modifySpecificOrder()"
            block
            class="btn_order"
          >
            <v-icon left>mdi-checkbox-marked-circle-plus-outline</v-icon>Adjust
            Order
          </v-btn>
        </div>

        <tbl-data-loader v-if="dataLoading" />

        <div v-else>
          <data-not-available v-if="!allOrder.length && !dataLoading" />
        </div>
      </v-card-text>
    </v-card>
  </div>
</template>

<script>
export default {
  data() {
    return {
      selectZoneItemObj: { id: "All", name: "All" },
      // saleProductsList
      saleProductsList: [],
      date: this.$moment().format("YYYY-MM-DD"),

      product_name: "",

      allOrder: "",

      placeOrderDialog: false,

      orderList: [],
      routeCounter: 1,
      currentUrl: "/admin/orders",
    };
  },

  methods: {
    getSaleProducts() {
      return axios.get("/admin/product/sales_product").then((response) => {
        if (response.data) {
          response.data.forEach((element) => {
            this.saleProductsList.push({
              value: element.id,
              text: element.type + " -- " + element.product_code,
            });
          });
          this.product_name = this.saleProductsList[0].value;

          if (this.routeCounter == 1) {
            if (this.$route.params.selectedDate) {
              this.date = this.$route.params.selectedDate;
            }
            if (this.$route.params.product_id) {
              this.product_name = this.$route.params.product_id;
            }

            this.routeCounter = 0;
          }
        }
      });
    },

    getSpecificOrder() {
      this.selectZoneItem = this.selectZoneItemObj.id;
      this.dataLoading = true;
      axios
        .get(
          this.currentUrl +
            "/adjustOrder/index?product=" +
            this.product_name +
            "&order_date=" +
            this.date +
            "&zone=" +
            this.selectZoneItem
        )
        .then((response) => {
          this.allOrder = response.data;
          this.orderList = response.data;

          this.dataLoading = false;
        });
    },

    modifySpecificOrder() {
      axios
        .post(this.currentUrl + "/adjustOrder/modify", {
          items: this.orderList,
          order_date: this.date,
        })
        .then((response) => {
          Swal.fire(
            response.data.data,
            response.data.msg,
            response.data.status
          );

          this.placeOrderDialog = false;
          this.getSpecificOrder();
        });
    },

    // ModifyQuantityOrder
    ModifyAdjustQuantityOrder(item) {
      const orderListItemMatched = this.orderList.filter(
        (obj) => obj.order_number == item.order_number
      );
      //console.log('orderListItemMatched', count,  orderListItemMatched[0].quantity, orderListItemMatched.length)

      // check double item
      if (orderListItemMatched.length > 0) {
        // remove double item
        orderListItemMatched[0].quantity_order = item.quantity_order;

        // this.snackbar = true;
        // this.snackbarText = "Deleted Successfully";
      } else {
        this.orderList.push(item);
        // this.snackbar = true;
        // this.snackbarText = "Added Successfully";
      }
    },
  },

  computed: {
    computedDate() {
      return this.formatDate(this.date);
    },
  },

  watch: {
    product_name: function () {
      this.getSpecificOrder();
    },

    date: function () {
      this.getSpecificOrder();
    },

    selectZoneItemObj: function () {
      this.getSpecificOrder();
    },
  },

  async created() {
    this.$Progress.start();
    await this.getAllAssignedZonesAsync();
    await this.getSaleProducts();
    this.$Progress.finish();
  },
};
</script>

<style>
</style>