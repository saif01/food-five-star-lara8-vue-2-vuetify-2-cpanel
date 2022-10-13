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
      <span class="font-weight-bold">&nbsp;Login Log</span>
    </div>
    <v-card elevation="0">
      <v-card-title
        >All Login Logs&nbsp;
        <v-chip v-if="totalOnline" color="success">
          Total Online: &nbsp; <b> {{ totalOnline }}</b>
        </v-chip>
        <v-chip v-else>Total Online: 0</v-chip>
      </v-card-title>
      <v-card-text>
        <v-row>
          <v-col cols="3" lg="2">
            <!-- Show -->
            <v-select
              prepend-icon="mdi-database-eye"
              v-model="paginate"
              label="Show:"
              :items="tblItemNumberShow"
              small
            ></v-select>
          </v-col>

          <v-col cols="9" lg="2">
            <v-select
              v-model="short_by"
              label="Status By:"
              prepend-icon="mdi-format-list-bulleted"
              :items="sortOptions"
              clearable
            ></v-select>
          </v-col>

          <v-col cols="12" lg="2">
            <!-- <v-text-field
              v-model="start_date"
              type="date"
              label="Start Date"
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

              <v-date-picker v-model="start_date" no-title scrollable>
                <v-spacer></v-spacer>
                <v-btn text color="primary" @click="menu = false">Cancel</v-btn>
              </v-date-picker>
            </v-menu>
          </v-col>
          <v-col cols="12" lg="2">
            <!-- <v-text-field
              v-model="end_date"
              type="date"
              label="End Date"
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

              <v-date-picker v-model="end_date" no-title scrollable>
                <v-spacer></v-spacer>
                <v-btn text color="primary" @click="menu2 = false"
                  >Cancel</v-btn
                >
              </v-date-picker>
            </v-menu>
          </v-col>

          <v-col cols="12" lg="4">
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
            <thead class="table_head table_head_link">
              <tr>
                <th>
                  <a href="#" @click.prevent="change_sort('id')">ID</a>
                  <span v-if="sort_direction == 'desc' && sort_field == 'id'"
                    >&uarr;</span
                  >
                  <span v-if="sort_direction == 'asc' && sort_field == 'id'"
                    >&darr;</span
                  >
                </th>
                <th>Status</th>
                <th>
                  <a href="#" @click.prevent="change_sort('login_id')"
                    >Login ID</a
                  >
                  <span
                    v-if="sort_direction == 'desc' && sort_field == 'login_id'"
                    >&uarr;</span
                  >
                  <span
                    v-if="sort_direction == 'asc' && sort_field == 'login_id'"
                    >&darr;</span
                  >
                </th>
                <th>
                  <a href="#" @click.prevent="change_sort('os')">OS</a>
                  <span v-if="sort_direction == 'desc' && sort_field == 'os'"
                    >&uarr;</span
                  >
                  <span v-if="sort_direction == 'asc' && sort_field == 'os'"
                    >&darr;</span
                  >
                </th>
                <th>
                  <a href="#" @click.prevent="change_sort('browser')"
                    >Browser</a
                  >
                  <span
                    v-if="sort_direction == 'desc' && sort_field == 'browser'"
                    >&uarr;</span
                  >
                  <span
                    v-if="sort_direction == 'asc' && sort_field == 'browser'"
                    >&darr;</span
                  >
                </th>
                <th>
                  <a href="#" @click.prevent="change_sort('device')">Device</a>
                  <span
                    v-if="sort_direction == 'desc' && sort_field == 'device'"
                    >&uarr;</span
                  >
                  <span v-if="sort_direction == 'asc' && sort_field == 'device'"
                    >&darr;</span
                  >
                </th>
                <th>
                  <a href="#" @click.prevent="change_sort('created_at')"
                    >Login At</a
                  >
                  <span
                    v-if="
                      sort_direction == 'desc' && sort_field == 'created_at'
                    "
                    >&uarr;</span
                  >
                  <span
                    v-if="sort_direction == 'asc' && sort_field == 'created_at'"
                    >&darr;</span
                  >
                </th>
                <th>
                  <a href="#" @click.prevent="change_sort('created_at')"
                    >Logout At</a
                  >
                  <span
                    v-if="
                      sort_direction == 'desc' && sort_field == 'created_at'
                    "
                    >&uarr;</span
                  >
                  <span
                    v-if="sort_direction == 'asc' && sort_field == 'created_at'"
                    >&darr;</span
                  >
                </th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="singleData in allData.data" :key="singleData.id">
                <td>{{ singleData.id }}</td>
                <td>
                  <span v-if="singleData.status == 1" class="text-success"
                    >Successful</span
                  >
                  <span v-if="singleData.status == 3" class="text-danger"
                    >Not authorized AD</span
                  >
                  <span v-if="singleData.status == 4" class="text-danger"
                    >Operator Login Error</span
                  >
                  <span v-if="singleData.status == 5" class="text-danger"
                    >Owner Login Error</span
                  >
                </td>
                <td>{{ singleData.login_id }}</td>
                <td>{{ singleData.os }}</td>
                <td>{{ singleData.browser }}</td>
                <td>{{ singleData.device }}</td>
                <td>
                  <span v-if="singleData.created_at">
                    {{
                      singleData.created_at | moment("MMM Do, YYYY , h:mm:ss a")
                    }}
                  </span>
                  <span v-else class="error--text">N/A</span>
                </td>
                <td>
                  <span v-if="singleData.updated_at != singleData.created_at">
                    {{
                      singleData.updated_at | moment("MMM Do, YYYY , h:mm:ss a")
                    }}
                  </span>
                  <span v-else class="error--text">N/A</span>
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

        <data-not-available v-if="!totalValue && !dataLoading" />
      </v-card-text>
    </v-card>
  </div>
</template>

<script>
export default {
  data() {
    return {
      v_total: null,
      //current page url
      currentUrl: "/admin/user/logs",

      sortOptions: [
        {
          text: "Successful login",
          value: "s",
        },
        {
          text: "AD login Error",
          value: "ae",
        },
        {
          text: "Owner login Error",
          value: "ow",
        },
        {
          text: "Operator login Error",
          value: "op",
        },
      ],

      // datepicker
      menu: "",
      menu2: "",
      short_by: "",
      start_date: this.$moment().subtract(1, "M").format("YYYY-MM-DD"),
      end_date: this.$moment().format("YYYY-MM-DD"),
    };
  },

  methods: {
    // Get table data
    getResults(page = 1) {
      this.dataLoading = true;
      axios
        .get(
          this.currentUrl +
            "/index?page=" +
            page +
            "&paginate=" +
            this.paginate +
            "&search=" +
            this.search +
            "&sort_direction=" +
            this.sort_direction +
            "&sort_field=" +
            this.sort_field +
            "&search_field=" +
            this.search_field +
            "&short_by=" +
            this.short_by +
            "&start=" +
            this.start_date +
            "&end=" +
            this.end_date
        )
        .then((response) => {
          //console.log(response.data.data);
          //console.log(response.data.from, response.data.to);
          this.allData = response.data;
          this.totalValue = response.data.total;
          this.dataShowFrom = response.data.from;
          this.dataShowTo = response.data.to;
          this.currentPageNumber = response.data.current_page;
          this.v_total = response.data.last_page;
          this.totalOnline = response.data.total_online;
          // Loading Animation
          this.dataLoading = false;
        });
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

  watch: {
    short_by: function () {
      this.$Progress.start();
      this.getResults();
      this.$Progress.finish();
    },

    start_date: function () {
      this.getResults();
    },
    end_date: function () {
      this.getResults();
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
