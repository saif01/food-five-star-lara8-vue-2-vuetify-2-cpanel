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
      >&nbsp;/ <span class="text-muted">&nbsp;Report&nbsp;</span> /
      <span class="font-weight-bold">&nbsp;Outlet</span>
    </div>
    <v-card-title class="d-flex justify-content-between">
      <h4>Sales Growth Report by Outlet</h4>
      <v-btn
        class="btn_excel"
        elevation="5"
        small
        @click="allData.length ? exportExcel('outlet') : warning()"
        :loading="exportLoading"
      >
        <v-icon left>mdi-file-excel</v-icon>Export
      </v-btn>
    </v-card-title>

    <v-row>
      <v-col cols="12" lg="4">
        <v-autocomplete
          v-if="allOutletsOptions"
          v-model="currShopInfo"
          label="Outlet"
          placeholder="Select Outlet"
          prepend-icon="mdi-storefront-outline"
          :items="allOutletsOptions"
          return-object
        ></v-autocomplete>
      </v-col>
      <v-col cols="12" lg="4">
        <!-- <v-text-field
          v-model="start_date"
          type="date"
          label="Start Date"
        ></v-text-field>-->
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

          <v-date-picker v-model="start_date" no-title scrollable>
            <v-spacer></v-spacer>
            <v-btn text color="primary" @click="menu = false">Cancel</v-btn>
          </v-date-picker>
        </v-menu>
      </v-col>
      <v-col cols="12" lg="4">
        <!-- <v-text-field
          v-model="end_date"
          type="date"
          label="End Date"
        ></v-text-field>-->
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

          <v-date-picker v-model="end_date" no-title scrollable>
            <v-spacer></v-spacer>
            <v-btn text color="primary" @click="menu2 = false">Cancel</v-btn>
          </v-date-picker>
        </v-menu>
      </v-col>
    </v-row>

    <v-card elevation="0">
      <v-card-text v-if="allData.length">
        <v-row>
          <v-col cols="12" lg="8">
            <div>
              <pai-chart
                v-if="chartShow"
                :chartData="allChartData"
                :key="chartKey"
              ></pai-chart>
            </div>

            <!-- <div v-else class="p-5 my-5">
              <tbl-data-loader v-if="dataLoading" />
              <data-not-available v-if="!dataLoading" />
            </div>-->
          </v-col>

          <v-col cols="12" lg="4">
            <v-card>
              <v-card-title>
                Total Sale &nbsp;
                <span v-if="allSaleAmount && allSaleAmount.total_sale">{{
                  allSaleAmount.total_sale | toCurrency
                }}</span>
              </v-card-title>
              <div class="px-4">
                <p class="font-weight-bold">
                  Product Name
                  <span class="float-right">Quantity</span>
                </p>
                <hr />
              </div>
              <v-card height="350" class="scroll" elevation="0">
                <v-card-text>
                  <div v-for="(item, index) in allData" :key="index">
                    <div class="d-flex justify-content-between align-center">
                      <div class="d-flex align-center">
                        <img
                          :src="imagePathSm + item.foods.image"
                          alt="Image"
                          class="img-fluid"
                          height="70"
                          width="70"
                        />
                        <span v-if="item.foods">{{ item.foods.name_en }}</span>
                      </div>
                      <div>
                        <b>{{ item.sum }}</b>
                      </div>
                    </div>
                    <hr />
                  </div>
                </v-card-text>
                <!-- <v-card-text v-else>
                  <tbl-data-loader v-if="dataLoading" />
                  <data-not-available v-if="!dataLoading" />
                </v-card-text>-->
              </v-card>
            </v-card>
          </v-col>
        </v-row>
      </v-card-text>

      <v-card-text v-else>
        <tbl-data-loader v-if="dataLoading" />
        <data-not-available v-if="!dataLoading" />
      </v-card-text>
    </v-card>
  </div>
</template>

<script>
import paiChart from "./chart/outlet_report_chart.vue";
export default {
  components: {
    paiChart,
  },
  data() {
    return {
      start_date: this.$moment().subtract(1, "M").format("YYYY-MM-DD"),
      end_date: this.$moment().format("YYYY-MM-DD"),

      currentUrl: "/admin/report",

      // Chart
      chartShow: false,
      allChartData: {},

      allData: "",
      allSaleAmount: "",

      dataLoading: false,
      chartKey: 1,

      imagePathSm: "/images/product/small/",
      imagePath: "/images/product/",
    };
  },

  methods: {
    getResults() {
      if (this.currShopInfo) {
        // console.log(this.currShopInfo);

        // Assign CV Code
        this.cv_code = this.currShopInfo.value;

        this.dataLoading = true;
        axios
          .get(
            this.currentUrl +
              "/outlet/index?cv_code=" +
              this.cv_code +
              "&start_date=" +
              this.start_date +
              "&end_date=" +
              this.end_date
          )
          .then((response) => {
            //console.log(response.data.allSale);
            this.allData = response.data.allData.allSale;
            this.allSaleAmount = response.data.allData.allSaleAmount;
            this.allChartData = response.data.dataArry;
            this.chartShow = true;
            this.dataLoading = false;
            this.chartKey++;

            this.title =
              this.currShopInfo.text +
              " (" +
              this.start_date +
              " to " +
              this.end_date +
              ")";
          });
      }
    },
  },

  computed: {
    computedStartDate() {
      return this.formatDate(this.start_date);
    },

    computedEndDate() {
      return this.formatDate(this.end_date);
    },
  },

  watch: {
    currShopInfo: function () {
      this.getResults();
    },
    start_date: function () {
      this.getResults();
    },
    end_date: function () {
      this.getResults();
    },
  },

  created() {
    this.$Progress.start();
    this.dataLoading = true;
    this.AllOutlets();
    this.$Progress.finish();
  },
};
</script>

<style scoped>
.scroll {
  overflow-y: scroll;
}
</style>