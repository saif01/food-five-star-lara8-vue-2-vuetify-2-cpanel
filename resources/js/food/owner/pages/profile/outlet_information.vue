<template>
  <div>
    <div class="px-3 h3">Outlet Information</div>

    <v-card elevation="0">
      <v-autocomplete
        label="Outlet"
        placeholder="Select Outlet"
        prepend-icon="mdi-storefront-outline"
        :items="allShop"
        v-model="cv_code"
      ></v-autocomplete>

      <v-card-text v-if="!dataLoading">
        <v-simple-table dark>
          <tbody>
            <tr>
              <td>CV Code: {{ allData.cv_code }}</td>
              <td>Outlet Name: {{ allData.shop_name }}</td>
            </tr>
            <tr>
              <td>
                Contact Number:
                <span v-if="allData.owner_mobile">{{
                  allData.owner_mobile
                }}</span>
                <span v-else class="error--text">N/A</span>
              </td>

              <td>
                Division:
                <span v-if="allData.division">{{
                  getDivision(allData.division)
                }}</span>
                <span v-else class="error--text">N/A</span>
              </td>
            </tr>
            <tr>
              <td>
                Address:
                <span v-if="allData.shop_address">{{
                  allData.shop_address
                }}</span>
                <span v-else class="error--text">N/A</span>
              </td>

              <td>
                District:
                <span v-if="allData.district">{{
                  getDistrict(allData.district)
                }}</span>
                <span v-else class="error--text">N/A</span>
              </td>
            </tr>
            <tr>
              <td>
                Manager:
                <span v-if="allData.manager">{{ allData.manager.name }}</span>
                <span v-else class="error--text">N/A</span>
              </td>

              <td>
                City:
                <span v-if="allData.city">{{ getCity(allData.city) }}</span>
                <span v-else class="error--text">N/A</span>
              </td>
            </tr>
            <tr>
              <td>
                Officer:
                <span v-if="allData.officer">{{ allData.officer.name }}</span>
                <span v-else class="error--text">N/A</span>
              </td>
              <td>
                Zone:
                <span v-if="allData.zone">{{ allData.zone.name }}</span>
                <span v-else class="error--text">N/A</span>
              </td>
            </tr>
            <tr>
              <td>
                Latitude:
                <span v-if="allData.latitude">{{ allData.latitude }}</span>
                <span v-else class="error--text">N/A</span>
              </td>
              <td>
                Longitude:
                <span v-if="allData.longitude">{{ allData.longitude }}</span>
                <span v-else class="error--text">N/A</span>

                <v-btn
                  @click="openLatLong(allData)"
                  small
                  class="ma-1 btn_mapview"
                  v-if="allData.latitude && allData.longitude"
                >
                  <v-icon left>mdi-map</v-icon>View Map
                </v-btn>
              </td>
            </tr>
            <!-- <tr v-if="allData.latitude && allData.longitude">
              <td colspan="2">
                <v-btn
                  @click="openLatLong(allData)"
                  small
                  class="ma-1 btn_mapview"
                  v-if="allData.latitude && allData.longitude"
                >
                  <v-icon left>mdi-map</v-icon>View Map
                </v-btn>
              </td>
            </tr> -->
          </tbody>
        </v-simple-table>

        <!-- <v-card-text v-if="link" class="mt-10" :key="mapKey">
          <iframe
            :src="link"
            frameborder="0"
            height="400px"
            width="100%"
          ></iframe>
        </v-card-text> -->
      </v-card-text>

      <div v-else>
        <tbl-data-loader v-if="dataLoading" />
      </div>
    </v-card>
  </div>
</template>

<script>
import district from "../../js/district";
import division from "../../js/division";
import city from "../../js/city";
export default {
  data() {
    return {
      allShop: [],
      cv_code: "",

      currentUrl: "/owner/view-profile",
      allData: "",

      ...district,
      ...division,
      ...city,

      link: "",
      mapKey: 1,
    };
  },

  methods: {
    getResults() {
      this.dataLoading = true;
      axios
        .get(
          this.currentUrl +
            "/index?start_date=" +
            this.start_date +
            "&end_date=" +
            this.end_date +
            "&cv_code=" +
            this.cv_code
        )
        .then((response) => {
          this.allData = response.data;

          // this.openLatLong(response.data);

          this.dataLoading = false;
        });
    },

    getDistrict(id) {
      let district = "";
      this.district.forEach((element) => {
        if (element.id === id) {
          if (this.currentLang == "bangla") {
            district = element.bn_name;
          } else {
            district = element.name;
          }
        }
      });
      return district;
    },

    getDivision(id) {
      let division = "";
      this.division.forEach((element) => {
        if (element.id === id) {
          if (this.currentLang == "bangla") {
            division = element.bn_name;
          } else {
            division = element.name;
          }
        }
      });
      return division;
    },

    getCity(id) {
      let city = "";
      this.city.forEach((element) => {
        if (element.id === id) {
          if (this.currentLang == "bangla") {
            city = element.bn_name;
          } else {
            city = element.name;
          }
        }
      });
      return city;
    },

    openLatLong(e) {
      this.link =
        "https://maps.google.com/maps?q=" +
        e.latitude +
        "," +
        e.longitude +
        "&hl=es&z=14&amp;output=embed";

      window.open(this.link);
    },

    // openLatLong(e) {
    //   if (e.latitude && e.longitude) {
    //     this.link =
    //       "https://maps.google.com/maps?q=" +
    //       e.latitude +
    //       "," +
    //       e.longitude +
    //       "&hl=es&z=14&amp;output=embed";
    //   } else {
    //     this.link = "";
    //   }

    //   this.mapKey++;
    // },
  },

  watch: {
    cv_code: function () {
      this.getResults();
    },
  },

  created() {
    this.$Progress.start();
    this.getAllShop();
    this.$Progress.finish();
  },
};
</script>