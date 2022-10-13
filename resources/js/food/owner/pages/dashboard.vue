<template>
  <div>
    <div class="h3">Owner Outlet Report</div>
    <div class="d-flex align-items-center">
      <v-btn
        small
        :outlined="daily == true ? true : false"
        color="teal"
        class="white--text mr-3"
        @click="
          (monthly = false),
            (yearly = false),
            (daily = true),
            (req = 'd'),
            getResults()
        "
      >Date Wise Report</v-btn>
      <v-btn
        small
        :outlined="monthly == true ? true : false"
        color="teal"
        class="white--text mx-3"
        @click="
          (daily = false),
            (yearly = false),
            (monthly = true),
            (req = 'm'),
            getResults()
        "
      >Monthly Report</v-btn>
      <v-btn
        small
        :outlined="yearly == true ? true : false"
        color="teal"
        class="white--text mx-3"
        @click="
          (monthly = false),
            (daily = false),
            (yearly = true),
            (req = 'y'),
            getResults()
        "
      >Yearly Report</v-btn>
    </div>

    <v-card elevation="0">
      <v-card-title>
        <v-row>
          <v-col lg="4" cols="12">
            <v-autocomplete
              label="Outlet"
              placeholder="Select Outlet"
              prepend-icon="mdi-storefront-outline"
              :items="allShop"
              v-model="currShopInfo"
              return-object
              @change="getResults()"
            ></v-autocomplete>
          </v-col>
          <v-col cols="12" lg="4" md="6" v-if="daily">
            <v-menu v-model="menu" min-width="auto">
              <template v-slot:activator="{ on, attrs }">
                <v-text-field
                  v-model="computedStartDate"
                  label="Start Date"
                  prepend-icon="mdi-calendar"
                  readonly
                  v-bind="attrs"
                  v-on="on"
                ></v-text-field>
              </template>

              <v-date-picker v-model="start_date" no-title scrollable></v-date-picker>
            </v-menu>
          </v-col>
          <v-col cols="12" lg="4" md="6" v-if="daily">
            <v-menu v-model="menu2" min-width="auto">
              <template v-slot:activator="{ on, attrs }">
                <v-text-field
                  v-model="computedEndDate"
                  label="End Date"
                  prepend-icon="mdi-calendar"
                  readonly
                  v-bind="attrs"
                  v-on="on"
                ></v-text-field>
              </template>

              <v-date-picker v-model="end_date" no-title scrollable></v-date-picker>
            </v-menu>
          </v-col>

          <v-col cols="12" lg="4" md="6" v-if="monthly">
            <v-menu v-model="menu3" min-width="auto">
              <template v-slot:activator="{ on, attrs }">
                <v-text-field
                  v-model="monthText"
                  label="Select Month"
                  prepend-icon="mdi-calendar"
                  readonly
                  v-bind="attrs"
                  v-on="on"
                ></v-text-field>
              </template>

              <v-date-picker v-model="month" no-title scrollable type="month"></v-date-picker>
            </v-menu>
          </v-col>
          {{ activePicker }}
          <v-col cols="12" lg="4" md="6" v-if="yearly">
            <v-menu ref="menu4" v-model="menu4" min-width="auto" :close-on-content-click="true">
              <template v-slot:activator="{ on, attrs }">
                <v-text-field
                  v-model="computedYear"
                  label="Select Year"
                  prepend-icon="mdi-calendar"
                  readonly
                  v-bind="attrs"
                  v-on="on"
                ></v-text-field>
              </template>

              <v-date-picker
                v-model="year"
                reactive
                no-title
                scrollable
                show-current
                min="2022"
                max="2032"
                :active-picker.sync="activePicker"
              ></v-date-picker>
            </v-menu>
          </v-col>
        </v-row>
      </v-card-title>
      <v-card-text>
        <div v-if="allData.length && !dataLoading">
          <v-row>
            <v-col cols="12" lg="4" md="6">
              <div class="table-responsive">
                <table class="table table-bordered text-center">
                  <thead class="table_head">
                    <tr>
                      <th>Date</th>
                      <th>Total Sale</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="singleData in allData" :key="singleData.date">
                      <td>
                        <span v-if="req == 'y'">{{ singleData.date }}</span>
                        <span v-else>{{ singleData.date | moment("Do MMMM YYYY") }}</span>
                      </td>
                      <td class="text-right">{{ singleData.total_sale | toCurrency }}</td>
                    </tr>
                    <tr class="table_bottom text-right">
                      <td>Subtotal</td>
                      <td>{{ salesChartData.total | toCurrency }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </v-col>
            <v-col cols="12" lg="8" md="6">
              <sale-by-date-chart
                v-if="chartShow && salesChartData"
                :chartData="salesChartData"
                :chartTitle="chartTitle"
                :key="chartKey"
              />
            </v-col>
          </v-row>
        </div>

        <div v-else>
          <tbl-data-loader v-if="dataLoading" />
        </div>
        <data-not-available v-if="!dataLoading && allData.length <= 0" />
      </v-card-text>
    </v-card>
  </div>
</template>

<script>
import saleByDateChart from "./dashboard_chart/sale_by_date.vue";
export default {
  components: {
    saleByDateChart
  },
  data() {
    return {
      allShop: [],
      cv_code: "1",
      start_date: this.$moment()
        .subtract(1, "M")
        .format("YYYY-MM-DD"),
      end_date: this.$moment().format("YYYY-MM-DD"),
      month: this.$moment().format("YYYY-MM"),
      monthText: this.$moment().format("YYYY-MMMM"),
      year: new Date().toISOString().substr(0, 4),
      activePicker: "",

      currentUrl: "owner/",

      // Chart
      chartTitle: "Total Sales By Date",
      salesChartData: [],
      chartKey: 1,
      chartShow: false,

      daily: true,
      yearly: false,
      monthly: false,
      // requested data
      req: ""
    };
  },

  methods: {
    getResults() {
      if (this.currShopInfo) {
        // Assign CV Code
        this.cv_code = this.currShopInfo.value;
        this.dataLoading = true;

        return axios
          .get(
            "/owner/dashboard_data?start_date=" +
              this.start_date +
              "&end_date=" +
              this.end_date +
              "&cv_code=" +
              this.cv_code +
              "&month=" +
              this.month +
              "&year=" +
              this.computedYear +
              "&req=" +
              this.req
          )
          .then(response => {
            this.chartTitle = response.data.shop_info.shop_name + " Sales";
            this.salesChartData = response.data.chart_data;
            this.allData = response.data.day_wise_sale;

            this.chartKey++;
            this.chartShow = true;
            this.dataLoading = false;
          })
          .catch(error => {
            console.log(error);
          });
      }
    }
  },

  computed: {
    computedStartDate() {
      return this.formatDate(this.start_date);
    },

    computedEndDate() {
      return this.formatDate(this.end_date);
    },

    computedYear() {
      return this.formatYear(this.year);
    }
  },

  watch: {
    start_date: function() {
      this.getResults();
    },
    end_date: function() {
      this.getResults();
    },
    month: function() {
      this.monthText = this.$moment(this.month).format("YYYY-MMMM");
      this.getResults();
    },
    year: function() {
      this.getResults();
    },

    menu4(val) {
      val && setTimeout(() => (this.activePicker = "YEAR"));
    }
  },

  async created() {
    this.$Progress.start();
    await this.getAllShop();
    await this.getResults();
    this.$Progress.finish();
  }
};
</script>

<style scoped>
.scroll {
  overflow-y: scroll;
}
</style>

