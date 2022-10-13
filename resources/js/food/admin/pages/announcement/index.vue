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
      <span class="font-weight-bold">&nbsp;Announcement</span>
    </div>
    <v-card elevation="0">
      <v-card-title class="justify-center">
        <v-row>
          <v-col cols="10">Announcement List</v-col>
          <v-col cols="2">
            <v-btn
              v-if="isPermit('announcement-add')"
              @click="addDataModel('Add New Announcement')"
              elevation="10"
              small
              class="float-right btn_add"
            >
              <v-icon left>mdi-plus-circle-outline</v-icon>Add
            </v-btn>
          </v-col>
        </v-row>
      </v-card-title>

      <v-card-text>
        <div>
          <v-row>
            <v-col cols="3">
              <!-- Show -->
              <v-select
                prepend-icon="mdi-database-eye"
                v-model="paginate"
                label="Show:"
                :items="tblItemNumberShow"
                small
              ></v-select>
            </v-col>

            <v-col cols="9">
              <v-text-field
                prepend-icon="mdi-clipboard-text-search"
                v-model="search"
                label="Search:"
                placeholder="Search Input..."
              ></v-text-field>
            </v-col>
          </v-row>

          <div class="table-responsive" v-if="!dataLoading && allData.data">
            <table class="table table-bordered text-center table-striped">
              <thead>
                <tr class="table_head table_head_link">
                  <th>Image</th>
                  <th>
                    Announcement Duration
                    <a href="#" @click.prevent="change_sort('start')">Start</a>
                    <span v-if="sort_direction == 'desc' && sort_field == 'start'">&uarr;</span>
                    <span v-if="sort_direction == 'asc' && sort_field == 'start'">&darr;</span>
                    /
                    <a href="#" @click.prevent="change_sort('end')">End</a>
                    <span v-if="sort_direction == 'desc' && sort_field == 'end'">&uarr;</span>
                    <span v-if="sort_direction == 'asc' && sort_field == 'end'">&darr;</span>
                  </th>
                  <th>Allow For</th>
                  <th>Action</th>
                </tr>
              </thead>

              <tbody>
                <tr v-for="singleData in allData.data" :key="singleData.id">
                  <td>
                    <span v-if="singleData.image">
                      <img
                        :src="imagePathSm + singleData.image"
                        alt="Image"
                        height="100"
                        width="100"
                      />
                    </span>
                    <span v-else class="text-danger">No Image</span>
                  </td>
                  <!-- <td>{{ singleData.message }}</td> -->
                  <td>
                    {{ singleData.start | moment("MMMM Do YYYY") }}
                    <b>- to -</b>
                    {{ singleData.end | moment("MMMM Do YYYY") }}
                  </td>
                  <td>
                    <span v-if="singleData.allow">{{ singleData.allow }}</span>
                    <span v-else class="error--text">N/A</span>
                  </td>

                  <td>
                    <v-btn
                      v-if="singleData.status"
                      @click="statusChange(singleData)"
                      depressed
                      small
                      class="m-1 btn_active"
                      :style="
                        isPermit('announcement-status')
                          ? 'pointer-events: auto;'
                          : 'pointer-events: none'
                      "
                    >
                      <v-icon left>mdi-check-circle-outline</v-icon>Active
                    </v-btn>
                    <v-btn
                      v-else
                      @click="statusChange(singleData)"
                      depressed
                      small
                      class="m-1 btn_inactive"
                      :style="
                        isPermit('announcement-status')
                          ? 'pointer-events: auto;'
                          : 'pointer-events: none'
                      "
                    >
                      <v-icon left>mdi-alert-circle-outline</v-icon>Inactive
                    </v-btn>

                    <v-btn
                      v-if="isPermit('announcement-edit')"
                      @click="
                        editDataModel(singleData, 'Update Announcement Info.')
                      "
                      depressed
                      small
                      class="btn_edit"
                    >
                      <v-icon left>mdi-pencil</v-icon>Edit
                    </v-btn>

                    <v-btn
                      v-if="isPermit('announcement-delete')"
                      @click="deleteDataTemp(singleData.id)"
                      small
                      class="ma-1 error"
                    >
                      <v-icon left>mdi-delete-empty-outline</v-icon>Delete
                    </v-btn>
                    <div small class="text-muted text-center">
                      Created By --
                      <span v-if="singleData.makby">
                        {{
                        singleData.makby.name
                        }}
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

    <!-- Modal -->
    <v-dialog v-model="dataModal" max-width="600px" persistent>
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
          <v-form v-model="valid" ref="form">
            <form @submit.prevent="editmode ? updateData() : createData()">
              <v-row>
                <v-col cols="12" lg="6">
                  <!-- <v-text-field
                    type="date"
                    label="Start Date"
                    v-model="form.start"
                    required
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

                    <v-date-picker v-model="form.start" no-title scrollable>
                      <v-spacer></v-spacer>
                      <v-btn text color="primary" @click="menu = false">Cancel</v-btn>
                    </v-date-picker>
                  </v-menu>
                </v-col>

                <v-col cols="12" lg="6">
                  <!-- <v-text-field
                    type="date"
                    label="End Date"
                    v-model="form.end"
                    required
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

                    <v-date-picker v-model="form.end" no-title scrollable>
                      <v-spacer></v-spacer>
                      <v-btn text color="primary" @click="menu2 = false">Cancel</v-btn>
                    </v-date-picker>
                  </v-menu>
                </v-col>

                <v-col cols="12" lg="6">
                  <v-file-input
                    @change="upload_image($event)"
                    label="Image"
                    placeholder="Choose or drop Image here..."
                    class="is-invalid"
                    accept=".jpg, .png, .jpeg"
                    :error-messages="form.errors.get('image')"
                  ></v-file-input>
                </v-col>
                <v-col cols="12" lg="6">
                  <div class="mt-1">
                    <img :src="get_image()" height="100" width="100" />
                  </div>
                </v-col>

                <v-col cols="12">
                  <v-autocomplete
                    label="Allow For"
                    placeholder="Select Allow For"
                    :items="allowList"
                    v-model="form.allow"
                    required
                  ></v-autocomplete>
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

export default {
  data() {
    return {
      // v-form
      valid: false,
      // dialog
      dataModal: false,

      // loader
      announcementLoader: false,

      announcementRules: [v => !!v || "This field is required!"],

      //current page url

      currentUrl: "/admin/announcement",

      allowList: [
        {
          text: "All",
          value: "all"
        },

        {
          text: "Operators",
          value: "operator"
        },

        {
          text: "Owners",
          value: "owner"
        }
      ],

      // Form
      form: new Form({
        id: "",
        image: "",
        message: "",
        start: "",
        end: "",
        allow: ""
      }),

      imagePathSm: "/images/announcement/small/",
      imagePath: "/images/announcement/",
      imageMaxSize: "2111775"
    };
  },

  computed: {
    computedStartDate() {
      return this.formatDate(this.form.start);
    },

    computedEndDate() {
      return this.formatDate(this.form.end);
    }
  },
  mounted() {
    this.$Progress.start();
    // Fetch initial results
    this.getResults();
    this.$Progress.finish();
  }
};
</script>
