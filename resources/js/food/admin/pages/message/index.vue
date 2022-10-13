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
      >&nbsp;/
      <span class="font-weight-bold">&nbsp;Message</span>
    </div>
    <v-card elevation="0">
      <v-card-title class="justify-center">
        <v-row>
          <v-col cols="10">Message List</v-col>

          <v-col cols="2">
            <v-btn
              v-if="isPermit('message-add')"
              @click="addDataModel('Add New Message'), checkFree()"
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
            <table class="table table-bordered text-center table-striped">
              <thead>
                <tr class="table_head table_head_link">
                  <th>Title</th>
                  <th>Messsage</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="singleData in allData.data" :key="singleData.id">
                  <td>
                    <span v-if="singleData.title == 'reset-password'"
                      >Reset Password</span
                    >
                    <span v-else-if="singleData.title == 'order-confirmation'"
                      >Order Confirmation</span
                    >
                    <span v-else-if="singleData.title == 'daily-sale-owner'"
                      >Daile Sale Message (Owner)</span
                    >
                  </td>
                  <td>{{ singleData.message }}</td>

                  <td class="text-center">
                    <v-btn
                      v-if="singleData.status"
                      @click="statusChange(singleData)"
                      depressed
                      small
                      class="m-1 btn_active"
                      :style="
                        isPermit('message-status')
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
                        isPermit('message-status')
                          ? 'pointer-events: auto;'
                          : 'pointer-events: none'
                      "
                    >
                      <v-icon left>mdi-alert-circle-outline</v-icon>Inactive
                    </v-btn>

                    <v-btn
                      v-if="isPermit('message-edit')"
                      @click="editDataModel(singleData, 'Update Message')"
                      small
                      class="ma-1 btn_edit"
                    >
                      <v-icon left>mdi-pencil-outline</v-icon>Edit
                    </v-btn>

                    <v-btn
                      v-if="isPermit('message-delete')"
                      @click="deleteDataTemp(singleData.id)"
                      small
                      class="ma-1 error"
                    >
                      <v-icon left>mdi-delete-empty-outline</v-icon>Delete
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
          <form @submit.prevent="editmode ? store('update') : store('save')">
            <v-autocomplete
              :readonly="editmode ? true : false"
              v-model="form.title"
              label="Title"
              placeholder="Select Title"
              :error-messages="form.errors.get('title')"
              required
              :items="editmode ? titlesItem : newItem"
              item-text="text"
              item-value="value"
              @change="checkTitle()"
            ></v-autocomplete>

            <v-textarea
              rows="2"
              v-model="form.message"
              label="Message"
              placeholder="Enter Full Message"
              :error-messages="form.errors.get('message')"
              required
            ></v-textarea>

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
        {
          text: "Daile Sale Message (Owner)",
          value: "daily-sale-owner",
        },
        {
          text: "Test",
          value: "test-value",
        },
      ],

      newItem: [],

      selected: [],
      cursor_position: "",
      error: false,

      // Form
      form: new Form({
        id: "",
        title: "",
        message: "",
      }),
    };
  },

  methods: {
    checkTitle() {
      this.form.message = "";
      if (this.form.title == "reset-password") {
        this.form.message += "{otp} {outlet_address} {outlet_name}";
      } else if (
        this.form.title == "order-confirmation" ||
        "daily-sale-owner"
      ) {
        this.form.message += "{amount} {date} {outlet_name}";
      }
    },

    store(e) {
      if (this.form.title == "reset-password") {
        let find1 = this.form.message.search("{outlet_name}");
        let find2 = this.form.message.search("{otp}");
        let find3 = this.form.message.search("{outlet_address}");

        if (find1 < 0) {
          this.form.message += "{outlet_name}";

          if (find2 < 0) {
            this.form.message += "{otp}";

            if (find3 < 0) {
              this.form.message += "{outlet_address}";
            }
          }
        }
      } else if (
        this.form.title == "order-confirmation" ||
        "daily-sale-owner"
      ) {
        let find1 = this.form.message.search("{amount}");
        let find2 = this.form.message.search("{date}");
        let find3 = this.form.message.search("{outlet_name}");

        if (find1 < 0) {
          this.form.message += "{amount}";

          if (find2 < 0) {
            this.form.message += "{date}";

            if (find3 < 0) {
              this.form.message += "{outlet_name}";
            }
          }
        }
      }

      if (e == "save") {
        this.createData();
      } else {
        this.updateData();
      }
    },

    checkFree() {
      var allItem = [];
      if (this.allData.data) {
        this.allData.data.forEach((element) => {
          this.titlesItem.forEach((element2) => {
            if (element.title == element2.value) {
              allItem.push(element2);
            }
          });
        });
      }

      var newData = this.titlesItem.filter((object1) => {
        return !allItem.some((object2) => {
          return object1.value === object2.value;
        });
      });

      this.newItem = newData;
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



