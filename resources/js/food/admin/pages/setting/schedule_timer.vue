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
      >&nbsp;/ <span class="text-muted">&nbsp;Setting&nbsp;</span>/
      <span class="font-weight-bold">&nbsp;Schedule Timer</span>
    </div>
    <v-card elevation="0">
      <v-card-title class="justify-content-between">
        Schedule Timer
        <v-btn
          @click="addDataModel('Select Schedule Time'), checkFree()"
          class="btn_add float-right"
          small
        >
          <v-icon left>mdi-plus-circle-outline</v-icon>Add
        </v-btn>
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
                  <th>Time</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="singleData in allData.data" :key="singleData.id">
                  <td>
                    <v-chip>{{ showTitleText(singleData.title) }}</v-chip>
                    <br />
                    <small class="text-muted">
                      Key:
                      <v-chip x-small>{{ singleData.title }}</v-chip>
                    </small>
                  </td>
                  <td>
                    <span v-if="singleData.time">
                      <span
                        v-for="(timeVal, index) in singleData.time_explode"
                        :key="index"
                      >
                        <v-chip class="m-1">{{ showAMPM(timeVal) }}</v-chip>
                      </span>
                    </span>
                  </td>

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
                      @click="editDataModel(singleData, 'Update Schedule Time')"
                      small
                      class="ma-1 btn_edit"
                    >
                      <v-icon left>mdi-pencil-outline</v-icon>Edit
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

      <v-dialog v-model="dataModal">
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
                <v-col
                  class="pa-0"
                  cols="4"
                  lg="2"
                  v-for="item in checkboxItem"
                  :key="item.id"
                >
                  <v-checkbox
                    v-model="form.time"
                    :label="item.text"
                    :value="item.value"
                  ></v-checkbox>
                </v-col>
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
import HourlyTime from "./js/hourly_time_options";
import AmPmShow from "./js/am_pm_show";

import TitleItems from "./js/title_options";
import ShowTilte from "./js/title_show";

// vform
import Form from "vform";
export default {
  data() {
    return {
      start_date: this.$moment().subtract(1, "M").format("YYYY-MM-DD"),
      end_date: this.$moment().format("YYYY-MM-DD"),

      trigger: false,

      currentUrl: "/admin/schedule_timer",

      // title item
      ...TitleItems,

      // Form
      form: new Form({
        id: "",
        title: "",
        time: [],
      }),

      ...HourlyTime,

      newItem: [],
    };
  },

  methods: {
    // AmPmShow
    ...AmPmShow,

    // ShowTilte
    ...ShowTilte,

    editDataModel(singleData, title = null) {
      this.dataModal = true;
      this.editmode = true;
      this.dataModelTitle = title ? title : "Update Data";
      this.resetForm();
      this.form.time = [];
      this.form.id = singleData.id;
      this.form.title = singleData.title;
      // console.log(singleData.time.split(","));
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