<template>
  <div>
    <v-card-text class="pt-0">
      <div :class="screenWidth < 425 ? 'mobile_spacing' : 'bottom_spacing'">
        <v-card v-for="(item, index) in cart" :key="item.index" elevation="0">
          <v-card-text class="mb-3 p-0">
            <v-row>
              <v-col cols="4" class="pr-0">
                <v-badge avatar bordered overlap v-if="item.free" color="transparent">
                  <template v-slot:badge>
                    <v-avatar>
                      <v-img :src="imagePathSm + item.free.image"></v-img>
                    </v-avatar>
                  </template>

                  <v-tooltip top>
                    <template v-slot:activator="{ on, attrs }">
                      <v-avatar size="40" v-bind="attrs" v-on="on">
                        <v-img :src="imagePathSm + item.image" contain></v-img>
                      </v-avatar>
                    </template>
                    <span>One &nbsp;{{ item.free.name_en }}&nbsp;free</span>
                  </v-tooltip>
                </v-badge>
                <v-avatar size="40" v-else>
                  <v-img :src="imagePathSm + item.image" contain></v-img>
                </v-avatar>

                <div v-if="item.price_offer">{{ item.price_offer | toCurrency }}</div>
                <div v-else>{{ item.price | toCurrency }}</div>
              </v-col>
              <v-col cols="8">
                <div class="d-flex flex-column">
                  <div>
                    <div class="d-flex justify-content-between">
                      <div v-if="currentLang == 'bangla'">
                        {{ item.name_bn }}
                        <span
                          v-if="item.type"
                        >({{ item.type.name_bn.split(" ")[0] }})</span>
                      </div>
                      <div v-else>
                        {{ item.name_en }}
                        <span
                          v-if="item.type"
                        >({{ item.type.name_en.split(" ")[0] }})</span>
                      </div>
                      <div>
                        <v-btn icon class="btn_delete" small @click="removeProduct(item)">
                          <v-icon small>mdi-delete-outline</v-icon>
                        </v-btn>
                      </div>
                    </div>
                  </div>

                  <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center border border-success rounded-pill">
                      <v-btn v-if="item.quantity == 0" icon small>
                        <v-icon>mdi-minus</v-icon>
                      </v-btn>
                      <v-btn
                        small
                        v-else
                        icon
                        @click="
                          (trigger = randomKey()),
                            item.quantity--,
                            checkQuantity(item)
                        "
                      >
                        <v-icon>mdi-minus</v-icon>
                      </v-btn>
                      <span class="mx-3">
                        <span>{{ item.quantity }}</span>
                      </span>
                      <v-btn small icon @click="trigger++, item.quantity++, checkQuantity(item)">
                        <v-icon>mdi-plus</v-icon>
                      </v-btn>
                    </div>
                    <div class="font-weight-bold">
                      <span v-if="item.price_offer">
                        {{
                        sumSubtotal(index, item.quantity, item.price_offer)
                        }}
                      </span>
                      <span v-else>{{ sumSubtotal(index, item.quantity, item.price) }}</span>

                      <span>{{ item.subtotal | toCurrency }}</span>
                    </div>
                  </div>
                </div>
              </v-col>
            </v-row>
          </v-card-text>
        </v-card>
      </div>
    </v-card-text>
    <v-footer fixed style="padding-bottom: 4rem">
      <div class="w-100">
        <div class="d-flex justify-content-between h4">
          <div>{{ $t("total__") }}:</div>
          <div>
            <span>{{ sumField("subtotal") | toCurrency }}</span>
          </div>
        </div>

        <v-btn @click="openCustomerDetailsDialog()" class="rounded-pill btn_save" block large>
          <v-icon left>mdi-checkbox-marked-circle-plus-outline</v-icon>
          {{ $t("proceed__to__checkout") }}
        </v-btn>
      </div>
    </v-footer>

    <!-- customer details -->
    <v-dialog
      v-model="customerDetailsDialog"
      persistent
      max-width="900"
      :fullscreen="$vuetify.breakpoint.name == 'sm' || screenWidth < 425"
    >
      <v-card>
        <v-card-title>
          <v-row>
            <v-col cols="10" lg="11">
              <h3>{{ $t("customer__details") }}</h3>
            </v-col>
            <v-col cols="2" lg="1">
              <v-btn
                small
                @click="
                  (customerDetailsDialog = false),
                    $store.commit('setCdetails', [])
                "
                class="float-right btn_close"
              >
                <v-icon left>mdi-close-circle-outline</v-icon>
                {{ $t("close__") }}
              </v-btn>
            </v-col>
          </v-row>
        </v-card-title>

        <v-card-text>
          <v-form v-model="valid" ref="form">
            <form @submit.prevent="form.sales_numb ? update() : store()">
              <v-row>
                <v-col cols="12" md="6" lg="6">
                  <label>{{ $t("subtotal__") }}</label>
                  <v-text-field
                    v-model="total"
                    readonly
                    solo
                    large
                    prepend-inner-icon="mdi-currency-bdt"
                  ></v-text-field>
                </v-col>
                <v-col cols="12" md="6" lg="6">
                  <label>
                    {{ $t("rcv__amount") }}
                    <span class="text-danger">*</span>
                  </label>
                  <v-text-field
                    type="number"
                    v-model="form.rcv_money"
                    solo
                    @input="validate()"
                    :placeholder="$t('enter__rcv__amount')"
                    :rules="firstValidation"
                    required
                    persistent-hint
                    large
                    prepend-inner-icon="mdi-currency-bdt"
                  ></v-text-field>
                </v-col>
              </v-row>

              <div class="h3 text-center">
                <span v-if="this.currentLang == 'bangla'">ফেরত পাবে:</span>
                <span v-else>Return Amount:</span>

                <span
                  v-if="remaining_amount"
                  class="h2 font-weight-black"
                >{{ remaining_amount | toCurrency }}</span>
                <span v-else class="h2">0</span>
              </div>

              <v-row class="mb-8">
                <v-col cols="12" md="6" lg="6">
                  <v-text-field
                    v-model="form.customer_name"
                    :label="$t('customer__name')"
                    :placeholder="$t('customer__name__placeholder')"
                  ></v-text-field>
                </v-col>
                <v-col cols="12" md="6" lg="6">
                  <v-text-field
                    type="number"
                    v-model="form.customer_number"
                    :label="$t('customer__number')"
                    :placeholder="$t('customer__number__placeholder')"
                    :rules="numberRules"
                  ></v-text-field>
                </v-col>
                <v-col cols="12" md="6" lg="6">
                  <div>
                    <label>{{ $t("choose__type") }}</label>
                  </div>

                  <v-chip-group v-model="form.customer_type" color="error" group>
                    <v-chip small class="mr-2" value="Male">
                      {{
                      $t("male__")
                      }}
                    </v-chip>

                    <v-chip small class="mx-2" value="Female">
                      {{
                      $t("female__")
                      }}
                    </v-chip>

                    <v-chip small class="mx-2" value="Other">
                      {{
                      $t("other__")
                      }}
                    </v-chip>
                  </v-chip-group>
                </v-col>
                <v-col cols="12" md="6" lg="6">
                  <div>
                    <label>{{ $t("choose__age") }}</label>
                  </div>
                  <v-chip-group v-model="form.customer_age" color="deep-purple accent-3" group>
                    <v-chip small class="mr-2" value="14-18">
                      {{
                      $t("age__1")
                      }}
                    </v-chip>

                    <v-chip small class="mx-2" value="19-22">
                      {{
                      $t("age__2")
                      }}
                    </v-chip>

                    <v-chip small class="mx-2" value="23-30">
                      {{
                      $t("age__3")
                      }}
                    </v-chip>

                    <!-- <v-chip small class="mr-2" value="28-31">{{ $t("age__4") }}</v-chip> -->

                    <v-chip small class="mx-2" value="30+">
                      {{
                      $t("age__5")
                      }}
                    </v-chip>
                  </v-chip-group>
                </v-col>
              </v-row>

              <v-footer
                :fixed="
                  $vuetify.breakpoint.name == 'xl' ||
                  $vuetify.breakpoint.name == 'lg'
                    ? false
                    : true
                "
                padless
                :style="screenWidth < 425 ? 'margin-bottom:1rem' : ''"
              >
                <v-btn
                  v-if="form.sales_numb"
                  type="submit"
                  class="btn_update"
                  :loading="customerBtn"
                  block
                  large
                >
                  <v-icon left>mdi-checkbox-marked-circle-plus-outline</v-icon>
                  {{ $t("update__btn") }}
                </v-btn>

                <v-btn v-else type="submit" class="btn_save" :loading="customerBtn" block large>
                  <v-icon left>mdi-checkbox-marked-circle-plus-outline</v-icon>
                  {{ $t("submit__") }}
                </v-btn>
              </v-footer>
            </form>
          </v-form>
        </v-card-text>
      </v-card>
    </v-dialog>
  </div>
</template>


<script>
import Form from "vform";

export default {
  data() {
    return {
      valid: false,

      imagePathSm: "/images/product/small/",
      imagePath: "/images/product/",
      imageMaxSize: "2111775",

      trigger: 1,

      total: "",

      numberRules: [
        //v => !!v || 'Phone Number is required',
        v => v.length == 11 || "Phone Number must be 11 characters",
        v => /01+/.test(v) || "Phone Number must be valid"
      ],

      // customerBtn
      customerBtn: false,
      currentUrl: "/sale",

      // customerDetailsDialog
      customerDetailsDialog: false,

      form: new Form({
        id: "",
        customer_name: "",
        customer_number: "",
        customer_age: "",
        customer_type: "",
        // extra for manupulate
        sales_numb: "",
        rcv_money: ""
      }),

      isConfirmed: "",

      // calculateDialog: false,
      // calculateDialogKey: 1,
      // return_amount: "",
      // receive_amount: "",

      remaining_amount: "",
      // validation
      firstValidation: [true],
      screenWidth: screen.width
    };
  },
  methods: {
    // remove
    removeProduct(item) {
      // const removeIndex = this.cart.findIndex((obj) => obj.id === item.id);
      // this.cart.splice(removeIndex, 1);
      this.$emit("addToCart", item);
    },
    // calculate subtotal for each item
    sumSubtotal(index, quantity, price) {
      this.cart[index].subtotal = quantity * price;
    },

    // calculate sum of all cart item
    sumField(subtotal) {
      this.total = this.cart.reduce((a, b) => a + (b[subtotal] || 0), 0);
      return this.total;
    },

    // place order
    store() {
      if (this.total - this.form.rcv_money <= 0) {
        this.customerBtn = true;
        axios
          .post(this.currentUrl + "/store", {
            cart: this.cart,
            customer_info: this.form
          })
          .then(response => {
            this.customerBtn = false;
            if (this.currentLang == "bangla") {
              // Toast.fire({
              //   icon: "success",
              //   title: "অর্ডার সম্পন্ন হয়েছে",
              // });

              Swal.fire({
                title: "অর্ডার সম্পন্ন হয়েছে!",
                icon: "success",
                html:
                  "<h3> সর্বমোট: <b>" +
                  this.$options.filters.toCurrency(this.total) +
                  "</b> <br/> টাকা গ্রহণের পরিমাণ: <b>" +
                  this.$options.filters.toCurrency(this.form.rcv_money) +
                  "</b> <br/> টাকা ফেরতের পরিমাণ: <b>" +
                  this.$options.filters.toCurrency(this.remaining_amount) +
                  "</b> </h3>",
                showConfirmButton: false
              });
            } else {
              Swal.fire({
                title: "Order place successfully!",
                icon: "success",
                html:
                  "<h3> Subtotal: <b>" +
                  this.$options.filters.toCurrency(this.total) +
                  "</b> <br/> Receive Amount: <b>" +
                  this.$options.filters.toCurrency(this.form.rcv_money) +
                  "</b> <br/> Return Amount: <b>" +
                  this.$options.filters.toCurrency(this.remaining_amount) +
                  "</b> </h3>",
                showConfirmButton: false
              });
            }

            this.customerDetailsDialog = false;
            this.sheet = false;
            this.form.reset();
            this.$store.commit("setCart", []);
            this.storeStatus = 1;
          })
          .catch(error => {
            this.customerBtn = false;
            this.customerDetailsDialog = false;
            // if (this.currentLang == "bangla") {
            //   Swal.fire("দুঃখিত!", "অর্ডার সম্পন্ন হয়নি", "warning");
            // } else {
            //   Swal.fire("Failed!", data.message, "warning");
            // }
            //Auth Check
            this.$CHECKAUTH.CheckByCode(error.response.status);
            console.log("error", error.response.status, error);
          });
      }
    },

    // update
    update() {
      if (this.total - this.form.rcv_money <= 0) {
        this.customerBtn = true;
        axios
          .put(this.currentUrl + "/update", {
            cart: this.cart,
            customer_info: this.form
          })
          .then(response => {
            this.customerBtn = false;
            if (this.currentLang == "bangla") {
              Swal.fire({
                title: "অর্ডার সম্পন্ন হয়েছে!",
                icon: "success",
                html:
                  "<h3> সর্বমোট: <b>" +
                  this.$options.filters.toCurrency(this.total) +
                  "</b> <br/> টাকা গ্রহণের পরিমাণ: <b>" +
                  this.$options.filters.toCurrency(this.form.rcv_money) +
                  "</b> <br/> টাকা ফেরতের পরিমাণ: <b>" +
                  this.$options.filters.toCurrency(this.remaining_amount) +
                  "</b> </h3>",
                showConfirmButton: false
              });
            } else {
              Swal.fire({
                title: "Order update successfully!",
                icon: "success",
                html:
                  "<h3> Subtotal: <b>" +
                  this.$options.filters.toCurrency(this.total) +
                  "</b> <br/> Receive Amount: <b>" +
                  this.$options.filters.toCurrency(this.form.rcv_money) +
                  "</b> <br/> Return Amount: <b>" +
                  this.$options.filters.toCurrency(this.remaining_amount) +
                  "</b> </h3>",
                showConfirmButton: false
              });
            }

            this.customerDetailsDialog = false;
            this.sheet = false;
            this.form.reset();
            this.$store.commit("setCart", []);
            this.$store.commit("setCdetails", []);
            this.purchaseItem = [];
          })
          .catch(error => {
            this.customerBtn = false;
            this.customerDetailsDialog = false;
            // if (this.currentLang == "bangla") {
            //   Swal.fire("দুঃখিত!", "অর্ডার সম্পন্ন হয়নি", "warning");
            // } else {
            //   Swal.fire("Failed!", data.message, "warning");
            // }
            //Auth Check
            this.$CHECKAUTH.CheckByCode(error.response.status);
            console.log("error", error.response.status, error);
          });
      }
    },

    openCustomerDetailsDialog() {
      // set details to customer form field
      if (this.cDetails.length != 0) {
        this.form.fill(this.cDetails);

        if (!this.cDetails.customer_number) {
          this.form.customer_number = "";
        }
      }

      this.customerDetailsDialog = true;
      this.calculateDialog = true;
      this.calculateDialogKey++;
    },

    checkQuantity(data) {
      if (data.quantity == 0) {
        if (this.currentLang == "bangla") {
          Swal.fire({
            text: "আপনি কি এই আইটেম সিরিয়ে ফেলতে চান?",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "হ্যাঁ, সরিয়ে ফেলুন!"
          });
        } else {
          Swal.fire({
            text: "Do you want to remove this item?",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, remove it!"
          }).then(result => {
            if (result.isConfirmed) {
              this.$emit("addToCart", data);
              //this.removeProduct(data);
            }
            if (result.isDismissed) {
              data.quantity = 1;
            }
          });
        }
      }
    },

    validate() {
      if (this.form.rcv_money != null) {
        const valid = this.total - this.form.rcv_money <= 0;
        if (valid) {
          this.firstValidation = [true];
          this.remaining_amount = this.form.rcv_money - this.total;
          return;
        } else {
          this.firstValidation = ["Must be greater than or equal Subtotal "];
          this.remaining_amount = this.form.rcv_money - this.total;
        }
      }
    }
  },

  watch: {
    rcv_money() {
      if (this.total <= this.form.rcv_money) {
        this.remaining_amount = this.form.rcv_money - this.total;
      } else {
        this.remaining_amount = 0;
      }
    },

    cart() {
      if (this.cart.length == 0) {
        this.$emit("sheetResponse");
      }
    }
  }
};
</script>

<style scoped>
.cart_container1 {
  height: 100%;
  overflow: hidden;
}
.bottom_spacing {
  margin-bottom: 9rem !important;
}
.mobile_spacing {
  margin-bottom: 9rem !important;
}

/* .cart_container2 {
  overflow-y: auto;
  height: auto;
  max-height: 54vh;
  width: 100%;
}

.cart_container2::-webkit-scrollbar {
  height: 0px !important;
}
.cart_container2::-webkit-scrollbar-thumb {
  background: transparent;
} */

.total-font-size {
  font-size: 16px !important;
}

.v-data-table__wrapper > table > tbody > tr:hover {
  background: inherit !important;
}
</style>
