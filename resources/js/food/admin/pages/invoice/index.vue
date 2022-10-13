<template>
  <div>
    <v-card elevation="0">
      <v-card-title class="justify-content-between"
        >Invoice Sync
        <v-btn
          v-if="isAddAccess()"
          @click="addDataModel('Add New Schedule'), checkFree()"
          class="btn_add float-right"
          small
        >
          <v-icon left>mdi-plus-circle-outline</v-icon>Add
        </v-btn>
      </v-card-title>

      <v-card-text>
        <v-row>
          <v-col cols="12" lg="4">
            <v-menu v-model="menu" min-width="auto">
              <template v-slot:activator="{ on, attrs }">
                <v-text-field
                  v-model="computedStartDate"
                  label="Start Date"
                  prepend-icon="mdi-calendar"
                  readonly
                  v-bind="attrs"
                  v-on="on"
                  dense
                ></v-text-field>
              </template>

              <v-date-picker v-model="start_date" no-title scrollable>
              </v-date-picker>
            </v-menu>
          </v-col>
          <v-col cols="12" lg="4">
            <v-menu v-model="menu2" min-width="auto">
              <template v-slot:activator="{ on, attrs }">
                <v-text-field
                  v-model="computedEndDate"
                  label="End Date"
                  prepend-icon="mdi-calendar"
                  readonly
                  v-bind="attrs"
                  v-on="on"
                  dense
                ></v-text-field>
              </template>

              <v-date-picker v-model="end_date" no-title scrollable>
              </v-date-picker>
            </v-menu>
          </v-col>
          <v-col cols="12" lg="4">
            <v-btn block color="pink" class="text-white" @click="sync()">
              <v-icon left :class="trigger ? 'animate' : ''">mdi-cached</v-icon>
              Start Sync</v-btn
            >
          </v-col>
        </v-row>
      </v-card-text>

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
                <tr class="table_head table_head_link">
                  <th>Title</th>
                  <th>Time</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="singleData in allData.data" :key="singleData.id">
                  <td>{{ singleData.title }}</td>
                  <td>{{ singleData.time }}</td>

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
                        editDataModel(singleData, 'Update Schedule Info.')
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
                :readonly="editmode ? true : false"
                v-model="form.title"
                label="Title"
                placeholder="Select Title"
                :error-messages="form.errors.get('title')"
                required
                :items="editmode ? titlesItem : newItem"
                item-text="text"
                item-value="value"
              ></v-autocomplete>

              <label style="font-size: 18px">Select Time</label>

              <div class="d-flex flex-wrap justify-content-between">
                <div v-for="item in checkboxItem" :key="item.id">
                  <v-checkbox
                    v-model="form.time"
                    :label="item.text"
                    :value="item.value"
                  ></v-checkbox>
                </div>
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
    </v-card>
  </div>
</template>

<script>
// vform
import Form from "vform";
export default {
  data() {
    return {
      start_date: this.$moment().subtract(1, "M").format("YYYY-MM-DD"),
      end_date: this.$moment().format("YYYY-MM-DD"),

      trigger: false,

      currentUrl: "/admin/db/sync",

      // title item
      titlesItem: [
        {
          text: "Invoice Sync",
          value: "invoice-sync",
        },
      ],

      // Form
      form: new Form({
        id: "",
        title: "",
        time: [],
        time_explode: [],
      }),

      checkboxItem: [
        {
          text: "1:00 PM",
          value: "1:00",
        },
        {
          text: "2:00 PM",
          value: "2:00",
        },
        {
          text: "3:00 PM",
          value: "3:00",
        },
        {
          text: "4:00 PM",
          value: "4:00",
        },
        {
          text: "5:00 PM",
          value: "5:00",
        },
        {
          text: "6:00 PM",
          value: "6:00",
        },
        {
          text: "7:00 PM",
          value: "7:00",
        },
        {
          text: "8:00 PM",
          value: "8:00",
        },
        {
          text: "9:00 PM",
          value: "9:00",
        },
        {
          text: "10:00 PM",
          value: "10:00",
        },
        {
          text: "11:00 PM",
          value: "11:00",
        },
        {
          text: "12:00 AM",
          value: "12:00",
        },
        {
          text: "1:00 AM",
          value: "13:00",
        },
        {
          text: "2:00 AM",
          value: "14:00",
        },
        {
          text: "3:00 AM",
          value: "15:00",
        },
        {
          text: "4:00 AM",
          value: "16:00",
        },
        {
          text: "5:00 AM",
          value: "17:00",
        },
        {
          text: "6:00 AM",
          value: "18:00",
        },
        {
          text: "7:00 AM",
          value: "19:00",
        },
        {
          text: "8:00 AM",
          value: "20:00",
        },
        {
          text: "9:00 AM",
          value: "21:00",
        },
        {
          text: "10:00 AM",
          value: "22:00",
        },
        {
          text: "11:00 AM",
          value: "23:00",
        },
        {
          text: "12:00 PM",
          value: "24:00",
        },
      ],

      newItem: [],
    };
  },

  methods: {
    sync() {
      // check start date is small or bigger than end date
      let dateTimeIsBefore = this.$moment(this.start_date).isBefore(
        this.end_date
      );

      // check if same is both date
      let dateTimeIsSame = this.$moment(this.start_date).isSame(this.end_date);

      if (dateTimeIsBefore || dateTimeIsSame) {
        this.trigger = true;
        axios
          .get(
            "/api/smartsoft_invoice_sync/" +
              this.start_date +
              "/" +
              this.end_date
          )
          .then((response) => {
            console.log(response);
            Swal.fire(
              response.data.title,
              response.data.msg,
              response.data.icon
            );
            this.trigger = false;
          })
          .catch((error) => {
            this.trigger = false;
            console.log(error);
          });
      } else {
        Swal.fire(
          "Warning",
          "START DATE must be lower than END DATE",
          "warning"
        );
      }
    },

    editDataModel(singleData, title = null) {
      this.dataModal = true;
      this.editmode = true;
      this.dataModelTitle = title ? title : "Update Data";
      this.resetForm();
      this.form.fill(singleData);
      this.form.time = singleData.time_explode;
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

  computed: {
    computedStartDate() {
      return this.formatDate(this.start_date);
    },

    computedEndDate() {
      return this.formatDate(this.end_date);
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

<style>
.animate {
  animation: 0.8s ease-in-out infinite spin;
}

@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}
</style>