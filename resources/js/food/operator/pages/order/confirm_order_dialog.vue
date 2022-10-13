<template>
  <div>
    <v-dialog v-model="placeOrderDialog" max-width="800">
      <v-card>
        <v-card-title>
          <v-row>
            <v-col cols="10">
              <div>{{ $t("confirm__order") }}</div>
            </v-col>
            <v-col cols="2">
              <v-btn
                @click="placeOrderDialog = false"
                small
                class="float-right btn_close"
              >
                <v-icon left dark>mdi-close-octagon</v-icon>
                {{ $t("close__") }}
              </v-btn>
            </v-col>
          </v-row>
        </v-card-title>
        <v-card-text class="order__details">
          <div class="table-responsive">
            <table class="table table-bordered text-right">
              <colgroup>
                <col span="4" />
                <col class="table_right" />
              </colgroup>
              <thead>
                <tr class="table_head">
                  <th class="text-left">{{ $t("name__product") }}</th>
                  <th>{{ $t("price__") }} (Incl. Vat)</th>
                  <th>{{ $t("quantity__") }} ({{ $t("set__") }})</th>
                  <th>{{ $t("weight__") }} ({{ $t("kg__") }})</th>
                  <th>{{ $t("total__") }} ({{ $t("bdt__") }})</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="item in orderList" :key="item.id">
                  <td class="text-left">
                    <span v-if="currentLang == 'bangla'">{{
                      item.type_bn
                    }}</span>
                    <span v-else>{{ item.type }}</span>
                  </td>

                  <td>
                    <!-- <span v-if="currentLang == 'bangla'">{{
                      item.tp | toCurrencyBL
                    }}</span> -->
                    <span>{{ item.tp | toCurrency }}</span>
                  </td>

                  <td>
                    <!-- <span v-if="currentLang == 'bangla'">{{
                      englishTobangla(item.quantity_order)
                    }}</span> -->
                    <span>{{ item.quantity_order }}</span>
                  </td>

                  <td>
                    <span v-if="item.weight && item.weight_type">
                      <!-- <span v-if="currentLang == 'bangla'">
                        {{ englishTobangla(item.weight) }}
                        <span v-if="item.weight == 'kg'"> (কেজি) </span>
                        <span v-else> (গ্রাম) </span>
                      </span> -->
                      <span> {{ item.weight }} ({{ item.weight_type }}) </span>
                    </span>
                    <span v-else class="error--text">N/A</span>
                  </td>

                  <td class="text-white">
                    <span>
                      <!-- <span v-if="currentLang == 'bangla'">{{
                        (item.quantity_order * item.tp) | toCurrencyBL
                      }}</span> -->
                      <span>{{
                        (item.quantity_order * item.tp) | toCurrency
                      }}</span>
                    </span>
                  </td>
                </tr>
                <tr class="table_bottom">
                  <td></td>
                  <td class="font-weight-bold">{{ $t("subtotal__") }}:</td>
                  <td>
                    <!-- <span v-if="currentLang == 'bangla'">{{
                      englishTobangla(subQuantity())
                    }}</span> -->
                    <span>{{ subQuantity() }}</span>
                  </td>
                  <td>
                    <!-- <span v-if="currentLang == 'bangla'">{{
                      englishTobangla(subWeight())
                    }}</span> -->
                    <span>{{ subWeight() }}</span>
                  </td>
                  <td>
                    <!-- <span v-if="currentLang == 'bangla'">{{
                      subTotal() | toCurrencyBL
                    }}</span> -->
                    <span>{{ subTotal() | toCurrency }}</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <v-btn
            v-if="orderList.length"
            @click="from ? placeOrderModify() : placeOrder()"
            block
            small
            class="btn_save"
          >
            <v-icon left>mdi-checkbox-marked-circle-plus-outline</v-icon>
            {{ $t("place__order") }}
          </v-btn>
        </v-card-text>
      </v-card>

      <!-- overlay loading -->
      <v-overlay :value="orderLoading">
        <v-progress-circular size="64"></v-progress-circular>
      </v-overlay>
    </v-dialog>
  </div>
</template>

<script>
import orderJs from "./js/order";
export default {
  props: ["order_list", "date", "from", "ordernumber"],
  data() {
    return {
      orderLoading: false,
      placeOrderDialog: true,
      currentUrl: "/order",
      orderList: [],
      selected_date: "",
      order_number: "",
    };
  },

  methods: {
    // all order related methods
    ...orderJs,
  },

  mounted() {
    this.orderList = this.order_list;
    this.selected_date = this.date;
    this.order_number = this.ordernumber;
  },
};
</script>