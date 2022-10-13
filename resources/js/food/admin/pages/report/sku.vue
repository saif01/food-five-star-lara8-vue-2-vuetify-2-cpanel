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
      <span class="text-muted">&nbsp;Report&nbsp;</span> /
      <span class="font-weight-bold">&nbsp;SKU</span>
    </div>
    <v-card-title class="d-flex justify-content-between">
      <h4>SKU Report</h4>
      <v-btn
        class="btn_excel"
        elevation="5"
        small
        @click="allData.length ? exportExcel('sku') : warning()"
        :loading="exportLoading"
      >
        <v-icon left>mdi-file-excel</v-icon>Export
      </v-btn>
    </v-card-title>
    <v-row>
      <v-col cols="6" lg="6" v-if="allZoneShort">
        <!-- zone -->
        <v-autocomplete
          :items="allAssignZones"
          item-text="name"
          v-model="selectZoneItemObj"
          prepend-icon="mdi-map-marker-outline"
          label="Zone"
          placeholder="Select Zone"
          return-object
        ></v-autocomplete>
      </v-col>
      <v-col cols="12" lg="6">
        <v-menu v-model="menu" min-width="auto">
          <template v-slot:activator="{ on, attrs }">
            <v-text-field
              v-model="computedDate"
              label="Date"
              prepend-icon="mdi-calendar"
              readonly
              v-bind="attrs"
              v-on="on"
            ></v-text-field>
          </template>

          <v-date-picker v-model="date" no-title scrollable>
            <v-spacer></v-spacer>
            <v-btn text color="primary" @click="menu = false">Cancel</v-btn>
          </v-date-picker>
        </v-menu>
      </v-col>
    </v-row>
    <v-card elevation="0" v-if="allData.length">
      <v-card-text>
        <div class="table-responsive" v-if="allData.length">
          <div class="table-responsive">
            <table class="table table-bordered indigo text-white">
              <thead>
                <tr>
                  <td>
                    Date:
                    <span class="font-weight-bold">{{ date | moment("dddd, MMMM Do YYYY") }}</span>
                  </td>

                  <td>
                    Quantity:
                    <span class="font-weight-bold">{{ totalQuantity }}</span>
                  </td>

                  <td>
                    Weight:
                    <span class="font-weight-bold">{{ totalWeight / 1000 }} Kg</span>
                  </td>

                  <td>
                    Amount:
                    <span class="font-weight-bold">{{ totalPrice | toCurrency }}</span>
                  </td>
                </tr>
              </thead>
            </table>
          </div>
          <table class="table table-bordered text-right">
            <thead>
              <tr class="table_head">
                <th class="text-left">Product Code</th>
                <th class="text-left">Product Name</th>
                <th>Quantity (Set)</th>
                <th>Weight (kg)</th>
                <th>Amount</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in allData" :key="item.id">
                <td class="text-left">{{ item.product_code }}</td>
                <td class="text-left">
                  {{ item.product_name }} ({{ item.product_type }})
                  <v-btn
                    v-if="isPermit('order-adjust-order-view')"
                    data-bs-toggle="tooltip"
                    data-bs-placement="top"
                    title="Adjust Order"
                    icon
                    @click="
                      $router.push({
                        name: 'adjust_order',
                        params: {
                          selectedDate: date,
                          product_id: item.product_id,
                        },
                      })
                    "
                  >
                    <v-icon right>mdi-file-document-edit-outline</v-icon>
                  </v-btn>
                </td>
                <td>{{ item.quantity }}</td>
                <td>
                  <span v-if="item.weight / 1000">{{ item.weight / 1000 }}</span>
                  <span v-else class="error--text">N/A</span>
                </td>
                <td>{{ item.price | toCurrency }}</td>
              </tr>
            </tbody>
            <tfoot>
              <tr class="table_bottom font-weight-bold">
                <td class="text-right" colspan="2">Subtotal:</td>
                <td>{{ totalQuantity }}</td>
                <td>{{ totalWeight / 1000 }}</td>
                <td>{{ totalPrice | toCurrency }}</td>
              </tr>
            </tfoot>
          </table>
        </div>
      </v-card-text>
    </v-card>

    <div v-else>
      <tbl-data-loader v-if="dataLoading" />
      <data-not-available v-if="!dataLoading" />
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      selectZoneItemObj: { id: "All", name: "All" },
      cv_code: null,
      date: this.$moment().format("YYYY-MM-DD"),

      currentUrl: "/admin/report/sku",

      allData: "",
      totalWeight: "",
      totalPrice: "",
      totalQuantity: ""
    };
  },

  methods: {
    getResults() {
      if (this.selectZoneItemObj) {
        // Assign Zone ID
        this.selectZoneItem = this.selectZoneItemObj.id;
        this.dataLoading = true;
        axios
          .get(
            this.currentUrl +
              "/index?zone=" +
              this.selectZoneItem +
              "&start_date=" +
              this.date
          )
          .then(response => {
            this.allData = response.data.allData;
            this.totalWeight = response.data.totalWeight;
            this.totalPrice = response.data.totalPrice;
            this.totalQuantity = response.data.totalQuantity;

            this.dataLoading = false;

            // Excel Title
            this.title =
              "SKU Report " +
              this.selectZoneItemObj.name +
              " (" +
              this.start_date +
              ")";
          });
      }
    }
  },

  computed: {
    computedDate() {
      return this.formatDate(this.date);
    }
  },

  watch: {
    selectZoneItemObj: function() {
      this.getResults();
    },

    date: function() {
      this.getResults();
    }
  },

  mounted() {
    this.getResults();
  },

  created() {
    this.$Progress.start();
    this.dataLoading = true;
    this.getAllAssignedZones();
    this.$Progress.finish();
  }
};
</script>