<template>
  <div class="mb-10">
    <h3>{{ $t("stock__list") }}</h3>
    <v-row class="m-2" v-if="!dataLoading">
      <table
        v-if="allData.length"
        class="table table-bordered text-right table-striped"
      >
        <thead>
          <tr class="table_head">
            <th class="text-left">{{ $t("stock__product__name") }}</th>
            <th>{{ $t("stock__have") }}</th>
            <th>{{ $t("stock__sold") }}</th>
            <th>{{ $t("stock__remaining") }}</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in allData" :key="item.id">
            <td class="text-left">
              <span v-if="currentLang == 'bangla'">
                {{ item.product_name_bn }}
                <span v-if="item.product_type_name_bn"
                  >({{ item.product_type_name_bn }})</span
                >
              </span>
              <span v-else>
                {{ item.product_name_en }}
                <span v-if="item.product_type_name_en"
                  >({{ item.product_type_name_en }})</span
                >
              </span>
              <img
                :src="imagePathSm + item.product_image"
                alt="Image"
                class="img-fluid"
                height="70"
                width="70"
              />
            </td>
            <td>
              <span v-if="item.in_stock">
                <!-- <span v-if="currentLang == 'bangla'">
                  {{ englishTobangla(item.in_stock) }}
                </span> -->
                <span>
                  {{ item.in_stock }}
                </span>
              </span>
            </td>
            <td>
              <!-- <span v-if="currentLang == 'bangla'">
                <span v-if="item.sold">{{ englishTobangla(item.sold) }}</span>
                <span v-else>{{ englishTobangla("0") }}</span>
              </span> -->
              <span>
                <span v-if="item.sold">{{ item.sold }}</span>
                <span v-else>0</span>
              </span>
            </td>
            <td>
              <span v-if="item.remaining">
                <!-- <span v-if="currentLang == 'bangla'">
                  <span v-if="item.remaining < 0">
                    {{ englishTobangla("0") }}
                  </span>
                  <span v-else>
                    {{ englishTobangla(item.remaining) }}
                  </span>
                </span> -->
                <span>
                  <span v-if="item.remaining < 0"> 0 </span>
                  <span v-else>
                    {{ item.remaining }}
                  </span>
                </span>
              </span>
            </td>
          </tr>
        </tbody>
      </table>
    </v-row>

    <tbl-data-loader v-else />

    <div v-if="!allData.length && !dataLoading" class="text-center">
      <data-not-available />

      <v-btn to="/new-order" class="btn_order">
        <v-icon left>mdi-plus-thick</v-icon>{{ $t("place__order") }}</v-btn
      >
    </div>
  </div>
</template>

<script>
// import store from "../../js/store";
export default {
  data() {
    return {
      overlay: false,
      currentUrl: "/stock",

      imagePathSm: "/images/product/small/",
      imagePath: "/images/product/",
      imageMaxSize: "2111775",

      overlay: false,

      typesData: [],

      // sort_field
      sort_field: "All",

      // checkId
      checkId: [],

      // foodTypeKey
      foodTypeKey: 1,
    };
  },
  methods: {
    getResults() {
      this.dataLoading = true;
      axios.get(this.currentUrl + "/index").then((response) => {
        this.allData = response.data;
        // Loading Animation
        this.dataLoading = false;
      });
    },
  },

  watch: {
    sort_field: function () {
      this.getResults();
    },

    currentLang: function (v) {
      this.foodTypeKey = this.randomKey();
    },

    cart: function () {
      if (this.cart.length == "") {
        this.purchaseItem = [];
        this.getResults();
      }
    },
  },

  mounted() {
    // if statement only execute while modify
    if (this.cart.length) {
      this.purchaseItem.push(...this.cart);
    }
  },

  created() {
    this.$Progress.start();
    this.getResults();
    this.$Progress.finish();
  },
};
</script>

