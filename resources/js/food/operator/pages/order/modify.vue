<template>
  <div>
    <v-dialog fullscreen v-model="modifyModal" persistent>
      <v-card elevation="0">
        <v-card-title>
          <v-row>
            <v-col cols="10">
              <div>{{ $t("nav__modifyOrder") }}</div>
            </v-col>
            <v-col cols="2">
              <v-btn
                @click="modifyModal = false"
                small
                class="float-right btn_close"
              >
                <v-icon left dark>mdi-close-octagon</v-icon>
                {{ $t("close__") }}
              </v-btn>
            </v-col>
          </v-row>
        </v-card-title>
        <v-card-text>
          <v-row>
            <v-col
              cols="5"
              lg="2"
              v-if="currentLang == 'bangla'"
              :key="foodTypeKey"
            >
              <v-autocomplete
                dense
                :items="category"
                item-text="name_bn"
                item-value="id"
                v-model="sort_type"
              ></v-autocomplete>
            </v-col>
            <v-col cols="5" lg="2" v-else>
              <v-autocomplete
                dense
                :items="category"
                item-text="name_en"
                item-value="id"
                v-model="sort_type"
              ></v-autocomplete>
            </v-col>

            <v-col cols="7" lg="6">
              <v-text-field
                prepend-icon="mdi-clipboard-text-search"
                v-model="search2"
                dense
                :placeholder="$t('search__by__any__data')"
              ></v-text-field>
            </v-col>

            <v-col cols="12" lg="4">
              <!-- <v-text-field
                v-model="selected_date"
                dense
                label="Order date"
                type="date"
              ></v-text-field> -->
              <v-menu v-model="menu" min-width="auto">
                <template v-slot:activator="{ on, attrs }">
                  <v-text-field
                    v-model="computedDate"
                    :label="$t('order__date')"
                    prepend-icon="mdi-calendar"
                    readonly
                    v-bind="attrs"
                    v-on="on"
                    dense
                  ></v-text-field>
                </template>

                <v-date-picker v-model="selected_date" no-title scrollable>
                </v-date-picker>
              </v-menu>
            </v-col>
          </v-row>
          <div class="float-right">
            <v-btn
              small
              class="btn_order"
              @click="(placeOrderDialog = true), placeOrderDialogKey++"
            >
              {{ $t("order__list") }}
              <v-icon right>mdi-cart-outline</v-icon>
              <v-badge
                v-if="orderList != ''"
                :content="orderList.length"
              ></v-badge>
              <v-badge v-else content="0"></v-badge>
            </v-btn>
          </div>
          <div v-for="items in foodItems2" :key="items.id">
            <div class="title_item">
              <span v-if="currentLang == 'bangla'">{{
                items[0].order_type.name_bn
              }}</span>
              <span v-else>{{ items[0].order_type.name_en }}</span>
            </div>

            <div class="table-responsive">
              <table class="table table-bordered text-center table-striped">
                <thead>
                  <tr class="table_head">
                    <th>{{ $t("name__product") }}</th>
                    <th>{{ $t("price__") }} (Incl. Vat)</th>
                    <th>{{ $t("weight__") }}</th>
                    <th>{{ $t("quantity__") }}</th>
                    <th>{{ $t("action__") }}</th>
                  </tr>
                </thead>
                <tbody v-if="items">
                  <tr v-for="(item, index) in items" :key="index">
                    <td>
                      <span v-if="currentLang == 'bangla'">{{
                        item.type_bn
                      }}</span>
                      <span v-else>{{ item.type }}</span>
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
                      <span v-if="item.tp">
                        <!-- <span v-if="currentLang == 'bangla'">
                          <span v-if="item">
                            {{ item.tp | toCurrencyBL }} ({{
                              englishTobangla(item.quantity)
                            }}
                            পিস)
                          </span>
                        </span> -->
                        <span>
                          {{ item.tp | toCurrency }} ({{ item.quantity }}
                          Pcs)
                        </span>
                      </span>
                      <span v-else class="error--text">N/A</span>
                    </td>

                    <td>
                      <span v-if="item.weight">
                        <!-- <span v-if="currentLang == 'bangla'">
                          {{ englishTobangla(item.weight) }}
                          <span v-if="item.weight_type == 'kg'">কেজি</span>
                          <span v-else>গ্রাম</span>
                        </span> -->
                        <span>{{ item.weight }} {{ item.weight_type }}</span>
                      </span>
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
                          style="outline: none; max-width: 50px"
                          min="1"
                          @input="checkQuantity(item)"
                          @keypress="numbersOnly($event)"
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
                        <v-icon left>mdi-cart-remove</v-icon>
                        {{ $t("delete__order") }}
                      </v-btn>

                      <v-btn
                        v-else
                        @click="addToOrder(item)"
                        class="btn_add"
                        small
                      >
                        <v-icon left>mdi-cart-plus</v-icon>
                        {{ $t("add__to__list") }}
                      </v-btn>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="text-center" v-if="loading">
              <div class="dot-flashing d-inline-block"></div>
            </div>
          </div>
        </v-card-text>
      </v-card>
    </v-dialog>

    <!-- Conform Order -->
    <confirm-order
      :order_list="orderList"
      :date="selected_date"
      :ordernumber="CurrOrderSummary.order_number"
      from="modify"
      v-if="placeOrderDialog"
      :key="placeOrderDialogKey"
      @getTriggered="getTriggered"
    ></confirm-order>

    <!-- snackbar notification -->
    <v-snackbar v-model="snackbar" timeout="2000"
      >{{ snackbarText }}
      <template v-slot:action="{ attrs }">
        <v-btn
          class="btn_close"
          text
          v-bind="attrs"
          @click="snackbar = false"
          icon
        >
          <v-icon>mdi-close-circle</v-icon>
        </v-btn>
      </template></v-snackbar
    >
  </div>
</template>

<script>
import orderJs from "./js/order";
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

      sort_type: "All",
      search2: "",

      currentUrl: "/order",
      orderList: [],
      placeOrderDialog: false,
      placeOrderDialogKey: 1,

      // snackbar
      snackbar: false,
      snackbarText: "",

      // loading
      loading: false,
      orderLoading: false,

      // previousOrder
      previousOrder: null,

      // selected shops cvcode
      selected_date: this.CurrOrderSummary.order_date,

      productQuantity: "",

      // foodTypeKey
      foodTypeKey: 1,

      imagePathSm: "/images/order/small/",
      imagePath: "/images/order/",
    };
  },

  methods: {
    // all order related methods
    ...orderJs,
    // triggered to index page get results
    getTriggered() {
      this.$emit("getResults");
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
      }
    },

    currentLang: function (v) {
      this.foodTypeKey = this.randomKey();
    },
  },

  async created() {
    await this.getFoodItemWithModifyOrder();
    await this.getProductOrderTypeAll();
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

/* table */
.order__details table tr td,
table tr th {
  padding: 0.5rem;
}
</style>