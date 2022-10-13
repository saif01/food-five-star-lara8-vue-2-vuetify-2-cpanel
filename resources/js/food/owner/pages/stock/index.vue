<template>
  <div class="mb-10">
    <h3>Stock</h3>
    <v-autocomplete
      label="Outlet"
      placeholder="Select Outlet"
      :items="allShop"
      v-model="cv_code"
      prepend-icon="mdi-storefront-outline"
    ></v-autocomplete>
    <v-row class="m-2">
      <table v-if="allData.length" class="table-bordered table text-right table-striped">
        <thead>
          <tr class="table_head">
            <th class="text-left">Product Name</th>
            <th>In Stock</th>
            <th>Sold</th>
            <th>Remainig</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in allData" :key="item.id">
            <td class="text-left">
              {{ item.product_name_en }}
              <span
                v-if="item.product_type_name_en"
              >({{ item.product_type_name_en }})</span>
              <img
                :src="imagePathSm + item.product_image"
                alt="Image"
                class="img-fluid"
                height="70"
                width="70"
              />
            </td>
            <td>
              <span v-if="item.in_stock">{{ item.in_stock }}</span>
              <span v-else>0</span>
            </td>
            <td>
              <span v-if="item.sold">{{ item.sold }}</span>
              <span v-else>0</span>
            </td>
            <td>
              <span v-if="item.remaining">
                <span v-if="item.remaining < 0">0</span>
                <span v-else>{{ item.remaining }}</span>
              </span>
              <span v-else>0</span>
            </td>
          </tr>
        </tbody>
      </table>
    </v-row>
    <tbl-data-loader v-if="dataLoading" />
    <data-not-available v-if="!allData.length && !dataLoading" />
  </div>
</template>

<script>
// import store from "../../js/store";
export default {
  data() {
    return {
      allShop: [],
      cv_code: "",
      currentUrl: "/owner/stock",

      imagePathSm: "/images/product/small/",
      imagePath: "/images/product/",
      imageMaxSize: "2111775",

      allData: "",

      // foodTypeKey
      foodTypeKey: 1
    };
  },
  methods: {
    getResults() {
      this.dataLoading = true;
      axios
        .get(this.currentUrl + "/index?cv_code=" + this.cv_code)
        .then(response => {
          this.allData = response.data;
          // Loading Animation
          this.dataLoading = false;
        });
    }
  },

  watch: {
    cv_code: function() {
      this.getResults();
    },

    currentLang: function(v) {
      this.foodTypeKey = this.randomKey();
    }
  },

  created() {
    this.$Progress.start();
    this.getAllShop();
    this.$Progress.finish();
  }
};
</script>

