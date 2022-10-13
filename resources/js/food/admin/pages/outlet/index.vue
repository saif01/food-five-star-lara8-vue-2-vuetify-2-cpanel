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
      <span class="font-weight-bold">&nbsp;Outlets</span>
    </div>
    <v-card elevation="0">
      <v-card-title class="justify-center">
        <v-row>
          <v-col cols="10">Outlet List</v-col>

          <v-col cols="2">
            <v-btn
              v-if="isPermit('outlet-add')"
              @click="addDataModel('Add New Outlet')"
              class="btn_add float-right"
              small
            >
              <v-icon left>mdi-plus-circle-outline</v-icon>Add
            </v-btn>
          </v-col>
        </v-row>
      </v-card-title>

      <v-card-text>
        <div>
          <v-row>
            <v-col cols="6" lg="2">
              <v-select
                prepend-icon="mdi-database-eye"
                :items="tblItemNumberShow"
                dense
                v-model="paginate"
                label="Show:"
              ></v-select>
            </v-col>

            <v-col cols="6" lg="2" v-if="allZoneShort">
              <!-- zone -->
              <v-autocomplete
                :items="allZoneShort"
                item-text="name"
                item-value="id"
                v-model="by_zone"
                label="Zone"
                prepend-icon="mdi-map-marker-outline"
                placeholder="Select Zone"
                dense
              ></v-autocomplete>
            </v-col>

            <v-col cols="12" lg="8">
              <v-text-field
                v-model="search"
                prepend-icon="mdi-clipboard-text-search"
                dense
                placeholder="Search ..."
              ></v-text-field>
            </v-col>
          </v-row>

          <div class="table-responsive" v-if="!dataLoading && allData.data.length">
            <table class="table table-bordered">
              <thead>
                <tr class="table_head table_head_link">
                  <th>Details</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="singleData in allData.data" :key="singleData.id">
                  <td>
                    <v-row>
                      <v-col cols="12" lg="6">
                        <div>
                          CV Code:
                          <span v-if="singleData.cv_code">
                            {{
                            singleData.cv_code
                            }}
                          </span>
                          <span v-else class="error--text">N/A</span>
                        </div>
                        <div>
                          Zone:
                          <span v-if="singleData.zone">
                            {{
                            singleData.zone.name
                            }}
                          </span>
                          <span v-else class="error--text">N/A</span>
                        </div>
                        <div>
                          Manager:
                          <span v-if="singleData.manager">
                            {{
                            singleData.manager.name
                            }}
                          </span>
                          <span v-else class="error--text">N/A</span>
                        </div>
                        <div>
                          Officer:
                          <span v-if="singleData.officer">
                            {{
                            singleData.officer.name
                            }}
                          </span>
                          <span v-else class="error--text">N/A</span>
                        </div>
                        <div>
                          Name:
                          <span v-if="singleData.shop_name">
                            {{
                            singleData.shop_name
                            }}
                          </span>
                          <span v-else class="error--text">N/A</span>
                        </div>
                        <div>
                          Address:
                          <span v-if="singleData.shop_address">
                            {{
                            singleData.shop_address
                            }}
                          </span>
                          <span v-else class="error--text">N/A</span>
                        </div>
                        <div>
                          Latitude:
                          <span v-if="singleData.latitude">
                            {{
                            singleData.latitude
                            }}
                          </span>
                          <span v-else class="error--text">N/A</span>
                        </div>
                      </v-col>
                      <v-col cols="12" lg="6">
                        <div>
                          Owner:
                          <span v-if="singleData.owner">
                            {{
                            singleData.owner
                            }}
                          </span>
                          <span v-else class="error--text">N/A</span>
                        </div>
                        <div>
                          Owner Mobile:
                          <span v-if="singleData.owner_mobile">
                            {{
                            singleData.owner_mobile
                            }}
                          </span>
                          <span v-else class="error--text">N/A</span>
                        </div>
                        <div>
                          Owner Mobile 2:
                          <span v-if="singleData.owner_mobile_2">
                            {{
                            singleData.owner_mobile_2
                            }}
                          </span>
                          <span v-else class="error--text">N/A</span>
                        </div>
                        <div>
                          City:
                          <span v-if="singleData.city">{{ getCity(singleData.city) }}</span>
                          <span v-else class="error--text">N/A</span>
                        </div>
                        <div>
                          District:
                          <span
                            v-if="singleData.district"
                          >{{ getDistrict(singleData.district) }}</span>
                          <span v-else class="error--text">N/A</span>
                        </div>
                        <div>
                          Division:
                          <span
                            v-if="singleData.division"
                          >{{ getDivision(singleData.division) }}</span>
                          <span v-else class="error--text">N/A</span>
                        </div>
                        <div>
                          Longtitude:
                          <span v-if="singleData.longitude">
                            {{
                            singleData.longitude
                            }}
                          </span>
                          <span v-else class="error--text">N/A</span>
                        </div>
                      </v-col>
                    </v-row>
                  </td>

                  <td class="text-center">
                    <v-btn
                      v-if="singleData.status"
                      @click="statusChange(singleData)"
                      depressed
                      small
                      class="m-1 btn_active"
                      :style="(isPermit('outlet-status')) ? 'pointer-events: auto;' : 'pointer-events: none'"
                    >
                      <v-icon left>mdi-check-circle-outline</v-icon>Active
                    </v-btn>
                    <v-btn
                      v-else
                      @click="statusChange(singleData)"
                      depressed
                      small
                      class="m-1 btn_inactive"
                      :style="(isPermit('outlet-status')) ? 'pointer-events: auto;' : 'pointer-events: none'"
                    >
                      <v-icon left>mdi-alert-circle-outline</v-icon>Inactive
                    </v-btn>
                    <v-btn
                      v-if="isPermit('outlet-edit')"
                      @click="
                        editDataModel(singleData, 'Update Outlet Info.'),
                          changeDistrict(),
                          changeCity()
                      "
                      small
                      class="ma-1 btn_edit"
                    >
                      <v-icon left>mdi-pencil-outline</v-icon>Edit
                    </v-btn>
                    <v-btn
                      v-if="isPermit('outlet-delete')"
                      @click="deleteDataTemp(singleData.id)"
                      small
                      class="ma-1 error"
                    >
                      <v-icon left>mdi-delete-empty-outline</v-icon>Delete
                    </v-btn>

                    <v-btn
                      @click="openLatLong(singleData)"
                      small
                      class="ma-1 btn_mapview"
                      v-if="singleData.latitude && singleData.longitude"
                    >
                      <v-icon left>mdi-map</v-icon>View Map
                    </v-btn>

                    <div small class="text-muted text-center">
                      Created By --
                      <span v-if="singleData.makby">{{ singleData.makby.name }}</span>
                      <span v-else class="error--text">N/A</span>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
            <v-pagination
              circle
              v-model="currentPageNumber"
              :length="v_total"
              :total-visible="7"
              @input="getResults"
            ></v-pagination>
            <div>
              <span>Total Records: {{ totalValue }}</span>
            </div>
          </div>

          <div v-else>
            <div v-if="dataLoading">
              <tbl-data-loader />
            </div>
          </div>
        </div>

        <data-not-available v-if="!totalValue && !dataLoading" />
      </v-card-text>
    </v-card>

    <v-dialog v-model="dataModal">
      <v-card>
        <v-card-title class="justify-center">
          <v-row>
            <v-col cols="10">{{ dataModelTitle }}</v-col>
            <v-col cols="2">
              <v-btn @click="(dataModal = false), resetForm()" small class="float-right btn_close">
                <v-icon left dark>mdi-close-octagon</v-icon>Close
              </v-btn>
            </v-col>
          </v-row>
        </v-card-title>

        <v-card-text>
          <v-form ref="form">
            <form @submit.prevent="editmode ? updateData() : createData()">
              <v-row>
                <v-col cols="12">
                  <v-text-field
                    v-model="form.cv_code"
                    label="CV Code"
                    placeholder="Enter CV Code"
                    :rules="[(v) => !!v || 'CV Code is required!']"
                    :error-messages="form.errors.get('cv_code')"
                    @change="checkCVCodeValidity(form.cv_code)"
                  >
                    <template slot="append">
                      <v-scroll-x-transition>
                        <v-icon
                          v-if="
                            cvCodeValidityStatus && cvCodeValidityStatus == 'n'
                          "
                          color="success"
                        >mdi-check-outline</v-icon>
                        <v-icon
                          v-if="
                            cvCodeValidityStatus && cvCodeValidityStatus == 'y'
                          "
                          color="error"
                        >mdi-close-outline</v-icon>
                      </v-scroll-x-transition>
                    </template>
                  </v-text-field>
                </v-col>

                <v-col cols="12" lg="6">
                  <v-text-field
                    v-model="form.shop_name"
                    label="Outlet Name"
                    placeholder="Enter Outlet Name"
                    :rules="[(v) => !!v || 'Outlet Name is required!']"
                    :error-messages="form.errors.get('shop_name')"
                  ></v-text-field>
                </v-col>

                <v-col cols="12" lg="6">
                  <v-text-field
                    v-model="form.owner"
                    label="Owner Name"
                    placeholder="Enter Owner Name"
                    :rules="[(v) => !!v || 'Owner Name is required!']"
                    :error-messages="form.errors.get('owner')"
                  ></v-text-field>
                </v-col>

                <v-col cols="12" lg="4" v-if="allZones">
                  <!-- zone -->
                  <v-autocomplete
                    :items="allZones"
                    item-text="name"
                    item-value="id"
                    v-model="form.zone_id"
                    label="Zone"
                    placeholder="Select Zone"
                    :rules="[(v) => !!v || 'Zone is required!']"
                    :error-messages="form.errors.get('zone_id')"
                    dense
                  ></v-autocomplete>
                </v-col>
                <v-col cols="12" lg="4">
                  <!-- manager -->
                  <v-autocomplete
                    v-model="form.manager_id"
                    :items="manager"
                    label="Manager"
                    placeholder="Select Manager"
                    item-text="name"
                    item-value="id"
                    :rules="[(v) => !!v || 'Manager is required!']"
                    :error-messages="form.errors.get('manager_id')"
                    dense
                  >
                    <template v-slot:selection="data">
                      <v-avatar left size="30">
                        <v-img :src="imagePath + data.item.image"></v-img>
                      </v-avatar>
                      {{ data.item.name }}
                    </template>
                    <template v-slot:item="data">
                      <template>
                        <v-list-item-avatar>
                          <img :src="imagePath + data.item.image" />
                        </v-list-item-avatar>
                        <v-list-item-content>
                          <v-list-item-title v-html="data.item.name"></v-list-item-title>
                        </v-list-item-content>
                      </template>
                    </template>
                  </v-autocomplete>
                </v-col>
                <v-col cols="12" lg="4">
                  <!-- officer -->
                  <v-autocomplete
                    v-model="form.officer_id"
                    :items="officer"
                    label="Officer"
                    placeholder="Select Officer"
                    item-text="name"
                    item-value="id"
                    :rules="[(v) => !!v || 'Officer is required!']"
                    :error-messages="form.errors.get('officer_id')"
                    dense
                  >
                    <template v-slot:selection="data">
                      <v-avatar left size="30">
                        <v-img :src="imagePath + data.item.image"></v-img>
                      </v-avatar>
                      {{ data.item.name }}
                    </template>
                    <template v-slot:item="data">
                      <template>
                        <v-list-item-avatar>
                          <img :src="imagePath + data.item.image" />
                        </v-list-item-avatar>
                        <v-list-item-content>
                          <v-list-item-title v-html="data.item.name"></v-list-item-title>
                        </v-list-item-content>
                      </template>
                    </template>
                  </v-autocomplete>
                </v-col>
              </v-row>

              <v-textarea
                v-model="form.shop_address"
                label="Outlet Address"
                placeholder="Enter Outlet Address"
                :rules="[(v) => !!v || 'Outlet address is required!']"
                :error-messages="form.errors.get('shop_address')"
                rows="2"
              ></v-textarea>

              <v-row>
                <v-col cols="12" lg="6">
                  <v-text-field
                    v-model="form.owner_mobile"
                    label="Owner Mobile No"
                    placeholder="Enter Owner Mobile No"
                    :rules="numberRules"
                    :error-messages="form.errors.get('owner_mobile')"
                  ></v-text-field>
                </v-col>
                <v-col cols="12" lg="6">
                  <v-text-field
                    v-model="form.owner_mobile_2"
                    :rules="numberRules2"
                    label="Owner Mobile No (2)"
                    placeholder="Enter Owner Mobile No (2)"
                    :error-messages="form.errors.get('owner_mobile_2')"
                  ></v-text-field>
                </v-col>
                <v-col cols="12" lg="4">
                  <!-- division -->
                  <v-autocomplete
                    :items="division"
                    item-text="name"
                    item-value="id"
                    v-model="form.division"
                    label="Divison"
                    placeholder="Select Divison"
                    :rules="[(v) => !!v || 'Division is required!']"
                    :error-messages="form.errors.get('division')"
                    dense
                    @change="changeDistrict()"
                  ></v-autocomplete>
                </v-col>
                <v-col cols="12" lg="4">
                  <!-- district -->
                  <v-autocomplete
                    :items="districts"
                    item-text="name"
                    item-value="id"
                    v-model="form.district"
                    label="District"
                    placeholder="Select District"
                    :rules="[(v) => !!v || 'District is required!']"
                    :error-messages="form.errors.get('district')"
                    dense
                    @change="changeCity()"
                  ></v-autocomplete>
                </v-col>
                <v-col cols="12" lg="4">
                  <!-- city -->
                  <v-autocomplete
                    :items="cities"
                    item-text="name"
                    item-value="id"
                    v-model="form.city"
                    label="City"
                    placeholder="Select City"
                    :rules="[(v) => !!v || 'City is required!']"
                    :error-messages="form.errors.get('city')"
                    dense
                  ></v-autocomplete>
                </v-col>

                <!-- lat, long -->
                <v-col cols="12" lg="6">
                  <!-- lat -->
                  <v-text-field
                    v-model="form.latitude"
                    label="Latitude"
                    :error-messages="form.errors.get('latitude')"
                    dense
                    placeholder="Enter Outlet Latitude"
                  ></v-text-field>
                </v-col>
                <v-col cols="12" lg="6">
                  <!-- long -->
                  <v-text-field
                    v-model="form.longitude"
                    label="Longitude"
                    :error-messages="form.errors.get('longitude')"
                    dense
                    placeholder="Enter Outlet Longitude"
                  ></v-text-field>
                </v-col>
              </v-row>

              <v-btn
                v-show="editmode"
                type="submit"
                block
                class="my-2 btn_update"
                :loading="dataLodaing"
              >
                <v-icon left>mdi-circle-edit-outline</v-icon>Update
              </v-btn>
              <v-btn
                v-show="!editmode"
                type="submit"
                block
                class="my-2 btn_save"
                :loading="dataLodaing"
              >
                <v-icon left>mdi-plus-circle-outline</v-icon>Create
              </v-btn>
            </form>
          </v-form>
        </v-card-text>
      </v-card>
    </v-dialog>
  </div>
</template>



<script>
// vform
import Form from "vform";
import district from "../../js/district";
import division from "../../js/division";
import city from "../../js/city";

export default {
  data() {
    return {
      //current page url
      currentUrl: "/admin/outlet",

      imagePathSm: "/images/admin/small/",
      imagePath: "/images/admin/",
      imageMaxSize: "2111775",

      numberRules: [
        //v => !!v || 'Phone Number is required',
        v => v.length == 11 || "Phone Number must be 11 characters",
        v => /01+/.test(v) || "Phone Number must be valid"
      ],
      numberRules2: [
        //v => !!v || 'Phone Number is required',
        v => v.length == 11 || "Phone Number must be 11 characters",
        v => /01+/.test(v) || "Phone Number must be valid"
      ],

      // Form
      form: new Form({
        id: "",
        cv_code: "",
        zone_id: "",
        manager_id: "",
        officer_id: "",
        shop_name: "",
        shop_address: "",
        owner: "",
        owner_mobile: "",
        owner_mobile_2: "",
        city: "",
        district: "",
        division: "",
        latitude: "",
        longitude: ""
      }),

      ...district,
      ...division,
      ...city,

      districts: [],
      divisions: [],
      cities: [],

      zone: [],
      manager: [],
      officer: [],

      imagePath: "/images/users/",

      orderKey: 1,
      cvCodeValidityStatus: false,
      cvCodeValidityLoading: false,

      link: ""
    };
  },

  methods: {
    // getDivision
    getDivision(id) {
      let name = "";
      this.division.forEach(element => {
        if (element.id === id) {
          name = element.name;
        }
      });

      return name;
    },

    // getDistrict
    getDistrict(id) {
      let name = "";
      this.district.forEach(element => {
        if (element.id === id) {
          name = element.name;
        }
      });

      return name;
    },

    // getCity
    getCity(id) {
      let name = "";
      this.city.forEach(element => {
        if (element.id === id) {
          name = element.name;
        }
      });

      return name;
    },

    // changeDistrict
    changeDistrict() {
      this.districts = [];
      this.cities = [];
      this.district.forEach(element => {
        if (element.division_id === this.form.division) {
          this.districts.push(element);
        }
      });
    },

    // changeCity
    changeCity() {
      this.cities = [];
      this.city.forEach(element => {
        if (element.district_id === this.form.district) {
          this.cities.push(element);
        }
      });
    },

    // checkCVCodeValidity
    checkCVCodeValidity(val) {
      this.cvCodeValidityLoading = true;
      axios
        .get(this.currentUrl + "/check_cv_code?cv_code=" + val)
        .then(response => {
          this.cvCodeValidityLoading = false;

          this.cvCodeValidityStatus = response.data;
        })
        .catch(error => {
          this.cvCodeValidityLoading = false;
        });
    },

    // get manager_officer
    getManagerOfficer() {
      return axios.get(this.currentUrl + "/get_user").then(response => {
        this.manager = response.data.managers;
        this.officer = response.data.officers;
      });
    },

    openLatLong(e) {
      this.link =
        "https://maps.google.com/maps?q=" +
        e.latitude +
        "," +
        e.longitude +
        "&hl=es&z=14&amp;output=embed";

      window.open(this.link);
    }
  },

  async created() {
    this.$Progress.start();
    // Fetch initial results
    await this.getResults();
    await this.getAllZoneAsync();
    await this.getManagerOfficer();
    this.$Progress.finish();
  }
};
</script>
