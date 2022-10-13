<template>
  <div class="mb-10">
    <v-card elevation="0">
      <v-card-title class="justify-content-between">
        <div>{{ $t("nav__report") }}</div>
        <v-btn
          class="btn_excel"
          elevation="5"
          small
          @click="exportExcel()"
          :loading="exportLoading"
        >
          <v-icon left>mdi-file-excel</v-icon>Export
        </v-btn>
      </v-card-title>
      <v-card-text>
        <v-row>
          <v-col cols="12" lg="6" md="6">
            <v-menu v-model="menu" min-width="auto">
              <template v-slot:activator="{ on, attrs }">
                <v-text-field
                  v-model="computedStartDate"
                  :label="$t('start__')"
                  prepend-icon="mdi-calendar"
                  readonly
                  v-bind="attrs"
                  v-on="on"
                ></v-text-field>
              </template>

              <v-date-picker v-model="start_date" no-title scrollable></v-date-picker>
            </v-menu>
          </v-col>
          <v-col cols="12" lg="6" md="6">
            <v-menu v-model="menu2" min-width="auto">
              <template v-slot:activator="{ on, attrs }">
                <v-text-field
                  v-model="computedEndDate"
                  :label="$t('end__')"
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
      </v-card-text>

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
                {{ $t("total__sales") }} &nbsp;
                <span v-if="allSaleAmount">
                  <!-- <span v-if="currentLang == 'bangla'">
                    {{ allSaleAmount.total_sale | toCurrencyBL }}
                  </span>-->
                  <span>{{ allSaleAmount.total_sale | toCurrency }}</span>
                </span>
              </v-card-title>

              <v-card height="350" class="scroll" elevation="0">
                <v-card-text>
                  <div>
                    <p class="font-weight-bold">
                      {{ $t("product__name") }}
                      <span class="float-right">{{ $t("quantity__") }}</span>
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
                      </div>
                      <div>
                        <b>
                          <!-- <span v-if="currentLang == 'bangla'">
                            {{ englishTobangla(item.sum) }}
                          </span>-->
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

    <!-- <div v-else>
      <tbl-data-loader v-if="dataLoading" />
    </div>-->
  </div>
</template>

<script>
import paiChart from "./chart.vue";
export default {
  components: {
    paiChart
  },
  data() {
    return {
      start_date: this.$moment()
        .subtract(2, "D")
        .format("YYYY-MM-DD"),
      end_date: this.$moment().format("YYYY-MM-DD"),

      currentUrl: "/report",
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
      this.dataLoading = true;
      return axios
        .get(
          this.currentUrl +
            "/index?start_date=" +
            this.start_date +
            "&end_date=" +
            this.end_date
        )
        .then(response => {
          this.allData = response.data.allData.allData;
          this.allSaleAmount = response.data.allData.allSaleAmount;
          this.allChartData = response.data.dataArry;
          this.chartShow = true;
          this.dataLoading = false;
          this.chartKey++;
        });
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
            this.end_date,

          responseType: "blob" // important
        })
          .then(response => {
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
      return axios.get("/shop/index").then(response => {
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
    }
  },

  async created() {
    this.$Progress.start();
    await this.getResults();
    await this.getShopInfo();
    this.$Progress.finish();
  }
};
</script>

<style scoped>
.scroll {
  overflow-y: scroll;
}
</style>