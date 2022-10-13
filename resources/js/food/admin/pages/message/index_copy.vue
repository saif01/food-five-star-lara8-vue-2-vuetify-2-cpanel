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
      >
        Dashboard
      </router-link>

      &nbsp;/
      <span class="font-weight-bold">&nbsp;Messages </span>
    </div>
    <v-card elevation="0">
      <v-card-title class="justify-center">
        <v-row>
          <v-col cols="10">Message List</v-col>

          <v-col cols="2">
            <v-btn
              v-if="isAddAccess()"
              @click="addDataModel('Add New Message'), (selected = [])"
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

            <v-col cols="6" lg="10">
              <v-text-field
                v-model="search"
                prepend-icon="mdi-clipboard-text-search"
                dense
                placeholder="Search ..."
              ></v-text-field>
            </v-col>
          </v-row>

          <div
            class="table-responsive"
            v-if="!dataLoading && allData.data.length"
          >
            <table class="table table-bordered text-center">
              <thead>
                <tr class="bg-dark text-white table_head_link">
                  <th>Title</th>
                  <th>Messsage</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="singleData in allData.data" :key="singleData.id">
                  <td>{{ singleData.title }}</td>
                  <td>{{ singleData.message }}</td>

                  <td class="text-center">
                    <v-btn
                      v-if="singleData.status"
                      @click="statusChange(singleData)"
                      depressed
                      small
                      class="m-1 btn_active"
                    >
                      <v-icon left>mdi-check-circle-outline</v-icon>Active
                    </v-btn>
                    <v-btn
                      v-else
                      @click="statusChange(singleData)"
                      depressed
                      small
                      class="m-1 btn_inactive"
                    >
                      <v-icon left>mdi-alert-circle-outline</v-icon>Inactive
                    </v-btn>

                    <v-btn
                      v-if="isEditAccess()"
                      @click="
                        editDataModel(singleData, 'Update Message'),
                          (selected = singleData.message.match(/{(.*?)}/g))
                      "
                      small
                      class="ma-1 btn_edit"
                    >
                      <v-icon left>mdi-pencil-outline</v-icon>Edit
                    </v-btn>

                    <div small class="text-muted text-center">
                      Created By --
                      <span v-if="singleData.makby">{{
                        singleData.makby.name
                      }}</span>
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
          </div>

          <div v-else>
            <div v-if="dataLoading">
              <tbl-data-loader />
            </div>
          </div>
          <div>
            <span>Total Records: {{ totalValue }}</span>
          </div>
        </div>

        <data-not-available v-if="!totalValue && !dataLoading" />
      </v-card-text>
    </v-card>

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
            <v-autocomplete
              v-model="form.title"
              label="Title"
              placeholder="Select Title"
              :error-messages="form.errors.get('title')"
              required
              :items="titlesItem"
              item-text="text"
              item-value="value"
            ></v-autocomplete>
            <v-textarea
              rows="2"
              id="id_field"
              v-model="form.message"
              label="Message"
              placeholder="Enter Full Message"
              :error-messages="form.errors.get('message')"
              required
              @click="textPosition()"
              @keyup="textPosition()"
            ></v-textarea>

            <div class="p-1" :class="{ 'border border-danger': error == true }">
              <label class="h6">Required Parameters</label>

              <v-row>
                <v-col
                  cols="12"
                  lg="3"
                  md="6"
                  v-for="item in checkboxItem"
                  :key="item.id"
                >
                  <v-checkbox
                    v-model="selected"
                    :label="item.text"
                    :value="item.value"
                    @click="selectedParams()"
                  ></v-checkbox>
                </v-col>
              </v-row>
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
      currentUrl: "/admin/message",

      checkboxItem: [
        {
          text: "CV Code",
          value: "{cv_code}",
        },
        {
          text: "Outlet Address",
          value: "{outlet_address}",
        },
        {
          text: "Outlet Name",
          value: "{outlet_name}",
        },

        {
          text: "Date",
          value: "{date}",
        },
        {
          text: "Amount",
          value: "{amount}",
        },
        {
          text: "OTP",
          value: "{otp}",
        },
      ],

      // title item
      titlesItem: [
        {
          text: "Reset Password",
          value: "reset-password",
        },
        {
          text: "Order Confirmation",
          value: "order-confirmation",
        },
      ],

      selected: [],
      cursor_position: "",
      error: false,

      // Form
      form: new Form({
        id: "",
        title: "",
        message: "",
        final_message: "",
      }),
    };
  },

  methods: {
    selectedParams() {
      var e = this.selected;
      if (e.length) {
        this.error = false;
      }

      // insert field in specific position where cursor locate
      const item = e[e.length - 1];

      this.form.message =
        this.form.message.slice(0, this.cursor_position) +
        item +
        this.form.message.slice(this.cursor_position);
    },

    textPosition() {
      var startPos = document.getElementById("id_field").selectionStart;
      this.cursor_position = startPos;
    },

    createData() {
      if (this.selected.length > 0) {
        this.dataLodaing = true;
        this.$Progress.start();
        // request send and get response
        this.form
          .post(this.currentUrl + "/store" + "")
          .then((response) => {
            // Input field make empty
            this.resetForm();

            // modal hide
            this.dataModal = false;
            this.dataLodaing = false;

            // Refresh Tbl Data with current page
            this.getResults(this.currentPageNumber);
            this.$Progress.finish();

            Toast.fire({
              icon: response.data.icon,
              title: response.data.msg,
            });
          })
          .catch((error) => {
            //this.dataModal = false;
            this.dataLodaing = false;
            Swal.fire("Failed!", data.message, "warning");
          });
      } else {
        this.error = true;
        Swal.fire("warning", "Some fields are required", "warning");
      }
    },
  },

  created() {
    this.$Progress.start();
    // Fetch initial results
    this.getResults();
    this.$Progress.finish();
  },
};
</script>


