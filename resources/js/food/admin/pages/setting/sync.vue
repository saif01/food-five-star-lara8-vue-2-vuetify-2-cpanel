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
      <span class="font-weight-bold">&nbsp;Sync</span>
    </div>

    <v-row>
      <v-col cols="12" lg="8" class="mx-auto">
        <v-card>
          <v-card-title class="justify-content-between"
            >Order Synchronization</v-card-title
          >

          <v-card-text>
            <v-col cols="12">
              <v-row>
                <v-col cols="12" lg="6">
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

                    <v-date-picker
                      v-model="start_date"
                      no-title
                      scrollable
                    ></v-date-picker>
                  </v-menu>
                </v-col>
                <v-col cols="12" lg="6">
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

                    <v-date-picker
                      v-model="end_date"
                      no-title
                      scrollable
                    ></v-date-picker>
                  </v-menu>
                </v-col>
                <v-col cols="12">
                  <v-btn
                    block
                    color="pink"
                    class="text-white"
                    @click="sync()"
                    :loading="loading"
                    v-if="isPermit('data-sync-order')"
                  >
                    <v-icon left>mdi-cached</v-icon>Start Order Sync

                    <span slot="loader">
                      <v-icon
                        left
                        class="text-white"
                        :class="trigger ? 'animate' : ''"
                        >mdi-cached</v-icon
                      >
                    </span>
                  </v-btn>
                </v-col>
              </v-row>
            </v-col>
          </v-card-text>
        </v-card>
      </v-col>
      <v-col cols="12" lg="8" class="mx-auto">
        <v-card>
          <v-card-title class="justify-content-between"
            >Price Synchronization</v-card-title
          >

          <v-card-text>
            <v-btn
              block
              color="indigo"
              class="text-white"
              @click="syncPrice()"
              :loading="loadingPrice"
              v-if="isPermit('data-sync-price')"
            >
              <v-icon left>mdi-cached</v-icon>Start Price Sync

              <span slot="loader">
                <v-icon
                  left
                  class="text-white"
                  :class="triggerProduct ? 'animate' : ''"
                  >mdi-cached</v-icon
                >
              </span>
            </v-btn>
          </v-card-text>
        </v-card>
      </v-col>

      <v-col cols="12" lg="8" class="mx-auto">
        <v-card>
          <v-card-title class="justify-content-between"
            >Outlet Synchronization</v-card-title
          >

          <v-card-text>
            <v-btn
              block
              color="purple"
              class="text-white"
              @click="syncOutlet()"
              :loading="loadingOutlet"
              v-if="isPermit('data-sync-outlet')"
            >
              <v-icon left>mdi-cached</v-icon>Start Outlet Sync

              <span slot="loader">
                <v-icon
                  left
                  class="text-white"
                  :class="triggerOutlet ? 'animate' : ''"
                  >mdi-cached</v-icon
                >
              </span>
            </v-btn>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>

<script>
// vform
import Form from "vform";
export default {
  data() {
    return {
      // start_date: this.$moment()
      //   .subtract(1, "M")
      //   .format("YYYY-MM-DD"),
      start_date: this.$moment().subtract(15, "days").format("YYYY-MM-DD"),

      end_date: this.$moment().format("YYYY-MM-DD"),

      trigger: false,
      triggerProduct: false,
      triggerOutlet: false,

      // Form
      form: new Form({
        id: "",
        title: "",
        time: [],
      }),

      newItem: [],
      loading: false,
      loadingPrice: false,
      loadingOutlet: false,
    };
  },

  methods: {
    // Store In DB
    sync() {
      // check start date is small or bigger than end date
      let dateTimeIsBefore = this.$moment(this.start_date).isBefore(
        this.end_date
      );

      // check if same is both date
      let dateTimeIsSame = this.$moment(this.start_date).isSame(this.end_date);

      if (dateTimeIsBefore || dateTimeIsSame) {
        this.trigger = true;
        this.loading = true;
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
            this.loading = false;
          })
          .catch((error) => {
            this.trigger = false;
            this.loading = false;
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

    syncPrice() {
      this.triggerProduct = true;
      this.loadingPrice = true;
      axios
        .get("/api/smartsoft_product_price_sync")
        .then((response) => {
          console.log(response);
          Swal.fire(response.data.title, response.data.msg, response.data.icon);

          this.triggerProduct = false;
          this.loadingPrice = false;
        })
        .catch((error) => {
          this.triggerProduct = false;
          this.loadingPrice = false;
          console.log(error);
        });
    },

    syncOutlet() {
      this.triggerOutlet = true;
      this.loadingOutlet = true;
      axios
        .get("/api/smartsoft_outlet_sync")
        .then((response) => {
          console.log(response);
          Swal.fire(response.data.title, response.data.msg, response.data.icon);

          this.triggerOutlet = false;
          this.loadingOutlet = false;
        })
        .catch((error) => {
          this.triggerOutlet = false;
          this.loadingOutlet = false;
          console.log(error);
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

  created() {
    this.$Progress.start();

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
