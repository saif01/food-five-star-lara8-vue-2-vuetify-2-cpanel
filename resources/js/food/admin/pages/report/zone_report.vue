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
      <span class="font-weight-bold">&nbsp;Zone</span>
    </div>
    <v-card-title class="d-flex justify-content-between">
      <h4>Sales Growth Report by Zone</h4>
      <v-btn
        class="btn_excel"
        elevation="5"
        small
        @click="allData.length ? exportExcel('zone') : warning()"
        :loading="exportLoading"
      >
        <v-icon left>mdi-file-excel</v-icon>Export
      </v-btn>
    </v-card-title>
    <v-row>
      <v-col cols="12" lg="4">
        <v-autocomplete
          v-model="selectZoneItemObj"
          label="Zone"
          placeholder="Select Zone"
          prepend-icon="mdi-map-marker-outline"
          :items="zoneSelectItem"
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
      <v-card-text>
        <v-row>
          <v-col cols="12">
            <div v-if="allData.length">
              <bar-chart
                v-if="chartShow && allChartData"
                :chartData="allChartData"
                :key="chartKey"
              ></bar-chart>
              <hr />
              <div class="table-responsive">
                <table class="mt-5 table table-bordered table-striped">
                  <thead>
                    <tr class="table_head">
                      <th>Date</th>
                      <th>Name</th>
                      <th class="text-right">Quantity</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(item, index) in allData" :key="index">
                      <td>{{ item.created_at | moment("MMMM Do YYYY") }}</td>
                      <td>
                        <span v-if="item.foods">
                          {{ item.foods.name_en }}
                          <img
                            :src="imagePathSm + item.foods.image"
                            alt="Image"
                            class="img-fluid"
                            height="70"
                            width="70"
                          />
                        </span>
                        <span v-else class="text-danger">N/A</span>
                      </td>
                      <td class="text-right">{{ item.sum }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div v-else>
              <tbl-data-loader v-if="dataLoading" />
            </div>
            <data-not-available v-if="!allData.length && !dataLoading" />
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>
  </div>
</template>

<script>
import barChart from "./chart/product_sale_chart.vue";
export default {
  components: {
    barChart,
  },
  data() {
    return {
      start_date: this.$moment().subtract(1, "M").format("YYYY-MM-DD"),
      end_date: this.$moment().format("YYYY-MM-DD"),

      currentUrl: "/admin/report/zone",

      // Chart
      chartShow: false,
      allChartData: [],

      allData: "",

      dataLoading: false,
      chartKey: 1,
      imagePathSm: "/images/product/small/",
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
              "&start_date=" +
              this.start_date +
              "&end_date=" +
              this.end_date
          )
          .then((response) => {
            this.allData = response.data.allData;
            this.allChartData = response.data.dataArry;
            this.chartShow = true;

            this.dataLoading = false;
            this.chartKey++;

            this.title =
              "Sales growth report by zone " +
              this.selectZoneItemObj.text +
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
    selectZoneItemObj: function () {
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
    this.getAllZones();
    this.$Progress.finish();
  },
};
</script>

<style scoped>
.scroll {
  overflow-y: scroll;
}
</style>