<template>
  <div>
    <div class="d-flex justify-content-between align-items-center">
      <div class="h3">Owner Outlet Report</div>
      <div>
        <v-btn
          class="btn_excel"
          elevation="5"
          small
          @click="exportExcel()"
          :loading="exportLoading"
        >
          <v-icon left>mdi-file-excel</v-icon>Export
        </v-btn>
      </div>
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
            ></v-autocomplete>
          </v-col>
          <v-col cols="12" lg="4" md="6">
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
          <v-col cols="12" lg="4" md="6">
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
        </v-row>
      </v-card-title>
      <v-card-text v-if="allData.length && !dataLoading">
        <v-row>
          <v-col cols="12" lg="8">
            <pai-chart v-if="chartShow" :chartData="allChartData" :key="chartKey"></pai-chart>
            <div v-if="!chartShow" class="p-5 my-5">
              <data-not-available v-if="!totalValue && !dataLoading" />
            </div>
          </v-col>
          <v-col cols="12" lg="4">
            <v-card>
              <v-card-title>
                Total Sales &nbsp;
                <span
                  v-if="allSaleAmount"
                >{{ allSaleAmount.total_sale | toCurrency }}</span>
              </v-card-title>

              <v-card height="350" class="scroll" elevation="0">
                <v-card-text>
                  <div>
                    <p class="font-weight-bold">
                      Product Name
                      <span class="float-right">Quantity</span>
                    </p>
                    <hr />
                  </div>
                  <div v-for="(item, index) in allData" :key="index">
                    <div class="d-flex justify-content-between align-center">
                      <div class="d-flex align-center">
                        <img
                          :src="imagePathSm + item.foods.image"
                          alt="Image"
                          class="img-fluid mr-1"
                          height="70"
                          width="70"
                        />
                        <span v-if="item.foods">
                          {{ item.foods.name_en }} ({{
                          item.foods.type.name_en
                          }})
                        </span>
                      </div>
                      <div>
                        <b>
                          <span>{{ item.sum }}</span>
                        </b>
                      </div>
                    </div>
                    <hr />
                  </div>
                </v-card-text>
              </v-card>
            </v-card>
          </v-col>
        </v-row>
      </v-card-text>
      <div v-else>
        <tbl-data-loader v-if="dataLoading" />
      </div>

      <data-not-available v-if="!allData.length && !dataLoading" :message="no_data_loading_msg" />
    </v-card>
  </div>
</template>

<script>
import paiChart from "./chart/sales.vue";
export default {
  components: {
    paiChart
  },
  data() {
    return {
      allShop: [],
      cv_code: "1",
      start_date: this.$moment().format("YYYY-MM-DD"),
      end_date: this.$moment().format("YYYY-MM-DD"),

      currentUrl: "/owner/report",
      allSaleAmount: "",

      // Chart
      chartShow: false,
      allChartData: {},

      allData: "",

      chartKey: 1,

      imagePathSm: "/images/product/small/",
      imagePath: "/images/product/",

      no_data_loading_msg: "No Report Data",

      exportLoading: false,
      title: ""
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
            this.currentUrl +
              "/index?start_date=" +
              this.start_date +
              "&end_date=" +
              this.end_date +
              "&cv_code=" +
              this.cv_code
          )
          .then(response => {
            this.allData = response.data.allData.allData;
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

    // exportExcel
    exportExcel() {
      if (this.allData.length) {
        this.exportLoading = true;

        axios({
          method: "get",
          url:
            this.currentUrl +
            "/export_data?start_date=" +
            this.start_date +
            "&end_date=" +
            this.end_date +
            "&cv_code=" +
            this.cv_code,

          responseType: "blob" // important
        })
          .then(response => {
            let repName = new Date();

            const url = URL.createObjectURL(new Blob([response.data]));
            const link = document.createElement("a");
            link.href = url;
            link.setAttribute("download", `${this.title}.xlsx`);
            document.body.appendChild(link);
            link.click();

            this.exportLoading = false;
          })
          .catch(error => {
            //stop Loading
            this.exportLoading = false;
            console.log(error);
            Swal.fire({
              icon: "error",
              title: "Error !!",
              text: "Somthing going wrong !!"
            });
          });
      } else {
        Swal.fire({
          icon: "error",
          title: "Error !!",
          text: "Data not available !!"
        });
      }
    },

    getShopInfo() {
      axios.get("/owner/report/shop?cv_code=" + this.cv_code).then(response => {
        this.title =
          response.data.shop_name +
          " (" +
          response.data.cv_code +
          ") - " +
          response.data.shop_address +
          "," +
          this.$moment().format("dddd, MMMM Do YYYY");
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
    start_date: function() {
      this.getResults();
    },
    end_date: function() {
      this.getResults();
    },

    currShopInfo: function() {
      this.getResults();
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

