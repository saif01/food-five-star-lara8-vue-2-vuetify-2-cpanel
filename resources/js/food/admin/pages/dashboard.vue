<template>
  <div>
    <v-row class="ma-2">
      <v-col cols="12" lg="4">
        <v-card elevation="0" class="dcard dashboard_card1_bg1" v-if="outlets">
          <v-row>
            <v-col
              cols="3"
              md="2"
              lg="4"
              class="
                d-flex
                justify-content-end
                align-items-center
                icon
                bg-white
                p-1
              "
            >
              <v-btn
                class="
                  rounded-circle
                  dashboard_card1_bg2
                  pa-11
                  text-white
                  rotate_icon
                "
                x-large
                icon
              >
                <v-icon x-large>mdi-storefront</v-icon>
              </v-btn>
            </v-col>
            <v-col cols="9" md="10" lg="8" class="text-center text-white">
              <div class="display-2">
                <div class="iCountUp">
                  <ICountUp
                    :delay="1000"
                    :endVal="outlets"
                    :options="options"
                  />
                </div>
              </div>
              <div>Outlet</div>
            </v-col>
          </v-row>
        </v-card>
      </v-col>

      <v-col cols="12" lg="4">
        <v-card elevation="0" class="dcard dashboard_card2_bg1" v-if="owners">
          <v-row class="d-flex">
            <v-col
              cols="3"
              md="2"
              lg="4"
              class="
                d-flex
                justify-content-end
                align-items-center
                icon
                bg-white
                p-1
              "
            >
              <v-btn
                class="
                  rounded-circle
                  dashboard_card2_bg2
                  pa-11
                  text-white
                  rotate_icon
                "
                x-large
                icon
              >
                <v-icon x-large>mdi-account-supervisor-circle</v-icon>
              </v-btn>
            </v-col>
            <v-col cols="9" md="10" lg="8" class="text-center text-white">
              <div class="display-2">
                <div class="iCountUp">
                  <ICountUp :delay="1000" :endVal="owners" :options="options" />
                </div>
              </div>
              <div>Owner</div>
            </v-col>
          </v-row>
        </v-card>
      </v-col>

      <v-col cols="12" lg="4" v-if="operators">
        <v-card elevation="0" class="dcard dashboard_card3_bg1">
          <v-row class="d-flex">
            <v-col
              cols="3"
              md="2"
              lg="4"
              class="
                d-flex
                justify-content-end
                align-items-center
                icon
                bg-white
                p-1
              "
            >
              <v-btn
                class="
                  rounded-circle
                  dashboard_card3_bg2
                  pa-11
                  text-white
                  rotate_icon
                "
                x-large
                icon
              >
                <v-icon x-large>mdi-account-group-outline</v-icon>
              </v-btn>
            </v-col>
            <v-col cols="9" md="10" lg="8" class="text-center text-white">
              <div class="display-2">
                <div class="iCountUp">
                  <ICountUp
                    :delay="1000"
                    :endVal="operators"
                    :options="options"
                  />
                </div>
              </div>
              <div>Operator</div>
            </v-col>
          </v-row>
        </v-card>
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12" lg="4">
        <v-card class="mt-5">
          <v-card-title>
            Today's Top 5 Outlet Sale
            <img
              src="https://asset.cpbangladesh.com/documents/fire_20220720093404n4FV0uh89P.gif"
              height="25"
            />
          </v-card-title>
          <!-- <v-card-text>
        <div v-if="top_five_shop_sale">
          <v-simple-table dark class="simple_table">
            <thead>
              <tr>
                <th>Outlet Name</th>
                <th>Total Sale</th>
                <th>Zone</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in top_five_shop_sale" :key="item.id">
                <td>
                  <span v-if="item.outlet_details"
                    >{{ item.outlet_details.shop_name }} ({{
                      item.cv_code
                    }})</span
                  >
                  <span v-else class="error--text">N/A</span>
                </td>
                <td>{{ item.today_sale | toCurrency }}</td>
                <td>
                  <span v-if="item.outlet_details.zone">{{
                    item.outlet_details.zone.name
                  }}</span>
                  <span v-else class="error--text">N/A</span>
                </td>
              </tr>
            </tbody>
          </v-simple-table>
        </div>
        <div v-else>
          <tbl-data-loader v-if="dataLoading" />
        </div>
        <data-not-available v-if="!top_five_shop_sale && !dataLoading" />
      </v-card-text>-->

          <v-card-text>
            <doughnut-chart
              v-if="chartShow && doughnut_chart_label && doughnut_chart_data"
              :chartLabel="doughnut_chart_label"
              :chartData="doughnut_chart_data"
              :key="chartKey + 6"
            ></doughnut-chart>
            <div v-else>
              <tbl-data-loader v-if="dataLoading" />
            </div>
            <data-not-available
              v-if="
                !doughnut_chart_label && !doughnut_chart_data && !dataLoading
              "
            />
          </v-card-text>
        </v-card>
      </v-col>
      <v-col cols="12" lg="8">
        <v-card class="mt-5">
          <v-card-title>
            Last 7 days Outlet Sale
            <img
              src="https://asset.cpbangladesh.com/documents/fire_20220720093404n4FV0uh89P.gif"
              height="25"
            />
          </v-card-title>
          <v-card-text>
            <bar-chart
              v-if="chartShow && bar_chart_label && bar_chart_data"
              :chartLabel="bar_chart_label"
              :chartData="bar_chart_data"
              :key="chartKey + 3"
            ></bar-chart>
            <div v-else>
              <tbl-data-loader v-if="dataLoading" />
            </div>
            <data-not-available
              v-if="!bar_chart_label && !bar_chart_data && !dataLoading"
            />
          </v-card-text>
          <!-- <v-card-text>
        <div v-if="last_seven_days_sale">
          <v-simple-table class="bg-success white--text simple_table">
            <thead>
              <tr>
                <th class="white--text">Outlet Name</th>
                <th class="white--text">Total Sale</th>
                <th class="white--text">Zone</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in last_seven_days_sale" :key="item.id">
                <td>
                  <span v-if="item.outlet_details"
                    >{{ item.outlet_details.shop_name }} ({{
                      item.cv_code
                    }})</span
                  >
                  <span v-else class="error--text">N/A</span>
                </td>
                <td>{{ item.today_sale | toCurrency }}</td>
                <td>
                  <span v-if="item.outlet_details.zone">{{
                    item.outlet_details.zone.name
                  }}</span>
                  <span v-else class="error--text">N/A</span>
                </td>
              </tr>
            </tbody>
          </v-simple-table>
        </div>
        <div v-else>
          <tbl-data-loader v-if="dataLoading" />
        </div>
        <data-not-available v-if="!last_seven_days_sale && !dataLoading" />
      </v-card-text>-->
        </v-card>
      </v-col>
    </v-row>

    <v-card class="mt-5">
      <v-card-title>Last 30 Days Sales & Orders</v-card-title>
      <v-card-text>
        <line-chart
          v-if="chartShow && ChartLavbel && ChartData"
          :chartLavbel="ChartLavbel"
          :chartData="ChartData"
          :key="chartKey"
        ></line-chart>
        <div v-else>
          <tbl-data-loader v-if="dataLoading" />
        </div>
        <data-not-available v-if="!ChartLavbel && !ChartData && !dataLoading" />
      </v-card-text>
    </v-card>
  </div>
</template>

<script>
import ICountUp from "vue-countup-v2";
import lineChart from "./dashboard_chart/line_chart.vue";
import barChart from "./dashboard_chart/bar_chart.vue";
import doughnutChart from "./dashboard_chart/doughnut_chart.vue";

export default {
  components: {
    barChart,
    ICountUp,
    lineChart,
    doughnutChart,
  },

  data() {
    return {
      outlets: "",
      operators: "",
      owners: "",
      top_five_shop_sale: "",
      last_seven_days_sale: "",
      value: 100,
      currentUrl: "/admin",

      // Chart
      chartShow: false,
      chartKey: 1,
      ChartLavbel: [],
      ChartData: [],

      bar_chart_label: [],
      bar_chart_data: [],

      doughnut_chart_label: [],
      doughnut_chart_data: [],

      options: {
        useEasing: true,
        useGrouping: true,
      },
    };
  },

  methods: {
    dashboardData() {
      this.dataLoading = true;
      axios
        .get(this.currentUrl + "/dashboard_data")
        .then((response) => {
          this.outlets = response.data.allData.outlet;
          this.operators = response.data.allData.operator;
          this.owners = response.data.allData.owner;
          this.top_five_shop_sale = response.data.allData.top_five_shop_sale;
          this.last_seven_days_sale =
            response.data.allData.last_seven_days_sale;
          this.dataLoading = false;

          // Chart Data
          this.ChartLavbel = response.data.allData.line_chart_lavel;
          this.ChartData = response.data.allData.line_chart_data;

          this.bar_chart_label = response.data.allData.bar_chart_label;
          this.bar_chart_data = response.data.allData.bar_chart_data;

          this.doughnut_chart_label =
            response.data.allData.doughnut_chart_label;
          this.doughnut_chart_data = response.data.allData.doughnut_chart_data;

          //console.log(response.data.sale_order_chart_data.labels);

          // console.log(this.ChartLavbel, this.ChartData);
          this.chartShow = true;
        })
        .catch((error) => {
          this.dataLoading = false;
          console.log(error);
        });
    },

    getDaysInMonth(month = 8, year = 2022) {
      var date = new Date(year, month, 1);
      var days = [];
      while (date.getMonth() === month) {
        days.push(new Date(date));
        date.setDate(date.getDate() + 1);
      }
      return days;
    },
  },

  mounted() {
    // console.log(this.getDaysInMonth());
  },

  created() {
    this.dashboardData();
  },
};
</script>


<style scoped>
.simple_table tr:hover {
  background: transparent !important;
}

.icon {
  border-radius: 0 50px 50px 0px;
}

.rotate_icon {
  animation: mymove 3s;
}

@keyframes mymove {
  50% {
    transform: rotate(180deg);
  }
}

.dcard::after {
  content: "";
  width: 30px;
  height: 200px;
  position: absolute;
  left: -20px;
  top: 0;
  margin-top: -20px;
  background: #fff;
  opacity: 0.6;
  filter: blur(20px);
  animation: motion 3s infinite;
  animation-direction: alternate;
}

@keyframes motion {
  0% {
    width: 30px;
    height: 30px;
    rotate: 45deg;
  }
  100% {
    width: 200%;
    height: 100%;
    rotate: 45deg;
  }
}
</style>


