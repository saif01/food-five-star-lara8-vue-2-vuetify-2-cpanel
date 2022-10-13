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
      >Dashboard</router-link>&nbsp;/
      <span class="text-muted">&nbsp;Report&nbsp;</span> /
      <span class="font-weight-bold">&nbsp;Hourly</span>
    </div>
    <v-card-title class="d-flex justify-content-between">
      <h4>Hourly Report</h4>
      <v-btn
        class="btn_excel"
        elevation="5"
        small
        @click="allData ? exportExcel('hour') : warning()"
        :loading="exportLoading"
      >
        <v-icon left>mdi-file-excel</v-icon>Export
      </v-btn>
    </v-card-title>
    <v-row>
      <v-col cols="12" lg="6">
        <v-autocomplete
          v-model="selectZoneItemObj"
          label="Zone"
          prepend-icon="mdi-map-marker-outline"
          placeholder="Select Zone"
          :items="zoneSelectItem"
          return-object
        ></v-autocomplete>
      </v-col>
      <v-col cols="12" lg="6">
        <!-- <v-text-field v-model="date" type="date" label="Date"></v-text-field> -->
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

    <div v-if="!dataLoading">
      <v-row>
        <v-col cols="12" v-if="allData">
          <bar-chart v-if="chartShow && allData" :chartData="allChartData" :key="chartKey"></bar-chart>
        </v-col>

        <v-col cols="12">
          <div v-if="allData">
            <v-row>
              <v-col cols="12" lg="4" v-for="(items, key) in allData" :key="items.id">
                <v-card class="mb-3">
                  <v-card-text>
                    <div>
                      <div class="d-flex justify-content-between">
                        <span class="font-weight-bold h5">Sales Time {{ key }}</span>
                      </div>

                      <v-simple-table>
                        <thead>
                          <tr>
                            <th>Product</th>
                            <th class="text-right">Quantity</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="item in items" :key="item.id">
                            <td>
                              <div class="d-flex align-center">
                                <img
                                  :src="imagePathSm + item.foods.image"
                                  alt="Image"
                                  class="img-fluid"
                                  height="70"
                                  width="70"
                                />
                                <span>
                                  {{ item.foods.name_en }}({{
                                  item.foods.type.name_en
                                  }})
                                </span>
                              </div>
                            </td>
                            <td class="text-right">{{ item.total_sales }}</td>
                          </tr>
                        </tbody>
                      </v-simple-table>
                    </div>
                  </v-card-text>
                </v-card>
              </v-col>
            </v-row>
          </div>
          <div v-else>
            <data-not-available />
          </div>
        </v-col>
      </v-row>
    </div>
    <div v-else>
      <tbl-data-loader v-if="dataLoading" />
      <data-not-available v-if="!allData && !dataLoading" />
    </div>
  </div>
</template>

<script>
import barChart from "./chart/time_report_chart.vue";
export default {
  components: {
    barChart
  },
  data() {
    return {
      zone: "",
      zones: [],

      date: this.$moment().format("YYYY-MM-DD"),

      currentUrl: "/admin/report/hour",

      // Chart
      chartShow: false,
      allChartData: {},

      allData: [],

      dataLoading: false,
      chartKey: 1,

      imagePathSm: "/images/product/small/",
      imagePath: "/images/product/",

      time: "",
      menu: "",

      time2: "",
      menu2: ""
    };
  },

  methods: {
    getResults() {
      if (this.selectZoneItemObj) {
        // Assign Zone ID
        this.selectZoneItem = this.selectZoneItemObj.value;
        this.dataLoading = true;
        axios
          .get(
            this.currentUrl +
              "/index?zone=" +
              this.selectZoneItem +
              "&date=" +
              this.date
          )
          .then(response => {
            this.allData = response.data.finalData;

            let dataArry = {
              labels: response.data.chart_label,
              data: response.data.chart_data
            };
            // assign dataa
            this.allChartData = dataArry;
            this.chartShow = true;

            this.dataLoading = false;
            this.chartKey++;

            this.title =
              "Hourly Report of " +
              this.selectZoneItemObj.text +
              " (" +
              this.date +
              ")";
          });
      }
    }
  },

  computed: {
    computedDate() {
      return this.formatDate(this.date);
    }
  },

  watch: {
    selectZoneItemObj: function() {
      this.getResults();
    },
    date: function() {
      this.getResults();
    }
  },

  created() {
    this.$Progress.start();
    this.dataLoading = true;
    this.getAllZones();
    this.getResults();
    this.$Progress.finish();
  }
};
</script>

<style scoped>
.scroll {
  overflow-y: scroll;
}
</style>