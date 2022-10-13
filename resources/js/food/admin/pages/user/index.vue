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
      <span class="text-muted">&nbsp;Users&nbsp;</span> /
      <span class="font-weight-bold">&nbsp;Admin</span>
    </div>
    <v-card elevation="0">
      <v-card-title class="justify-center">
        <v-row>
          <v-col cols="10">
            Admin List&nbsp;
            <v-chip v-if="totalOnline" color="success" @click="toogleStatus()">
              Total Online: &nbsp;
              <b>{{ totalOnline }}</b>
            </v-chip>
            <v-chip v-else>Total Online: 0</v-chip>
          </v-col>

          <v-col cols="2">
            <v-btn
              v-if="isPermit('admin-add')"
              @click="addDataModel('Add New Admin'), getAllManagers()"
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
            <v-col cols="4" lg="2">
              <v-select
                prepend-icon="mdi-database-eye"
                :items="tblItemNumberShow"
                dense
                v-model="paginate"
                label="Show:"
              ></v-select>
            </v-col>

            <v-col cols="8" lg="10">
              <v-text-field
                v-model="search"
                prepend-icon="mdi-clipboard-text-search"
                label="Search:"
                dense
                placeholder="Search ..."
              ></v-text-field>
            </v-col>
          </v-row>

          <div class="table-responsive" v-if="!dataLoading && allData.data.length">
            <table class="table table-bordered text-center">
              <thead>
                <tr class="table_head table_head_link">
                  <th class="col-1">Image</th>
                  <th class="col-5">Details</th>
                  <th class="col-4">Role / Zone</th>
                  <th class="col-2">Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="singleData in allData.data" :key="singleData.id">
                  <td>
                    <v-badge
                      v-if="singleData.image"
                      bordered
                      top
                      :color="singleData.online ? 'success' : 'error'"
                      offset-x="15"
                      offset-y="15"
                    >
                      <v-avatar size="100">
                        <img :src="imagePathSm + singleData.image" alt="Image" />
                      </v-avatar>
                    </v-badge>

                    <v-badge
                      v-else
                      bordered
                      top
                      :color="singleData.online ? 'success' : 'error'"
                      offset-x="15"
                      offset-y="15"
                    >
                      <v-avatar size="100">
                        <v-img src="/all-assets/common/img/no-image.png"></v-img>
                      </v-avatar>
                    </v-badge>
                  </td>
                  <td class="text-left" :class="singleData.officer_type == 'mg' ? 'active_bg' : ''">
                    <v-row>
                      <v-col cols="12" lg="6">
                        <div>
                          Login:
                          <span v-if="singleData.login">{{ singleData.login }}</span>
                          <span v-else class="error--text">N/A</span>
                        </div>
                        <div>
                          Name:
                          <span v-if="singleData.name">{{ singleData.name }}</span>
                          <span v-else class="error--text">N/A</span>
                        </div>

                        <div>
                          Department:
                          <span v-if="singleData.department">{{ singleData.department }}</span>
                          <span v-else class="error--text">N/A</span>
                        </div>

                        <div>
                          Office ID:
                          <span v-if="singleData.office_id">{{ singleData.office_id }}</span>
                          <span v-else class="error--text">N/A</span>
                        </div>

                        <div>
                          Office Contact:
                          <span
                            v-if="singleData.office_contact"
                          >{{ singleData.office_contact }}</span>
                          <span v-else class="error--text">N/A</span>
                        </div>

                        <div>
                          Personal Contact:
                          <span
                            v-if="singleData.personal_contact"
                          >{{ singleData.personal_contact }}</span>
                          <span v-else class="error--text">N/A</span>
                        </div>
                      </v-col>
                      <v-col cols="12" lg="6">
                        <div>
                          Office Email:
                          <span
                            v-if="singleData.office_email"
                          >{{ singleData.office_email }}</span>
                          <span v-else class="error--text">N/A</span>
                        </div>

                        <div>
                          Personal Email:
                          <span
                            v-if="singleData.personal_email"
                          >{{ singleData.personal_email }}</span>
                          <span v-else class="error--text">N/A</span>
                        </div>

                        <div>
                          Type:
                          <span v-if="singleData.officer_type">
                            <v-chip small v-if="singleData.officer_type == 'of'">
                              Officer
                              <v-icon color="success">mdi-arrow-right-thick</v-icon>
                              <span
                                v-if="singleData.manager_details"
                              >{{ singleData.manager_details.name }}</span>
                              <span v-else class="error--text">N/A</span>
                            </v-chip>
                            <v-chip small v-else-if="singleData.officer_type == 'mg'">Manager</v-chip>
                          </span>
                          <span v-else class="error--text">N/A</span>
                        </div>

                        <div>
                          <b>Last Login:</b>
                          <span v-if="singleData.last_login" class="success--text">
                            {{
                            singleData.last_login.created_at
                            | moment("MMM Do, YYYY , h:mm:ss a")
                            }}
                          </span>
                          <span v-else class="error--text">N/A</span>
                        </div>
                        <div class="small">
                          <b>Created At:</b>
                          <span v-if="singleData.created_at">
                            {{
                            singleData.created_at | moment("MMMM Do, YYYY")
                            }}
                          </span>
                          <span v-else class="error--text">N/A</span>
                        </div>
                      </v-col>
                    </v-row>
                  </td>

                  <td :class="singleData.officer_type == 'mg' ? 'active_bg' : ''">
                    <span v-if="singleData.roles.length">
                      <span v-for="(item, index) in singleData.roles" :key="index">
                        <v-chip class="m-1 small">{{ item.name }}</v-chip>
                      </span>
                    </span>
                    <span v-else>
                      <span class="text-danger">Role Not Assigned</span>
                    </span>
                    <hr />
                    <span v-if="singleData.zones.length">
                      <span v-for="(item, index) in singleData.zones" :key="index">
                        <v-chip class="m-1 small">{{ item.name }}</v-chip>
                      </span>
                    </span>
                    <span v-else>
                      <span class="text-danger">Zone Not Assigned</span>
                    </span>
                  </td>

                  <td>
                    <v-btn
                      v-if="singleData.status"
                      @click="statusChange(singleData)"
                      small
                      elevation="10"
                      class="ma-1 btn_active"
                      style="pointer-events: none"
                      :style="
                        isPermit('admin-status')
                          ? 'pointer-events: auto;'
                          : 'pointer-events: none'
                      "
                    >
                      <v-icon left>mdi-check-decagram</v-icon>Active
                    </v-btn>
                    <v-btn
                      v-else
                      @click="statusChange(singleData)"
                      small
                      elevation="10"
                      class="ma-1 btn_inactive"
                      :style="
                        isPermit('admin-status')
                          ? 'pointer-events: auto;'
                          : 'pointer-events: none'
                      "
                    >
                      <v-icon left>mdi-close-octagon</v-icon>Inactive
                    </v-btn>
                    <v-btn
                      v-if="isPermit('admin-edit')"
                      @click="
                        editDataModel(singleData, 'Update Admin Info.'),
                          getAllManagers()
                      "
                      small
                      class="ma-1 btn_edit"
                    >
                      <v-icon left>mdi-pencil-outline</v-icon>Edit
                    </v-btn>
                    <v-btn
                      v-if="isPermit('admin-role-assign')"
                      @click="editRoleDialog(singleData)"
                      small
                      elevation="10"
                      class="ma-1 btn_role"
                    >
                      <v-icon left>mdi-alpha-r-circle-outline</v-icon>Role
                    </v-btn>
                    <v-btn
                      v-if="isPermit('admin-zone-assign')"
                      @click="editZoneDialog(singleData)"
                      small
                      elevation="10"
                      class="ma-1 btn_zone"
                    >
                      <v-icon left>mdi-map-marker</v-icon>Zone
                    </v-btn>

                    <v-btn
                      v-if="isPermit('admin-delete')"
                      @click="deleteDataTemp(singleData.id)"
                      small
                      class="ma-1 error"
                    >
                      <v-icon left>mdi-delete-empty-outline</v-icon>Delete
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

    <!-- Add user  -->
    <v-dialog v-model="dataModal">
      <v-card>
        <v-card-title class="justify-center">
          <v-row>
            <v-col cols="10">{{ dataModelTitle }}</v-col>
            <v-col cols="2">
              <v-btn @click="dataModal = false" small class="float-right btn_close">
                <v-icon left dark>mdi-close-octagon</v-icon>Close
              </v-btn>
            </v-col>
          </v-row>
        </v-card-title>

        <v-card-text>
          <form @submit.prevent="editmode ? updateData() : createData()">
            <v-row>
              <v-col cols="12" lg="6">
                <v-text-field
                  v-model="form.login"
                  label="Admin AD ID"
                  placeholder="Enter Admin AD ID like XXXX.YYY"
                  :error-messages="form.errors.get('login')"
                ></v-text-field>
              </v-col>
              <v-col cols="12" lg="6">
                <v-text-field
                  v-model="form.name"
                  label="Admin Name"
                  placeholder="Enter Admin Name"
                  :error-messages="form.errors.get('name')"
                ></v-text-field>
              </v-col>

              <v-col cols="12" lg="6">
                <v-select
                  :items="officerTypeOptons"
                  label="Type"
                  v-model="form.officer_type"
                  :error-messages="form.errors.get('officer_type')"
                ></v-select>
              </v-col>

              <v-col cols="12" lg="6" v-if="allManagers && form.officer_type == 'of'">
                <v-autocomplete
                  v-model="form.manager_id"
                  label="Manager for Officer"
                  placeholder="Select Manager for Officer"
                  chips
                  deletable-chips
                  small-chips
                  :items="allManagers"
                  :error-messages="form.errors.get('manager_id')"
                ></v-autocomplete>
              </v-col>

              <v-col cols="12" lg="6">
                <v-file-input
                  @change="upload_image($event)"
                  label="Choose or drop Image here..."
                  class="is-invalid"
                  accept=".jpg, .png, .jpeg"
                ></v-file-input>
                <div
                  class="small text-danger"
                  v-if="form.errors.has('image')"
                  v-html="form.errors.get('image')"
                />
              </v-col>

              <v-col cols="12" lg="6">
                <div class="mt-1">
                  <img :src="get_image()" height="100" width="100" />
                </div>
              </v-col>
            </v-row>
            <hr />
            <v-col cols="12" v-if="isPermit('Admin-Zone-Assign')">
              <label>Select Working Zone:</label>
              <v-row>
                <!-- {{ current Zones }} -->
                <v-col class="pa-0" cols="4" lg="2" v-for="(zone, index) in allZones" :key="index">
                  <v-checkbox
                    v-model="form.zone_id"
                    :label="zone.name"
                    color="indigo"
                    :value="zone.id"
                    hide-details
                  ></v-checkbox>
                </v-col>
              </v-row>
            </v-col>
            <hr />
            <v-row>
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
            </v-row>
          </form>
        </v-card-text>
      </v-card>
    </v-dialog>

    <!--Role Dilog  -->
    <v-dialog v-model="roleModelDialog" persistent fullscreen>
      <v-card>
        <v-card-title class="justify-center">
          <v-row>
            <v-col cols="10">Assign Roles</v-col>
            <v-col cols="2">
              <v-btn @click="roleModelDialog = false" small text class="float-right btn_close">
                <v-icon left dark>mdi-close-octagon</v-icon>Close
              </v-btn>
            </v-col>
          </v-row>
        </v-card-title>

        <v-card-text>
          <div class="accordion">
            <v-row>
              <v-col v-for="(items, key) in allRoles" :key="items.id" cols="12" lg="4" md="6">
                <div class="accordion-item">
                  <h2 class="accordion-header">
                    <button
                      class="accordion-button"
                      type="button"
                      data-bs-toggle="collapse"
                      aria-expanded="true"
                      :data-bs-target="`#${makeid(key)}`"
                      style="background-color: white"
                      :class="matchItem.includes(key) ? '' : 'collapsed'"
                    >{{ key }}</button>
                  </h2>
                  <div class="d-flex align-items-center flex-wrap">
                    <div v-for="item in items" :key="item.id * 3">
                      <div
                        class="collapse accordion-collapse"
                        :class="matchItem.includes(key) ? 'show' : ''"
                        :id="makeid(key)"
                      >
                        <div class="accordion-body m-2">
                          <label>
                            <input type="checkbox" v-model="currentRoles" :value="item.id" />
                            {{ item.only_text }}
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </v-col>
            </v-row>
          </div>

          <v-btn
            @click="updateUserRole()"
            block
            blockdepressed
            :loading="roleUpdating"
            class="btn_update mt-3"
          >
            <v-icon left dark>mdi-circle-edit-outline</v-icon>Update
          </v-btn>
        </v-card-text>
      </v-card>
    </v-dialog>

    <!--Zone Dilog  -->
    <v-dialog v-model="zoneModelDialog" persistent>
      <v-card>
        <v-card-title class="justify-center">
          <v-row>
            <v-col cols="10">Assign Zone</v-col>
            <v-col cols="2">
              <v-btn @click="zoneModelDialog = false" small text class="float-right btn_close">
                <v-icon left dark>mdi-close-octagon</v-icon>Close
              </v-btn>
            </v-col>
          </v-row>
        </v-card-title>

        <v-card-text>
          <v-row>
            <!-- {{ current Zones }} -->
            <v-col class="pa-0" cols="6" lg="3" v-for="(zone, index) in allZones" :key="index">
              <v-checkbox
                v-model="currentZones"
                :label="zone.name"
                color="indigo"
                :value="zone.id"
                hide-details
              ></v-checkbox>
            </v-col>

            <v-btn
              @click="updateUserZone()"
              block
              blockdepressed
              :loading="zoneUpdating"
              class="btn_update mt-3"
            >
              <v-icon left dark>mdi-circle-edit-outline</v-icon>Update
            </v-btn>
          </v-row>
        </v-card-text>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
// vform
import Form from "vform";

export default {
  data() {
    return {
      //current page url
      currentUrl: "/admin/user",

      imagePath: "/images/users/",
      imagePathSm: "/images/users/small/",
      imageMaxSize: "2111775",

      // Form
      form: new Form({
        id: "",
        login: "",
        name: "",
        officer_type: "of",
        manager_id: "",
        zone_id: [],
        role_id: [],
        image: ""
      }),

      // Role
      roleModelDialog: false,
      allRoles: [],
      currentRoles: [],
      currentRoleId: null,
      roleUpdating: false,

      // Zone
      zoneModelDialog: false,
      currentZones: [],
      currentZoneId: null,
      zoneUpdating: false,

      officerTypeOptons: [
        {
          text: "Officer",
          value: "of"
        },
        {
          text: "Manager",
          value: "mg"
        }
      ],

      allManagers: "",

      panels: [],
      matchItem: []
    };
  },

  methods: {
    // make id for collapse
    makeid(key) {
      return key.replace(/\s+/g, "");
    },
    // Edit Data Modal
    editDataModel(singleData, title = null) {
      this.dataModal = true;
      this.editmode = true;
      this.dataModelTitle = title ? title : "Update Data";
      this.resetForm();
      this.form.fill(singleData);
      // // Role
      // this.form.role_id = [];
      // singleData.roles.forEach(element => {
      //   this.form.role_id.push(element.id);
      // });
      // Zone
      this.form.zone_id = [];
      singleData.zones.forEach(element => {
        this.form.zone_id.push(element.id);
      });
    },

    // getAllManagers
    getAllManagers() {
      return axios
        .get(this.currentUrl + "/managers")
        .then(response => {
          this.allManagers = response.data;
        })
        .catch(error => {
          console.log(error);
        });
    },

    // editZoneDialog
    editZoneDialog(zoneData) {
      this.currentZoneId = zoneData.id;
      // Current role array empty
      this.currentZones = [];

      zoneData.zones.forEach(element => {
        this.currentZones.push(element.id);
      });

      // Zone modal show
      this.zoneModelDialog = true;
    },

    // update user role
    updateUserZone() {
      this.zoneUpdating = true;
      axios
        .post(this.currentUrl + "/zones_update", {
          currentId: this.currentZoneId,
          selectedData: this.currentZones
        })
        .then(response => {
          this.zoneUpdating = false;
          //console.log(response)
          // Refetch
          this.getResults(this.currentPageNumber);
          // Modal closed
          this.zoneModelDialog = false;

          Swal.fire({
            icon: response.data.icon,
            title: response.data.msg,
            customClass: "text-success"
          });
        })
        .catch(error => {
          // Modal closed
          this.zoneModelDialog = false;
          this.zoneUpdating = false;
          //Auth Check
          this.$CHECKAUTH.CheckByCode(error.response.status);
          console.log("error", error.response.status, error);
        });
    },

    // editRoleDialog
    editRoleDialog(roleData) {
      this.currentRoleId = roleData.id;
      // Current role array empty
      this.currentRoles = [];
      this.matchItem = [];

      roleData.roles.forEach(element => {
        this.currentRoles.push(element.id);

        Object.values(this.allRoles).forEach(allroles => {
          allroles.forEach(roles => {
            if (roles.id == element.id) {
              const obj = {
                test: "true"
              };
              Object.assign(roles, obj);
              this.matchItem.push(roles.key_name);
            }
          });
        });

        this.matchItem = [...new Set(this.matchItem)];
      });

      // Role modal show
      this.roleModelDialog = true;
    },

    // Get all Role
    getRoles() {
      return axios
        .get(this.currentUrl + "/roles_data")
        .then(response => {
          //console.log(response.data)
          this.allRoles = response.data;

          this.panels = Object.keys(Object.keys(response.data)).map(x =>
            parseInt(x)
          );
        })
        .catch(error => {
          console.log(error);
        });
    },

    // update user role
    updateUserRole() {
      this.roleUpdating = true;
      axios
        .post(this.currentUrl + "/roles_update", {
          currentRoleId: this.currentRoleId,
          roles: this.currentRoles
        })
        .then(response => {
          this.roleUpdating = false;
          //console.log(response)
          // Refetch
          this.getResults(this.currentPageNumber);
          // Modal closed
          this.roleModelDialog = false;

          Swal.fire({
            icon: response.data.icon,
            title: response.data.msg,
            customClass: "text-success"
          });
        })
        .catch(error => {
          // Modal closed
          this.roleModelDialog = false;
          this.roleUpdating = false;
          //Auth Check
          this.$CHECKAUTH.CheckByCode(error.response.status);
          console.log("error", error.response.status, error);
        });
    }
  },

  async created() {
    this.$Progress.start();
    // Fetch initial results
    await this.getResults();
    await this.getAllManagers();
    await this.getRoles();
    await this.getAllZoneAsync();
    this.$Progress.finish();
  }
};
</script>

<style scoped>
.active_bg {
  color: white !important;
  background-color: #009688;
}
</style>


