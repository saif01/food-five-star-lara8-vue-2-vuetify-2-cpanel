<template>
  <div>
    <v-card class="mb-10" elevation="0">
      <v-card-title class="justify-content-between align-items-center">
        <div>{{ $t("order__list") }}</div>
        <div>
          <v-btn class="btn_order" small link route :to="{ name: 'NewOrder' }" elevation="20">
            <v-icon left>mdi-plus-thick</v-icon>
            {{ $t("place__new__order") }}
          </v-btn>
        </div>
      </v-card-title>

      <v-card-text>
        <v-row class="mt-2">
          <v-col cols="12" md="6" lg="2">
            <v-select
              prepend-icon="mdi-database-eye"
              :items="tblItemNumberShow"
              dense
              v-model="paginate"
              :label="$t('show__')"
            ></v-select>
          </v-col>

          <v-col cols="12" md="6" lg="3">
            <v-menu v-model="menu" min-width="auto">
              <template v-slot:activator="{ on, attrs }">
                <v-text-field
                  v-model="computedStartDate"
                  :label="$t('start__')"
                  prepend-icon="mdi-calendar"
                  readonly
                  v-bind="attrs"
                  v-on="on"
                  dense
                ></v-text-field>
              </template>

              <v-date-picker v-model="start_date" no-title scrollable></v-date-picker>
            </v-menu>
          </v-col>
          <v-col cols="12" md="6" lg="3">
            <v-menu v-model="menu2" min-width="auto">
              <template v-slot:activator="{ on, attrs }">
                <v-text-field
                  v-model="computedEndDate"
                  :label="$t('end__')"
                  prepend-icon="mdi-calendar"
                  readonly
                  v-bind="attrs"
                  v-on="on"
                  dense
                ></v-text-field>
              </template>

              <v-date-picker v-model="end_date" no-title scrollable />
            </v-menu>
          </v-col>

          <v-col cols="12" md="6" lg="4">
            <v-text-field
              v-model="search"
              prepend-icon="mdi-clipboard-text-search"
              :label="$t('search__')"
              dense
              :placeholder="$t('search__by__any__data')"
            ></v-text-field>
          </v-col>
        </v-row>
        <div v-if="!dataLoading && allData.data.length">
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr class="table_head">
                  <th>{{ $t("order__place__date") }}</th>
                  <th>{{ $t("order__date") }}</th>
                  <th>{{ $t("order__number") }}</th>
                  <th class="text-center">{{ $t("status__") }}</th>
                  <th class="text-center">{{ $t("price__") }}</th>
                  <th class="text-center">{{ $t("payment__") }}</th>
                  <th>{{ $t("action__") }}</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="singleData in allData.data" :key="singleData.id">
                  <td
                    :class="
                      Object.keys(singleData.invoice).length > 0
                        ? 'active_bg'
                        : ''
                    "
                  >
                    <!-- <span v-if="currentLang == 'bangla'">
                      {{
                        calendarToBangla(singleData.created_at, "dddd, MMMM DD")
                      }}
                    </span>-->
                    <span>
                      {{
                      singleData.created_at | moment("dddd, MMMM DD")
                      }}
                    </span>
                  </td>

                  <td
                    :class="
                      Object.keys(singleData.invoice).length > 0
                        ? 'active_bg'
                        : ''
                    "
                  >
                    <!-- <span v-if="currentLang == 'bangla'">
                      {{
                        calendarToBangla(singleData.order_date, "dddd, MMMM DD")
                      }}
                    </span>-->
                    <span>
                      {{
                      singleData.order_date | moment("dddd, MMMM DD")
                      }}
                    </span>
                  </td>

                  <td
                    :class="
                      Object.keys(singleData.invoice).length > 0
                        ? 'active_bg'
                        : ''
                    "
                  >
                    <!-- <span v-if="currentLang == 'bangla'">{{
                      englishTobangla(singleData.order_number)
                    }}</span>-->
                    <span>{{ singleData.order_number }}</span>
                  </td>

                  <td
                    class="text-center"
                    :class="
                      Object.keys(singleData.invoice).length > 0
                        ? 'active_bg'
                        : ''
                    "
                  >
                    <v-chip v-if="singleData.admin_approve">
                      {{ $t("approve__") }} ||
                      <small class="ml-1">
                        <!-- <span v-if="currentLang == 'bangla'">
                          {{
                            calendarToBangla(
                              singleData.admin_approve,
                              "dddd, MMMM DD"
                            )
                          }}
                        </span>-->
                        <span>
                          {{
                          singleData.admin_approve | moment("dddd, MMMM DD")
                          }}
                        </span>
                      </small>
                    </v-chip>
                    <div v-else class="bg-warning rounded small">{{ $t("waiting__for__approve") }}</div>
                  </td>

                  <td
                    class="text-center"
                    :class="
                      Object.keys(singleData.invoice).length > 0
                        ? 'active_bg'
                        : ''
                    "
                  >
                    <!-- <span v-if="currentLang == 'bangla'">
                      {{ singleData.total_price | toCurrencyBL }}
                    </span>-->
                    <span>{{ singleData.total_price | toCurrency }}</span>
                  </td>

                  <td
                    class="text-center"
                    :class="
                      Object.keys(singleData.invoice).length > 0
                        ? 'active_bg'
                        : ''
                    "
                  >
                    <span v-if="singleData.payment_amount" class="text-success">
                      <v-chip>
                        {{ $t("payment__completed") }}
                        <v-btn
                          x-small
                          class="btn_preview_image ml-1"
                          @click="viewDoc(singleData.payment_doc)"
                        >
                          <v-icon left>mdi-eye</v-icon>
                          {{ $t("view__document") }}
                        </v-btn>
                      </v-chip>
                    </span>
                    <span v-else class="error--text">N/A</span>
                  </td>

                  <td
                    class="text-center"
                    :class="
                      Object.keys(singleData.invoice).length > 0
                        ? 'active_bg'
                        : ''
                    "
                  >
                    <div v-if="Object.keys(singleData.invoice).length > 0">
                      <b>{{ $t("invoice__") }}:</b>

                      <span v-for="(invItem, indx) in singleData.invoice" :key="indx">
                        <v-chip class="m-1">
                          <!-- <span v-if="currentLang == 'bangla'">
                            {{ englishTobangla(invItem.INVOICE_NO) }}
                          </span>-->
                          <span>{{ invItem.INVOICE_NO }}</span>

                          <small class="ml-1">
                            (
                            <!-- <span v-if="currentLang == 'bangla'">
                              {{ invItem.AMOUNT | toCurrencyBL }}
                            </span>-->
                            <span>{{ invItem.SumAMOUNT | toCurrency }}</span>
                            )
                          </small>
                          <small class="ml-1">
                            <!-- <span v-if="currentLang == 'bangla'">
                              {{
                                calendarToBangla(
                                  invItem.INVOICE_DATE,
                                  "MMMM DD"
                                )
                              }}
                            </span>-->
                            <span>
                              {{
                              invItem.INVOICE_DATE | moment("MMMM DD")
                              }}
                            </span>
                          </small>
                        </v-chip>
                      </span>
                    </div>
                    <div v-else>
                      <v-btn
                        v-if="
                          !singleData.manager_approve &&
                          !singleData.admin_approve
                        "
                        @click="
                          modifyOrder(singleData, singleData.order_number)
                        "
                        small
                        elevation="10"
                        class="ma-1 btn_edit"
                      >
                        <v-icon left>mdi-circle-edit-outline</v-icon>
                        {{ $t("modify__order") }}
                      </v-btn>
                      <v-btn
                        v-if="singleData.payment_doc == ''"
                        color="blue-grey"
                        small
                        class="ma-1 btn_makepayment"
                        @click="makePayment(singleData)"
                      >
                        <v-icon left>mdi-currency-bdt</v-icon>
                        {{ $t("make__payment") }}
                      </v-btn>
                    </div>

                    <v-btn small class="ma-1 btn_details" @click="getOrderDetails(singleData)">
                      <v-icon left>mdi-card-text-outline</v-icon>
                      {{ $t("order__details") }}
                    </v-btn>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div>
            <span v-if="currentLang == 'bangla'">
              <span>সর্বমোট রেকর্ড: {{ totalValue }}</span>
            </span>
            <span v-else>Total Records: {{ totalValue }}</span>
          </div>
          <v-pagination
            circle
            v-model="currentPageNumber"
            :length="v_total"
            :total-visible="7"
            @input="getResults"
          ></v-pagination>
        </div>

        <div v-else>
          <tbl-data-loader v-if="dataLoading" />
        </div>
        <data-not-available v-if="!totalValue && !dataLoading" />
      </v-card-text>
    </v-card>

    <!-- modify -->
    <modify-component
      v-if="modifyOrderModal"
      :CurrOrderSummary="currOrderSummary"
      :previousOrders="currOrders"
      :key="modifyKey"
      @getResults="getResults"
    ></modify-component>

    <!-- current order details dialog -->
    <v-dialog v-model="currentOrderDetailsDialog" max-width="800">
      <v-card>
        <v-card-title>{{ $t("order__details") }}</v-card-title>
        <v-card-text>
          <div class="table-responsive">
            <table class="table table-bordered text-right">
              <colgroup>
                <col span="4" />
                <col class="table_right" />
              </colgroup>
              <thead>
                <tr class="table_head">
                  <th class="text-left">{{ $t("name__product") }}</th>
                  <th>{{ $t("price__") }}</th>
                  <th>{{ $t("quantity__") }} ({{ $t("set__") }})</th>
                  <th>{{ $t("weight__") }} ({{ $t("kg__") }})</th>
                  <th>{{ $t("total__") }} ({{ $t("bdt__") }})</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="item in currentOrderDetails" :key="item.id">
                  <td class="text-left">
                    <span v-if="item.product_details">
                      <span v-if="currentLang == 'bangla'">
                        {{
                        item.product_details.type_bn
                        }}
                      </span>
                      <span v-else>{{ item.product_details.type }}</span>
                    </span>
                  </td>
                  <td>
                    <!-- <span v-if="currentLang == 'bangla'">
                      {{ item.price | toCurrencyBL }}
                    </span>-->
                    <span>{{ item.price | toCurrency }}</span>
                  </td>
                  <td>
                    <!-- <span v-if="currentLang == 'bangla'">
                      {{ englishTobangla(item.quantity) }}
                    </span>-->
                    <span>{{ item.quantity }}</span>
                  </td>

                  <td>
                    <span
                      v-if="
                        item.product_details.weight &&
                        item.product_details.weight_type
                      "
                    >
                      <!-- <span v-if="currentLang == 'bangla'">
                        {{ englishTobangla(item.product_details.weight) }}
                        <span v-if="item.product_details.weight == 'kg'">
                          (কেজি)
                        </span>
                        <span v-else> (গ্রাম) </span>
                      </span>-->
                      <span>
                        {{ item.product_details.weight }} ({{
                        item.product_details.weight_type
                        }})
                      </span>
                    </span>
                    <span v-else class="error--text">N/A</span>
                  </td>

                  <td class="text-white">
                    <span>
                      <!-- <span v-if="currentLang == 'bangla'">
                        {{ (item.quantity * item.price) | toCurrencyBL }}
                      </span>-->
                      <span>
                        {{
                        (item.quantity * item.price) | toCurrency
                        }}
                      </span>
                    </span>
                  </td>
                </tr>
                <tr class="table_bottom">
                  <td class="font-weight-bold" colspan="2">{{ $t("subtotal__") }}:</td>
                  <td>
                    <!-- <span v-if="currentLang == 'bangla'">{{
                      englishTobangla(subQuantity())
                    }}</span>-->
                    <span>{{ subQuantity() }}</span>
                  </td>
                  <td>
                    <!-- <span v-if="currentLang == 'bangla'"
                      >{{ englishTobangla(subWeight()) }}
                    </span>-->
                    <span>{{ subWeight() }}</span>
                  </td>
                  <td>
                    <!-- <span v-if="currentLang == 'bangla'">{{
                      subTotal() | toCurrencyBL
                    }}</span>-->
                    <span>{{ subTotal() | toCurrency }}</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </v-card-text>
      </v-card>
    </v-dialog>

    <!-- order details loading -->
    <v-overlay :value="orderDetailsLoading">
      <v-progress-circular size="64"></v-progress-circular>
    </v-overlay>

    <!-- make payment dialog -->
    <make-payment
      v-if="makePaymentDialog && currentOrderNumber"
      :currentOrderNumber="currentOrderNumber"
      :key="makePaymentDialogKey"
      @getResults="getResults"
    ></make-payment>

    <!-- view document -->
    <v-dialog v-model="docView" width="auto " :fullscreen="$vuetify.breakpoint.xsOnly">
      <v-card>
        <v-card-title class="justify-content-between">
          <a :href="imageFile" class="btn btn_download m-1" download>
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
import modify from "./modify.vue";
import payment from "./makePayment.vue";

export default {
  components: {
    "modify-component": modify,
    "make-payment": payment
  },
  data() {
    return {
      //current page url
      currentUrl: "/order",

      allData: "",

      // modifyOrderModal
      modifyOrderModal: false,
      currentShopInfo: "",
      currentOrderNumber: "",

      modifyKey: 1,

      // currentOrderDetails
      currentOrderDetails: [],

      // currentOrderDetailsDialog
      currentOrderDetailsDialog: false,

      // orderDetailsLoading
      orderDetailsLoading: false,

      currOrders: "",
      makePaymentDialog: false,
      makePaymentDialogKey: 1,

      // imageFile
      imageFile: "",
      docView: false,

      //start_date: "2022-07-01",
      start_date: this.$moment().format("YYYY-MM-DD"),
      end_date: this.$moment().format("YYYY-MM-DD")
    };
  },

  methods: {
    modifyOrder(val, order_number) {
      this.currOrderSummary = val;
      this.currOrders = val.all_orders;
      this.modifyKey = this.randomKey();
      this.modifyOrderModal = true;
    },

    // getOrderDetails
    getOrderDetails(data) {
      this.orderDetailsLoading = true;
      axios
        .post(this.currentUrl + "/order_details", {
          order_number: data.order_number
        })
        .then(resoponse => {
          this.currentOrderDetails = resoponse.data;
          this.orderDetailsLoading = false;
          this.currentOrderDetailsDialog = true;
        })
        .catch(error => {
          //Auth Check
          this.$CHECKAUTH.CheckByCode(error.response.status);
          console.log("error", error.response.status, error);
        });
    },

    // subtotal
    subTotal() {
      const price = [];
      this.currentOrderDetails.forEach(element => {
        price.push(element.quantity * element.price);
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
      this.currentOrderDetails.forEach(element => {
        if (element.product_details.weight_type == "kg") {
          weight.push(
            element.quantity * (element.product_details.weight * 1000)
          );
        } else {
          weight.push(element.quantity * element.product_details.weight);
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
      this.currentOrderDetails.forEach(element => {
        quantity.push(element.quantity);
      });

      const initialValue = 0;
      const sumWithInitial = quantity.reduce(
        (previousValue, currentValue) => previousValue + currentValue,
        initialValue
      );
      return sumWithInitial;
    },

    makePayment(data) {
      this.makePaymentDialog = true;
      this.makePaymentDialogKey = this.randomKey();
      this.currentOrderNumber = data.order_number;
    },

    viewDoc(image) {
      this.imageFile = "/images/order/" + image;
      this.docView = true;
    }
  },

  computed: {
    computedStartDate() {
      return this.formatDate(this.start_date);
    },

    computedEndDate() {
      return this.formatDate(this.end_date);
    }
  },

  watch: {
    start_date: function(value) {
      this.allData = {};
      this.$Progress.start();
      this.getResults(this.currentPageNumber);
      this.getResults();
      this.$Progress.finish();
    },
    end_date: function() {
      this.allData = {};
      this.$Progress.start();
      this.getResults(this.currentPageNumber);
      this.$Progress.finish();
    }
  },

  created() {
    this.$Progress.start();
    // Fetch initial results
    this.getResults();
    this.$Progress.finish();
  }
};
</script>



<style scoped>
/* table */
.order__details table tr td,
table tr th {
  padding: 0.5rem;
}
.active_bg {
  color: white;
  background-color: #009688;
}
</style>
