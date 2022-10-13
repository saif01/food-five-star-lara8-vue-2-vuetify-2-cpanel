<template>
  <div>
    <v-dialog v-model="paymentDialog" max-width="400">
      <v-card>
        <v-card-title>{{ $t("payment__") }}</v-card-title>
        <v-card-text>
          <form @submit.prevent="editmode ? updateData() : createData()">
            <v-text-field
              prepend-icon="mdi-currency-bdt"
              v-model="form.payment_amount"
              class="is-invalid"
              :label="$t('payment__amount')"
              :placeholder="$t('enter__payment__amount')"
              required
              type="number"
              dense
            ></v-text-field>

            <v-file-input
              @change="upload_image($event)"
              :label="$t('image__')"
              :placeholder="$t('image__')"
              class="is-invalid"
              accept=".jpg, .png, .jpeg"
            ></v-file-input>

            <img :src="get_image_receipt()" height="100" width="100" />

            <v-btn
              block
              small
              type="submit"
              class="mt-3 btn_save"
              :loading="dataLoading"
            >
              <v-icon left>mdi-checkbox-marked-circle-plus-outline</v-icon>
              {{ $t("submit__") }}</v-btn
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

      currentUrl: "/order",

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
          // modal hide
          this.paymentDialog = false;
          this.dataLoading = false;
          this.$emit("getResults");

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