<template>
  <div>
    <v-dialog v-model="currentOrderDetailsDialog" max-width="800">
      <v-card>
        <v-card-title>Order Details</v-card-title>
        <v-card-text>
          <div class="table-responsive">
            <table class="table table-bordered text-right">
              <colgroup>
                <col span="4" />
                <col class="table_right" />
              </colgroup>
              <thead>
                <tr class="table_head">
                  <th class="text-left">Product</th>
                  <th>Price (Incl. Vat)</th>
                  <th>Quantity (Set)</th>
                  <th>Weight (kg)</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="item in orderDetails" :key="item.id">
                  <td class="text-left">
                    <span v-if="item.product_details">
                      {{ item.product_details.product_code }} --
                      {{ item.product_details.type }}
                    </span>
                  </td>
                  <td>{{ item.price | toCurrency }}</td>
                  <td>{{ item.quantity }}</td>
                  <td>
                    <span
                      v-if="
                        item.product_details.weight &&
                        item.product_details.weight_type
                      "
                    >
                      {{ item.product_details.weight }} ({{
                        item.product_details.weight_type
                      }})
                    </span>
                    <span v-else class="error--text">N/A</span>
                  </td>

                  <td class="text-white">
                    {{ (item.quantity * item.price) | toCurrency }}
                  </td>
                </tr>
                <tr class="table_bottom">
                  <td class="font-weight-bold" colspan="2">Subtotal:</td>
                  <td>{{ subQuantity() }}</td>
                  <td>{{ subWeight() }}</td>
                  <td>{{ subTotal() | toCurrency }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </v-card-text>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
export default {
  props: ["orderDetails"],

  data() {
    return {
      currentOrderDetailsDialog: true,
    };
  },

  methods: {
    // subtotal
    subTotal() {
      const price = [];
      this.orderDetails.forEach((element) => {
        price.push(element.quantity * element.price);
      });

      const initialValue = 0;
      const sumWithInitial = price.reduce(
        (previousValue, currentValue) => previousValue + currentValue,
        initialValue
      );

      return sumWithInitial;
    },

    subWeight() {
      const weight = [];
      this.orderDetails.forEach((element) => {
        if (element.product_details.weight_type == "kg") {
          weight.push(
            element.quantity * (element.product_details.weight * 1000)
          );
        } else {
          weight.push(element.quantity * element.product_details.weight);
        }
      });

      const initialWeight = 0;
      const sumWithInitialWeight = weight.reduce(
        (previousValue, currentValue) => previousValue + currentValue,
        initialWeight
      );

      return sumWithInitialWeight / 1000;
    },

    subQuantity() {
      const quantity = [];
      this.orderDetails.forEach((element) => {
        quantity.push(element.quantity);
      });

      const initialValue = 0;
      const sumWithInitial = quantity.reduce(
        (previousValue, currentValue) => previousValue + currentValue,
        initialValue
      );

      return sumWithInitial;
    },
  },
};
</script>

<style scoped>
/* table */
/* .order__details table tr td,
table tr th {
  padding: 0.5rem;
} */
</style>