<template>
  <div>
    <v-dialog v-model="paymentDialog" max-width="400">
      <v-card>
        <v-card-title>Payment</v-card-title>
        <v-card-text>
          <form @submit.prevent="editmode ? updateData() : createData()">
            <v-text-field
              prepend-icon="mdi-currency-bdt"
              v-model="form.payment_amount"
              :error-messages="form.errors.get('payment_amount')"
              label="Enter Payment Amount"
              placeholder="Payment Amount"
              required
              type="number"
              dense
            ></v-text-field>

            <v-file-input
              @change="upload_image($event)"
              label="Choose payment image"
              :error-messages="form.errors.get('image')"
              accept=".jpg, .png, .jpeg"
              required
            ></v-file-input>

            <img :src="get_image_receipt()" height="100" width="100" />

            <v-btn
              block
              small
              type="submit"
              class="mt-3 btn_save"
              :loading="dataLoading"
              >Submit</v-btn
            >
          </form>
        </v-card-text>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import Form from "vform";
export default {
  props: ["currentOrderNumber"],
  data() {
    return {
      paymentDialog: true,
      dataLoading: false,

      imagePathSm: "/images/product/small/",
      imagePath: "/images/product/",
      imageMaxSize: "2111775",

      currentUrl: "/admin/orders",

      form: new Form({
        payment_amount: "",
        image: "",
        order_number: "",
      }),
    };
  },

  methods: {
    createData() {
      this.dataLoading = true;

      // request send and get response
      this.form
        .post(this.currentUrl + "/payment/store" + "")
        .then((response) => {
          console.log("response", response.data);
          // modal hide
          this.paymentDialog = false;
          this.dataLoading = false;
          this.$emit("getResults");
          this.$emit("getSkuReport");

          this.$Progress.finish();
        })
        .catch((error) => {
          //this.dataModal = false;
          this.dataLoading = false;
          Swal.fire("Failed!", error, "warning");
        });
    },
  },
  mounted() {
    this.form.order_number = this.currentOrderNumber;
  },
};
</script>