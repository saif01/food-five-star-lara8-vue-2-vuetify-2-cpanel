<template>
  <div>
    <!-- fullscreen -->
    <v-dialog v-model="modifyModal" fullscreen persistent>
      <v-card>
        <v-card-title>
          <v-row>
            <v-col cols="8" lg="10">
              <span>Modify Order</span>
              <span class="text-success"
                >( O.N. {{ CurrOrderSummary.order_number }} )</span
              >
            </v-col>
            <v-col cols="4" lg="2">
              <v-btn
                @click="modifyModal = false"
                small
                class="float-right btn_close"
              >
                <v-icon left dark>mdi-close-octagon</v-icon>Close
              </v-btn>
            </v-col>
          </v-row>
        </v-card-title>

        <v-card-text v-if="!loading">
          <v-row>
            <v-col cols="12" lg="4">
              <!-- <v-text-field
                v-model="selected_date"
                label="Order date"
                type="date"
              ></v-text-field> -->
              <v-menu v-model="menu" min-width="auto">
                <template v-slot:activator="{ on, attrs }">
                  <v-text-field
                    v-model="computedDate"
                    label="Order Date"
                    prepend-icon="mdi-calendar"
                    readonly
                    v-bind="attrs"
                    v-on="on"
                    dense
                  ></v-text-field>
                </template>

                <v-date-picker v-model="selected_date" no-title scrollable>
                  <v-spacer></v-spacer>
                  <v-btn text color="primary" @click="menu = false">
                    Cancel
                  </v-btn>
                </v-date-picker>
              </v-menu>
            </v-col>

            <v-col cols="12" lg="8">
              <div v-if="allOutlets">
                <v-autocomplete
                  prepend-icon="mdi-storefront-outline"
                  v-model="selected_cv_code"
                  :items="allOutletsOptions"
                  label="Outlet"
                  placeholder="Select Outlet for Confirm Order"
                  dense
                ></v-autocomplete>
              </div>
            </v-col>
            <v-col cols="6" lg="2">
              <v-autocomplete
                prepend-icon="mdi-format-list-bulleted"
                :items="category"
                item-text="name_en"
                item-value="id"
                v-model="sort_type"
                small
              ></v-autocomplete>
            </v-col>

            <v-col cols="6" lg="10">
              <v-text-field
                prepend-icon="mdi-clipboard-text-search"
                v-model="search2"
                small
                placeholder="Search ..."
              ></v-text-field>
            </v-col>
          </v-row>

          <div class="float-right">
            <v-btn
              small
              class="btn_order"
              @click="(placeOrderDialog = true), placeOrderDialogKey++"
            >
              Order
              <v-icon right>mdi-cart-outline</v-icon>
              <v-badge
                v-if="orderList != ''"
                :content="orderList.length"
              ></v-badge>
              <v-badge v-else content="0"></v-badge>
            </v-btn>
          </div>

          <div v-for="(items, index) in foodItems2" :key="index">
            <div class="title_item">{{ items[0].order_type.name_en }}</div>

            <div class="table-responsive">
              <table class="table table-bordered text-center table-striped">
                <thead class="table_head">
                  <tr>
                    <th>Product</th>
                    <th>Price (Incl. Vat)</th>
                    <th>Weight</th>
                    <th>Quantity</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody v-if="items">
                  <tr v-for="item in items" :key="item.id">
                    <td>
                      <span>{{ item.type }}</span>
                      <span v-if="item.image">
                        <img
                          :src="imagePathSm + item.image"
                          alt="Image"
                          class="img-fluid"
                          height="50"
                          width="50"
                        />
                      </span>
                    </td>
                    <td>
                      <span v-if="item.tp">{{ item.tp | toCurrency }}</span>

                      <span v-else class="error--text">N/A</span>

                      <span v-if="item.quantity"
                        >({{ item.quantity }} Pcs)</span
                      >
                    </td>
                    <td>
                      <span v-if="item.weight && item.weight_type"
                        >{{ item.weight }} ({{ item.weight_type }})</span
                      >
                      <span v-else class="error--text">N/A</span>
                    </td>
                    <td>
                      <div
                        class="d-flex align-items-center justify-content-center"
                      >
                        <v-btn v-if="item.quantity_order == 0" icon>
                          <v-icon>mdi-minus</v-icon>
                        </v-btn>
                        <v-btn
                          v-else
                          icon
                          @click="item.quantity_order--, checkQuantity(item)"
                        >
                          <v-icon>mdi-minus</v-icon>
                        </v-btn>

                        <input
                          type="number"
                          class="text-center"
                          v-model="item.quantity_order"
                          min="1"
                          @input="checkQuantity(item)"
                          @keypress="numbersOnly($event)"
                          style="outline: none; max-width: 50px"
                        />

                        <v-btn
                          icon
                          @click="item.quantity_order++, checkQuantity(item)"
                        >
                          <v-icon>mdi-plus</v-icon>
                        </v-btn>
                      </div>
                    </td>
                    <td>
                      <v-btn
                        v-if="findId(item)"
                        @click="addToOrder(item)"
                        class="btn_delete"
                        small
                      >
                        <v-icon left>mdi-cart-remove</v-icon>Remove
                      </v-btn>

                      <v-btn
                        v-else
                        @click="addToOrder(item)"
                        class="btn_add"
                        small
                      >
                        <v-icon left>mdi-cart-plus</v-icon>Add
                      </v-btn>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </v-card-text>

        <!-- order loading -->
        <tbl-data-loader v-else />
      </v-card>
    </v-dialog>

    <!-- placeOrderDialog -->
    <confirm-order
      :order_list="orderList"
      :date="selected_date"
      :cvCode="selected_cv_code"
      from="modify"
      :ordernumber="CurrOrderSummary.order_number"
      v-if="placeOrderDialog"
      :key="placeOrderDialogKey"
      @getTriggered="getTriggered"
    ></confirm-order>

    <!-- snackbar notification -->
    <v-snackbar v-model="snackbar" timeout="2000">
      {{ snackbarText }}
      <template v-slot:action="{ attrs }">
        <v-btn class="btn_close" text v-bind="attrs" @click="snackbar = false"
          >Close</v-btn
        >
      </template>
    </v-snackbar>
  </div>
</template>

<script>
import allOrderMethods from "./js/order_methods";
import confirm_order_dialog from "./confirm_order_dialog.vue";
export default {
  components: {
    "confirm-order": confirm_order_dialog,
  },
  props: ["CurrOrderSummary"],
  data() {
    return {
      modifyModal: true,
      numberRule: [(val) => val > 0 || "Please enter a positive number"],
      // foodItem
      foodItems: "",

      // category
      category: [],
      // sort_type
      sort_type: "All",
      search2: "",

      currentUrl: "/admin/orders",
      orderList: [],
      placeOrderDialog: false,
      placeOrderDialogKey: 1,

      // snackbar
      snackbar: false,
      snackbarText: "",

      // loading
      loading: false,
      orderLoading: false,
      // modifyLoading: false,

      // previousOrder
      previousOrder: null,

      // selected shops cvcode
      selected_cv_code: this.CurrOrderSummary.cv_code,
      selected_date: this.CurrOrderSummary.order_date,

      productQuantity: "",
      imagePathSm: "/images/order/small/",
      imagePath: "/images/order/",
    };
  },

  methods: {
    // allOrderMethods
    ...allOrderMethods,

    getTriggered() {
      this.$emit("getResults");
      this.$emit("getSkuReport");
      this.modifyModal = false;
    },
  },

  computed: {
    computedDate() {
      return this.formatDate(this.selected_date);
    },
  },

  watch: {
    // sort by food types (spicy, regular etc...)
    sort_type: function () {
      this.$Progress.start();
      this.getFoodItemWithModifyOrder();
      this.$Progress.finish();
    },

    // search for food order
    search2: function () {
      this.$Progress.start();
      this.getFoodItemWithModifyOrder();
      this.$Progress.finish();
    },

    modifyModal: function (newValue, old) {
      if (!newValue) {
        this.orderList = [];
        //this.$store.commit("setOrder", []);
      }
    },
  },

  async created() {
    await this.getFoodItemWithModifyOrder();
    await this.getOrderProductTypeAllAsync();
    await this.AllOutletsAsync();
  },
};
</script>

<style scoped>
.title_item {
  font-size: 19px;
  font-weight: 500;
  color: black;
  text-transform: uppercase;
  letter-spacing: 4px;
  margin-top: 10px;
  border-bottom: 2px solid #c50000;
  border-top: 2px solid #c50000;
  display: inline-block;
  margin: 5px 0px;
}

.input__field {
  max-width: 18%;
}

@media screen and (max-width: 450px) {
  .input__field {
    max-width: 100% !important;
  }
}
</style>
