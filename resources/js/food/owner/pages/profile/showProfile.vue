<template>
  <div>
    <v-row v-if="!dataLoading">
      <v-col cols="12" lg="8" class="mx-auto">
        <v-card class="mb-5" elevation="0">
          <v-card-title>Profile </v-card-title>

          <v-card-text>
            <v-row>
              <v-col cols="12" lg="3" class="text-center">
                <v-card-text>
                  <v-avatar size="170">
                    <img
                      v-if="auth.image"
                      :src="'/images/users/' + auth.image"
                      alt="image"
                      class="p-1 bg-dark"
                    />
                    <img
                      v-else
                      src="https://www.w3schools.com/howto/img_avatar.png"
                      alt="image"
                      class="p-1 bg-dark"
                    />
                  </v-avatar>
                  <div class="h3 mt-3" style="color: #ff5722">
                    {{ auth.name }}
                  </div>

                  <div>
                    <v-icon left>mdi-account-key</v-icon> Login ID:
                    <a :href="`tel:${auth.owner_login}`">{{
                      auth.owner_login
                    }}</a>
                  </div>
                </v-card-text>
              </v-col>
              <v-col cols="12" lg="9">
                <v-card-text
                  style="background-color: #efefef"
                  class="my-2"
                  v-for="item in allShopDetails"
                  :key="item.id"
                >
                  <v-row>
                    <v-col cols="12" lg="6">
                      <div>
                        <v-icon left>mdi-code-equal</v-icon>CV Code:
                        <span class="font-weight-bold">{{ item.cv_code }}</span>
                      </div>

                      <div>
                        <v-icon left>mdi-storefront-outline</v-icon> Outlet
                        Name:
                        <span>
                          <span v-if="item.shop_name" class="font-weight-bold">
                            {{ item.shop_name }}
                          </span>
                          <span v-else class="error--text">N/A</span>
                        </span>
                      </div>

                      <div>
                        <v-icon left>mdi-map-marker-outline</v-icon>Outlet
                        Address:
                        <span>
                          <span
                            v-if="item.shop_address"
                            class="font-weight-bold"
                          >
                            {{ item.shop_address }}
                          </span>
                          <span v-else class="error--text">N/A</span>
                        </span>
                      </div>

                      <div>
                        <v-icon left v-if="item.online" class="teal--text"
                          >mdi-circle-slice-8</v-icon
                        >
                        <v-icon left v-else class="error--text"
                          >mdi-circle-slice-8</v-icon
                        >
                        <span>
                          <span
                            class="font-weight-bold teal--text"
                            v-if="item.online"
                          >
                            Online
                          </span>
                          <span v-else class="error--text font-weight-bold"
                            >Offline</span
                          >
                        </span>
                      </div>
                    </v-col>
                    <v-col cols="12" lg="6">
                      <v-card-text
                        v-if="item.chLabels.length && item.chData.length"
                        class="pt-0 pl-0"
                      >
                        <bar-chart
                          style="height: 150px"
                          v-if="chartShow && item.chLabels && item.chData"
                          :chartLabel="item.chLabels"
                          :chartData="item.chData"
                          :key="chartKey"
                        ></bar-chart>
                      </v-card-text>
                    </v-col>
                  </v-row>
                </v-card-text>
              </v-col>
            </v-row>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>

    <div v-else>
      <tbl-data-loader v-if="dataLoading" />
    </div>
  </div>
</template>

<script>
import barChart from "./bar_chart.vue";
export default {
  components: {
    barChart,
  },
  data() {
    return {
      allShopDetails: "",
      allShop: [],

      // Chart
      chartShow: false,
      chartKey: 1,
      bar_chart_label: [],
      bar_chart_data: [],
    };
  },

  methods: {
    // view-profile

    getShops() {
      axios.get("/owner/view-profile/shop").then((response) => {
        if (response.data) {
          this.allShopDetails = response.data;
          this.chartShow = true;
        }
      });
    },
  },
  created() {
    this.$Progress.start();
    this.getShops();
    this.$Progress.finish();
  },
};
</script>
