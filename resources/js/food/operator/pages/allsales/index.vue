<template>
  <div>
    <v-card class="mb-10" elevation="0">
      <v-card-title>{{ $t("nav__allSales") }}</v-card-title>
      <v-card-text>
        <v-row>
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

              <v-date-picker v-model="end_date" no-title scrollable></v-date-picker>
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
            <table class="table table-bordered text-center table-striped">
              <thead>
                <tr class="table_head">
                  <th>{{ $t("sales__date") }}</th>
                  <th>{{ $t("customer__details") }}</th>
                  <th>{{ $t("sales__number") }}</th>
                  <th>{{ $t("total__") }}</th>
                  <th>{{ $t("action__") }}</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="singleData in allData.data" :key="singleData.id">
                  <td>
                    <span>
                      {{
                      singleData.sales_date | moment("dddd, MMMM DD, h:mm a")
                      }}
                    </span>
                  </td>
                  <td class="pt-0">
                    <v-row>
                      <v-col cols="12" lg="6">
                        <div>
                          {{ $t("name__") }}:
                          <span v-if="singleData.customer_name">
                            {{
                            singleData.customer_name
                            }}
                          </span>
                          <span v-else class="error--text">N/A</span>
                        </div>
                        <div>
                          {{ $t("number__") }}:
                          <span v-if="singleData.customer_number">
                            <!-- <span v-if="currentLang == 'bangla'">
                              {{ englishTobangla(singleData.customer_number) }}
                            </span>-->
                            <span>{{ singleData.customer_number }}</span>
                          </span>
                          <span v-else class="error--text">N/A</span>
                        </div>
                      </v-col>
                      <v-col cols="12" lg="6">
                        <div>
                          {{ $t("age__") }}:
                          <span v-if="singleData.customer_age">
                            <!-- <span v-if="currentLang == 'bangla'">
                              {{ englishTobangla(singleData.customer_age) }}
                            </span>-->
                            <span>{{ singleData.customer_age }}</span>
                          </span>
                          <span v-else class="error--text">N/A</span>
                        </div>
                        <div>
                          {{ $t("type__") }}:
                          <span v-if="singleData.customer_type">
                            <span v-if="currentLang == 'bangla'">
                              <span v-if="singleData.customer_type == 'Male'">
                                {{
                                $t("male__")
                                }}
                              </span>
                              <span v-if="singleData.customer_type == 'Female'">{{ $t("female__") }}</span>
                              <span v-if="singleData.customer_type == 'Other'">{{ $t("other__") }}</span>
                            </span>
                            <span v-else>{{ singleData.customer_type }}</span>
                          </span>
                          <span v-else class="error--text">N/A</span>
                        </div>
                      </v-col>
                    </v-row>
                  </td>

                  <td>
                    <!-- <span v-if="currentLang == 'bangla'">
                      {{ englishTobangla(singleData.sales_numb) }}
                    </span>-->
                    <span>{{ singleData.sales_numb }}</span>
                  </td>

                  <td>
                    <!-- <span v-if="currentLang == 'bangla'">
                      {{ singleData.total_price | toCurrencyBL }}
                    </span>-->
                    <span>{{ singleData.total_price | toCurrency }}</span>
                  </td>

                  <td class="text-center">
                    <v-btn
                      small
                      class="ma-1 text-white btn_edit"
                      @click="getModifyOrder(singleData)"
                    >
                      <v-icon left>mdi-pen</v-icon>
                      {{ $t("modify__order") }}
                    </v-btn>

                    <v-btn class="ma-1 btn_details" small @click="getOrderDetails(singleData)">
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

    <!-- current order details dialog -->
    <v-dialog v-model="currentOrderDetailsDialog" max-width="600">
      <v-card>
        <v-card-title>{{ $t("order__details") }}</v-card-title>
        <v-card-text class="order__details">
          <table class="table table-bordered text-right">
            <colgroup>
              <col span="2" />
              <col class="table_right" />
            </colgroup>
            <thead>
              <tr class="table_head">
                <th class="text-left">{{ $t("name__product") }}</th>
                <th>{{ $t("quantity__") }}</th>
                <th class="text-white">{{ $t("total__") }}</th>
              </tr>
            </thead>

            <tbody>
              <tr v-for="item in currentOrderDetails" :key="item.id">
                <td class="text-left">
                  <span v-if="item.foods">
                    <span v-if="currentLang == 'bangla'">
                      {{ item.foods.name_bn }} ({{
                      item.foods.type.name_bn
                      }})
                    </span>
                    <span v-else>
                      {{ item.foods.name_en }} ({{
                      item.foods.type.name_en
                      }})
                    </span>
                  </span>
                </td>
                <!-- ({{ item.type.name_en }}) -->
                <td>
                  <!-- <span v-if="currentLang == 'bangla'">
                    {{ englishTobangla(item.quantity) }}
                  </span>-->
                  <span>{{ item.quantity }}</span>
                </td>

                <td class="text-white">
                  <span v-if="item.foods.price_offer">
                    <!-- <span v-if="currentLang == 'bangla'">
                      {{
                        (item.quantity * item.foods.price_offer) | toCurrencyBL
                      }}
                    </span>-->
                    <span>
                      {{
                      (item.quantity * item.foods.price_offer) | toCurrency
                      }}
                    </span>
                  </span>
                  <span v-else>
                    <!-- <span v-if="currentLang == 'bangla'">
                      {{ (item.quantity * item.foods.price) | toCurrencyBL }}
                    </span>-->
                    <span>
                      {{
                      (item.quantity * item.foods.price) | toCurrency
                      }}
                    </span>
                  </span>
                </td>
              </tr>
              <tr class="table_bottom">
                <td class="font-weight-bold text-right" colspan="2">{{ $t("subtotal__") }}</td>
                <td class="text-white">
                  <!-- <span v-if="currentLang == 'bangla'">
                    {{ subTotal() | toCurrencyBL }}
                  </span>-->
                  <span>{{ subTotal() | toCurrency }}</span>
                </td>
              </tr>
            </tbody>
          </table>
        </v-card-text>
      </v-card>
    </v-dialog>

    <!-- modifyOverlay -->
    <v-overlay :value="modifyOverlay">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>

    <!-- cart components -->
    <div>
      <sale-cart v-if="bottomSheet" :key="bottomSheetKey"></sale-cart>
    </div>
  </div>
</template>


<script>
import saleModifyMethod from "./modify";
export default {
  data() {
    return {
      modifyDialog: true,
      currentUrl: "/modify",

      // currentOrderDetails
      currentOrderDetails: [],

      // currentOrderDetailsDialog
      currentOrderDetailsDialog: false,

      date: new Date(),

      start_date: this.$moment()
        .subtract(7, "d")
        .format("YYYY-MM-DD"),
      end_date: this.$moment().format("YYYY-MM-DD"),

      // modifyOverlay
      modifyOverlay: false
    };
  },

  methods: {
    ...saleModifyMethod,

    subTotal() {
      const price = [];
      this.currentOrderDetails.forEach(element => {
        if (element.foods.price_offer) {
          price.push(element.quantity * element.foods.price_offer);
        } else {
          price.push(element.quantity * element.foods.price);
        }
      });

      const initialValue = 0;
      const sumWithInitial = price.reduce(
        (previousValue, currentValue) => previousValue + currentValue,
        initialValue
      );

      // if (this.currentLang == "bangla") {
      //   let sum = this.englishTobangla(sumWithInitial);
      //   return sum;
      // } else {
      return sumWithInitial;
      // }
    },

    getResults(page = 1) {
      this.dataLoading = true;
      axios
        .get(
          this.currentUrl +
            "/index?page=" +
            page +
            "&paginate=" +
            this.paginate +
            "&search=" +
            this.search +
            "&sort_direction=" +
            this.sort_direction +
            "&sort_field=" +
            this.sort_field +
            "&search_field=" +
            this.search_field +
            "&start_date=" +
            this.start_date +
            "&end_date=" +
            this.end_date
        )
        .then(response => {
          this.allData = response.data;
          this.totalValue = response.data.total;
          // this.dataShowFrom = response.data.from;
          // this.dataShowTo = response.data.to;
          this.currentPageNumber = response.data.current_page;
          this.v_total = response.data.last_page;

          // Loading Animation
          this.dataLoading = false;
        });
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
    cart: function() {
      if (this.cart.length == "") {
        this.getResults();
      }

      this.bottomSheet = true;
      this.bottomSheetKey++;
    },

    start_date: function() {
      this.getResults();
    },

    end_date: function() {
      this.getResults();
    }
  },

  created() {
    this.$Progress.start();
    this.getResults();
    this.$Progress.finish();
  }
};
</script>


<style scoped>
.order__details table tr td,
table tr th {
  padding: 0.5rem;
}
</style>