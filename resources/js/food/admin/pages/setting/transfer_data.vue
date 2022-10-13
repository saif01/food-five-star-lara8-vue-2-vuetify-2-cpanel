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
      <span class="text-muted">&nbsp;Setting&nbsp;</span>/
      <span class="font-weight-bold">&nbsp;Transfer Data</span>
    </div>
    <v-card elevation="0">
      <v-card-title>Transfer Data</v-card-title>

      <v-card-text>
        <v-col cols="12" lg="8" class="m-auto">
          <v-row>
            <v-col cols="12" lg="5" md="5">
              <v-autocomplete
                :label="`Source DB Table List (${sourceDb}) `"
                :items="sourceTableList"
                v-model="sourceTable"
                hide-details
              ></v-autocomplete>
            </v-col>
            <v-col cols="12" lg="2" md="2" class="text-center">
              <v-btn class="mt-3" fab dark small color="pink">
                <!-- @click="swap()" -->
                <v-icon dark v-if="this.$vuetify.breakpoint.name == 'xs'">mdi-swap-vertical</v-icon>
                <v-icon dark v-else>mdi-swap-horizontal</v-icon>
              </v-btn>
            </v-col>
            <v-col cols="12" lg="5" md="5">
              <v-autocomplete
                :label="`Destination DB Table List (${destinationDb}) `"
                :items="destinationTableList"
                v-model="destinationTable"
                hide-details
              ></v-autocomplete>
            </v-col>
          </v-row>

          <v-btn block depressed color="indigo" class="white--text mt-5" @click="transfer()">
            <v-icon left>mdi-database-export-outline</v-icon>Transfer
            Data
          </v-btn>
        </v-col>
      </v-card-text>
    </v-card>
  </div>
</template>

<script>
export default {
  data() {
    return {
      dbName: "",

      sourceTable: "",
      sourceTableList: [],

      destinationTable: "",
      destinationTableList: [],

      sourceDb: "5star",
      destinationDb: "5star_uat",

      currentUrl: "/admin/transfer_data"
    };
  },

  methods: {
    getTableName() {
      axios
        .get(
          this.currentUrl +
            "/table_name?source_db=" +
            this.sourceDb +
            "&destination_db=" +
            this.destinationDb
        )
        .then(response => {
          this.sourceTableList = response.data.source_db_table_name_list;
          this.destinationTableList =
            response.data.destination_db_table_name_list;
        })
        .catch(error => {
          console.log(error);
        });
    },

    swap() {
      var x = this.sourceTableList;
      var y = this.destinationTableList;
      this.sourceTableList = y;
      this.destinationTableList = x;

      var a = this.sourceDb;
      var b = this.destinationDb;
      this.sourceDb = b;
      this.destinationDb = a;
    },

    transfer() {
      axios
        .post(
          this.currentUrl +
            "/table_transfer?source_table=" +
            this.sourceTable +
            "&destination_table=" +
            this.destinationTable +
            "&source_db=" +
            this.sourceDb +
            "&destination_db=" +
            this.destinationDb
        )
        .then(response => {
          Swal.fire(response.data.title, response.data.msg, response.data.icon);
        })
        .catch(error => {
          console.log(error);
        });
    }
  },

  created() {
    this.getTableName();
  }
};
</script>

<style>
</style>