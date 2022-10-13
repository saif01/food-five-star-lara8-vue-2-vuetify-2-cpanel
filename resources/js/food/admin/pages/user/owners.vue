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
      >&nbsp;/ <span class="text-muted">&nbsp;Users&nbsp;</span> /
      <span class="font-weight-bold">&nbsp;Owners</span>
    </div>
    <v-card elevation="0">
      <v-card-title class="justify-center">
        <v-row>
          <v-col cols="8">
            Owner List&nbsp;
            <v-chip v-if="totalOnline" color="success" @click="toogleStatus()">
              Total Online: &nbsp;
              <b>{{ totalOnline }}</b>
            </v-chip>
            <v-chip v-else>Total Online: 0</v-chip>
          </v-col>
          <v-col cols="4">
            <v-btn
              v-if="isPermit('owner-add')"
              @click="addDataModel('Add New Owner')"
              class="btn_add float-right"
              elevation="20"
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
            <v-col cols="3" lg="2">
              <v-select
                prepend-icon="mdi-database-eye"
                :items="tblItemNumberShow"
                v-model="paginate"
                label="Show:"
                small
              ></v-select>
            </v-col>

            <v-col cols="9" lg="3">
              <!-- search_field -->
              <v-select
                v-model="search_field"
                label="Search By:"
                prepend-icon="mdi-text-search-variant"
                :items="searchByFields"
                small
              ></v-select>
            </v-col>

            <v-col cols="12" lg="7">
              <v-text-field
                prepend-icon="mdi-clipboard-text-search"
                v-model="search"
                label="Search:"
                placeholder="Search ..."
              ></v-text-field>
            </v-col>
          </v-row>

          <div
            class="table-responsive"
            v-if="!dataLoading && allData.data.length"
          >
            <table class="table table-bordered text-center table-striped">
              <thead>
                <tr class="table_head table_head_link">
                  <th>
                    <a href="#" @click.prevent="change_sort('id')">Image</a>
                    <span v-if="sort_direction == 'desc' && sort_field == 'id'"
                      >&uarr;</span
                    >
                    <span v-if="sort_direction == 'asc' && sort_field == 'id'"
                      >&darr;</span
                    >
                  </th>
                  <th>
                    <a href="#" @click.prevent="change_sort('owner_login')"
                      >Login</a
                    >
                    <span
                      v-if="
                        sort_direction == 'desc' && sort_field == 'owner_login'
                      "
                      >&uarr;</span
                    >
                    <span
                      v-if="
                        sort_direction == 'asc' && sort_field == 'owner_login'
                      "
                      >&darr;</span
                    >
                  </th>
                  <th>
                    <a href="#" @click.prevent="change_sort('name')">Name</a>
                    <span
                      v-if="sort_direction == 'desc' && sort_field == 'name'"
                      >&uarr;</span
                    >
                    <span v-if="sort_direction == 'asc' && sort_field == 'name'"
                      >&darr;</span
                    >
                  </th>
                  <th>CV Code || Outlet Name || Address</th>
                  <th>Action</th>
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
                        <img
                          :src="imagePathSm + singleData.image"
                          alt="Image"
                        />
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
                        <v-img
                          src="/all-assets/common/img/no-image.png"
                        ></v-img>
                      </v-avatar>
                    </v-badge>
                  </td>
                  <td>
                    <span v-if="singleData.owner_login">
                      {{ singleData.owner_login }}
                    </span>
                    <span v-else class="text-danger">N/A</span>
                  </td>
                  <td>
                    <span v-if="singleData.name">{{ singleData.name }}</span>
                    <span v-else class="text-danger">N/A</span>
                  </td>
                  <td>
                    <span
                      v-if="
                        singleData.cv_code_outlets &&
                        singleData.cv_code_outlets.length
                      "
                    >
                      <v-chip
                        v-for="(
                          cvCodeItem, index
                        ) in singleData.cv_code_outlets"
                        :key="index"
                        outlined
                        small
                        class="success m-1"
                      >
                        {{
                          cvCodeItem.cv_code +
                          " || " +
                          cvCodeItem.shop_name +
                          " || " +
                          cvCodeItem.shop_address
                        }}
                      </v-chip>
                      <!-- <v-btn   > </v-btn> -->
                    </span>
                    <span v-else class="text-danger">N/A</span>
                  </td>
                  <td>
                    <v-btn
                      v-if="singleData.status"
                      @click="statusChange(singleData)"
                      small
                      elevation="10"
                      class="ma-1 btn_active"
                      :style="
                        isPermit('owner-status')
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
                        isPermit('owner-status')
                          ? 'pointer-events: auto;'
                          : 'pointer-events: none'
                      "
                    >
                      <v-icon left>mdi-close-octagon</v-icon>Inactive
                    </v-btn>

                    <v-btn
                      v-if="isPermit('owner-edit')"
                      @click="editDataModel(singleData, 'Update Owner Info.')"
                      small
                      elevation="10"
                      class="ma-1 btn_edit"
                    >
                      <v-icon left>mdi-circle-edit-outline</v-icon>Edit
                    </v-btn>

                    <v-btn
                      v-if="isPermit('owner-delete')"
                      @click="deleteDataTemp(singleData.id)"
                      small
                      class="ma-1 error"
                    >
                      <v-icon left>mdi-delete-empty-outline</v-icon>Delete
                    </v-btn>
                    <div small class="text-muted text-center">
                      Created By --
                      <span v-if="singleData.makby">
                        {{ singleData.makby.name }}
                      </span>
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

    <!-- Dialog -->
    <v-dialog max-width="900" v-model="dataModal">
      <v-card>
        <v-card-title class="justify-center">
          <v-row>
            <v-col cols="10">{{ dataModelTitle }}</v-col>
            <v-col cols="2">
              <v-btn
                @click="dataModal = false"
                small
                class="float-right btn_close"
              >
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
                  v-model="form.owner_login"
                  label="Owner Login"
                  prepend-icon="mdi-cellphone-basic"
                  :rules="numberRules"
                  placeholder="Enter Owner Contact Number"
                  required
                  counter="11"
                  :error-messages="form.errors.get('owner_login')"
                ></v-text-field>
              </v-col>
              <v-col cols="12" lg="6">
                <v-text-field
                  v-model="form.name"
                  label="Owner Name"
                  prepend-icon="mdi-card-text"
                  required
                  placeholder="Enter Owner Name"
                  :error-messages="form.errors.get('name')"
                ></v-text-field>
              </v-col>

              <v-col cols="12" lg="12" v-if="allOutletsOptions">
                <v-autocomplete
                  v-model="form.cv_code_owner"
                  label="Outlet or CV"
                  placeholder="Select Outlet or CV"
                  prepend-icon="mdi-account-key"
                  chips
                  clearable
                  deletable-chips
                  multiple
                  small-chips
                  :items="allOutletsOptions"
                  :error-messages="form.errors.get('cv_code_owner')"
                ></v-autocomplete>
              </v-col>

              <v-col cols="12" lg="6">
                <v-file-input
                  @change="upload_image($event)"
                  label="Choose or drop Image here..."
                  class="is-invalid"
                  accept=".jpg, .png, .jpeg"
                  :error-messages="form.errors.get('image')"
                ></v-file-input>
              </v-col>

              <v-col cols="12" lg="6">
                <v-text-field
                  v-model="form.password"
                  label="Owner Login Password"
                  prepend-icon="mdi-lock"
                  placeholder="Enter Owner Login Password"
                  counter="20"
                  :error-messages="form.errors.get('password')"
                ></v-text-field>
              </v-col>
            </v-row>
            <div class="mt-1 justify-content-center">
              <img :src="get_image()" height="100" width="100" />
            </div>

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
      currentUrl: "/admin/user/owner",

      imagePath: "/images/users/",
      imagePathSm: "/images/users/small/",
      imageMaxSize: "2111775",

      numberRules: [
        //v => !!v || 'Phone Number is required',
        (v) => v.length == 11 || "Phone Number must be 11 characters",
        (v) => /01+/.test(v) || "Phone Number must be valid",
      ],

      searchByFields: [
        {
          text: "All",
          value: "All",
        },
        {
          text: "CV Code",
          value: "cv_code",
        },
        {
          text: "Name",
          value: "name",
        },
        {
          text: "Contact",
          value: "personal_contact",
        },
      ],

      // Form
      form: new Form({
        id: "",
        owner_login: "",
        name: "",
        personal_contact: "",
        password: "cp12345@",
        cv_code_owner: [],
        image: "",
      }),

      roleModelDialog: false,
      allRoles: {},
      currentRoles: [],
      currentRoleId: null,
      roleUpdating: false,
    };
  },

  methods: {
    //Edit Data Modal
    editDataModel(singleData, title = null) {
      this.resetForm();
      this.form.fill(singleData);

      this.form.cv_code_owner = singleData.cv_code_array;
      //this.form.password = singleData.password

      // get cv code which are not assigned cv code
      axios
        .get(this.currentUrl + "/get_cvcode/" + singleData.id)
        .then((response) => {
          this.allOutletsOptions = response.data;

          this.editmode = true;
          this.dataModelTitle = title ? title : "Update Data";
          this.dataModal = true;
        });
    },

    // All Outlets
    notAssignOutlets() {
      return axios
        .get(this.currentUrl + "/not_assign_outlets")
        .then((response) => {
          //console.log(response)
          this.allOutletsOptions = response.data;
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },

  async created() {
    this.$Progress.start();
    // Fetch initial results
    await this.getResults(this.currentPageNumber);
    await this.notAssignOutlets();
    this.$Progress.finish();
  },
};
</script>
