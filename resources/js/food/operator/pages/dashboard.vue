<template>
  <div>
    <div class="d-flex">
      <v-text-field
        class="mx-1 mb-3"
        flat
        dense
        hide-details
        :label="$t('search__food')"
        v-model="search"
        prepend-inner-icon="mdi-magnify"
        solo-inverted
      >
      </v-text-field>

      <v-menu offset-y transition="scale-transition" :nudge-width="500">
        <template v-slot:activator="{ on, attrs }">
          <v-btn
            @click="quickEditSale()"
            v-bind="attrs"
            v-on="on"
            :loading="modifyOverlay"
            color="indigo lighten-3"
            class="white--text"
          >
            <v-icon left>mdi-clipboard-text-search-outline</v-icon>
            {{ $t("latest__") }}
          </v-btn>
        </template>

        <v-card dark>
          <v-card-text>
            <table
              class="table text-white table-bordered mb-0"
              v-if="quickSearchData.length > 0 && !quickDataLoading"
            >
              <thead class="text-center">
                <tr>
                  <th>{{ $t("sales__time") }}</th>
                  <th>{{ $t("customer__details") }}</th>
                  <th>{{ $t("action__") }}</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="singleData in quickSearchData"
                  :key="singleData.id + 'quickSearch'"
                >
                  <td class="text-center">
                    {{ singleData.sales_date | moment("h:mm a") }}
                  </td>
                  <td>
                    {{ $t("number__") }}:
                    <span v-if="singleData.customer_number">
                      <span>{{ singleData.customer_number }}</span>
                    </span>
                    <span v-else class="error--text">N/A</span> <br />
                    {{ $t("price__") }}:
                    {{ singleData.total_price | toCurrency }}
                  </td>
                  <td class="text-center">
                    <v-btn
                      :loading="modifyOverlay"
                      small
                      class="text-center btn_edit"
                      @click="getModifyOrder(singleData, 'index')"
                    >
                      <v-icon left>mdi-pen</v-icon
                      >{{ $t("modify__order") }}</v-btn
                    >
                  </td>
                </tr>
              </tbody>
            </table>
            <div v-else>
              <tbl-data-loader v-if="quickDataLoading" />
            </div>
            <data-not-available
              v-if="quickSearchData.length <= 0 && !quickDataLoading"
            />
          </v-card-text>
        </v-card>
      </v-menu>
    </div>

    <div>
      <!-- tab start -->
      <ul class="nav nav-tabs itemOneLine">
        <li
          class="nav-item"
          v-for="items in typesData"
          :key="items.id + 'tabItem'"
        >
          <v-btn
            plain
            text
            class="nav-link"
            data-bs-toggle="tab"
            @click="sort_field = items.id"
            :class="{ active: items.name_en == sort_field }"
          >
            <span v-if="currentLang == 'bangla'">{{ items.name_bn }}</span>
            <span v-else>{{ items.name_en }}</span>
          </v-btn>
        </li>
      </ul>
      <div class="tab-content" v-if="!dataLoading">
        <div class="tab-pane fade show active">
          <div v-for="(items, index) in allData" :key="index + 'tab-pane'">
            <div class="title_item">
              <span v-if="items[0].type">
                <span v-if="currentLang == 'bangla'">
                  {{ items[0].type.name_bn }}
                </span>
                <span v-else>{{ items[0].type.name_en }}</span>
              </span>
            </div>

            <div
              class="pRow"
              v-if="
                $vuetify.breakpoint.name == 'lg' ||
                $vuetify.breakpoint.name == 'xl'
              "
            >
              <div class="d-flex">
                <div
                  class="col-lg-3 col-6 hidden-sm-and-down"
                  v-for="(item, index) in items"
                  :key="item.id + item.price + index"
                  style="width: 307.25px"
                >
                  <v-hover>
                    <template v-slot:default="{ hover }">
                      <v-card elevation="4" class="text-center">
                        <!-- <v-img
                          :src="imagePath + item.image"
                          contain
                          alt="Image"
                          height="160"
                        ></v-img> -->
                        <img
                          :src="imagePath + item.image"
                          alt="Image"
                          style="
                            width: 200px;
                            height: 160px;
                            object-fit: contain;
                          "
                        />

                        <div class="ribbon-2" v-if="item.price_offer">
                          {{ $t("offer__") }}
                        </div>

                        <v-card-text
                          style="position: relative"
                          class="border-top text-left"
                        >
                          <div class="d-flex primary--text font-weight-bold">
                            <div>
                              <span v-if="currentLang == 'bangla'">
                                {{ item.name_bn }}
                              </span>
                              <span v-else>{{ item.name_en }}</span>
                              &nbsp;
                            </div>

                            <div>
                              <span v-if="currentLang == 'bangla'">
                                ({{ item.type.name_bn }})
                              </span>
                              <span v-else>({{ item.type.name_en }})</span>
                            </div>
                          </div>

                          <div>
                            <span v-if="item.price_offer">
                              <span class="text-decoration-line-through">{{
                                item.price | toCurrency
                              }}</span>
                              <span class="error--text">
                                {{ item.price_offer | toCurrency }}
                                <img
                                  src="https://asset.cpbangladesh.com/documents/fire_20220720093404n4FV0uh89P.gif"
                                  height="25"
                                />
                              </span>
                            </span>
                            <span v-else>{{ item.price | toCurrency }}</span>
                          </div>
                        </v-card-text>

                        <v-overlay
                          :absolute="true"
                          :value="true"
                          v-if="findId(item)"
                          @click="addToCart(item)"
                        >
                          <v-avatar size="70">
                            <img
                              src="/all-assets/common/logo/cpb/cpfivestar.png"
                              alt=""
                            />
                          </v-avatar>
                        </v-overlay>

                        <v-fade-transition v-else>
                          <v-overlay
                            v-if="hover"
                            absolute
                            @click="addToCart(item)"
                          >
                            <v-btn class="blue darken-4 text-white" small>{{
                              $t("add__to__cart")
                            }}</v-btn>
                          </v-overlay>
                        </v-fade-transition>
                      </v-card>
                    </template>
                  </v-hover>
                </div>
              </div>
            </div>

            <!-- for small screen -->
            <div class="pRow" v-else>
              <div class="d-flex">
                <div
                  class="hidden-md-and-up"
                  :class="itemWidth ? 'col-md-6' : 'col-md-4'"
                  v-for="(item, index) in items"
                  :key="index + 'small'"
                  style="width: 160.25px"
                >
                  <v-card
                    class="text-center"
                    elevation="4"
                    @click="addToCart(item)"
                  >
                    <v-overlay
                      :absolute="true"
                      :value="true"
                      v-if="findId(item)"
                    >
                      <v-avatar size="70">
                        <img
                          src="/all-assets/common/logo/cpb/cpfivestar.png"
                          alt=""
                        />
                      </v-avatar>
                    </v-overlay>
                    <img
                      :src="imagePath + item.image"
                      alt="Image"
                      style="width: 140px; height: 120px; object-fit: contain"
                    />

                    <div class="ribbon-2" v-if="item.price_offer">
                      {{ $t("offer__") }}
                    </div>

                    <v-card-text
                      style="position: relative"
                      class="border-top text-left"
                    >
                      <div class="primary--text font-weight-bold">
                        <div>
                          <span v-if="currentLang == 'bangla'">
                            {{ item.name_bn }}
                          </span>
                          <span v-else>{{ item.name_en }}</span>
                        </div>
                      </div>

                      <div>
                        <div>
                          <span
                            v-if="item.price_offer"
                            class="d-flex align-items-center"
                          >
                            <span class="text-decoration-line-through">{{
                              item.price | toCurrency
                            }}</span>
                            <span class="error--text">
                              {{ item.price_offer | toCurrency }}
                              <img
                                src="https://asset.cpbangladesh.com/documents/fire_20220720093404n4FV0uh89P.gif"
                                height="20"
                              />
                            </span>
                          </span>
                          <span v-else>{{ item.price | toCurrency }}</span>
                        </div>
                      </div>
                    </v-card-text>
                  </v-card>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- tab end -->

      <!-- product loading -->

      <div v-else>
        <tbl-data-loader v-if="dataLoading" />
      </div>
      <data-not-available v-if="allData.length <= 0 && !dataLoading" />

      <!-- cart components -->
    </div>

    <v-navigation-drawer
      app
      right
      v-if="bottomSheet"
      v-model="bottomSheet"
      hide-overlay
      :width="screenWidth > 420 ? width : '100%'"
      :permanent="screenWidth > 420 ? true : false"
      touchless
    >
      <div class="pa-2">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <v-icon left color="teal">mdi-clock</v-icon>
            <b>{{ time }}</b>
          </div>
          <v-btn class="btn_close" small icon @click="bottomSheet = false">
            <v-icon>mdi-close</v-icon>
          </v-btn>
        </div>
      </div>
      <hr />

      <cart2-cart
        @sheetResponse="sheetResponse"
        @addToCart="addToCart"
      ></cart2-cart>
    </v-navigation-drawer>

    <v-snackbar v-model="snackbar" timeout="2000">
      {{ snackbarText }}
      <template v-slot:action="{ attrs }">
        <v-btn
          class="btn_close"
          text
          v-bind="attrs"
          @click="snackbar = false"
          icon
        >
          <v-icon>mdi-close-circle</v-icon>
        </v-btn>
      </template>
    </v-snackbar>
  </div>
</template>

<script>
import cart2 from "./cart/index.vue";
import saleModifyMethod from "./allsales/modify";
export default {
  components: { "cart2-cart": cart2 },
  data() {
    return {
      overlay: false,
      currentUrl: "/item",

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

      allData: [],

      snackbarText: "",
      snackbar: false,
      itemWidth: false,

      interval: null,
      time: null,
      screenWidth: screen.width,
      quickSearchData: "",
      quickDataLoading: false,
      modifyOverlay: false,
    };
  },
  methods: {
    ...saleModifyMethod,

    // getResults
    getResults() {
      this.dataLoading = true;
      axios
        .get(
          this.currentUrl +
            "/index?search=" +
            this.search +
            "&sort_field=" +
            this.sort_field
        )
        .then((response) => {
          this.allData = response.data.allData;
          this.typesData = response.data.allSaleType;
          // Loading Animation
          this.dataLoading = false;
        });
    },

    // get all food type
    getType() {
      axios
        .get(this.currentUrl + "/sales_type")
        .then((response) => {
          this.typesData = response.data.allData;
        })
        .catch((error) => {
          console.log(error);
        });
    },

    // add to cart
    addToCart(item) {
      // find double item
      const count = this.purchaseItem.filter((obj) => obj.id == item.id).length;
      // check double item
      if (count > 0) {
        // remove double item
        const removeIndex = this.purchaseItem.findIndex(
          (obj) => obj.id === item.id
        );
        this.purchaseItem.splice(removeIndex, 1);
        if (this.currentLang == "bangla") {
          this.snackbar = true;
          this.snackbarText = "বাদ দেওয়া হয়েছে";
        } else {
          this.snackbar = true;
          this.snackbarText = "Remove Successfully";
        }
      } else {
        item.subtotal = 0;

        item.quantity = 1;

        this.purchaseItem.push(item);

        if (this.currentLang == "bangla") {
          this.snackbar = true;
          this.snackbarText = "যোগ করা হয়েছে";
        } else {
          this.snackbar = true;
          this.snackbarText = "Added Successfully";
        }
      }

      this.$store.commit("setCart", this.purchaseItem);
    },

    // find id to show remove item button
    findId(item) {
      const count = this.purchaseItem.filter((obj) => obj.id == item.id).length;
      // check double item
      if (count > 0) {
        return true;
      } else {
        return false;
      }
    },

    // FOR CLOSING CART
    sheetResponse() {
      this.bottomSheet = false;
      this.$store.commit("setCartTrigger", false);
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
      if (this.cart.length == 0) {
        this.purchaseItem = [];
      } else {
        if (this.screenWidth > "425") {
          this.bottomSheet = true;
          this.bottomSheetKey++;
        }
      }
    },

    bottomSheet(e) {
      if (e == false) {
        // this.bottomSheet = false;
        this.itemWidth = false;
        this.$store.commit("setCartTrigger", false);
      } else {
        this.itemWidth = true;
      }
    },

    cartTrigger(e) {
      if (e == true) {
        this.bottomSheet = true;
        this.bottomSheetKey++;
      }
    },
  },

  computed: {
    width() {
      switch (this.$vuetify.breakpoint.name) {
        case "xs":
          return 235; // portrait tab
        case "sm":
          return 330; // ipad
        case "md":
          return 300; // landscape
        case "lg":
          return 320; // laptop
        case "xl":
          return 400; // monitor
      }
    },
  },

  mounted() {
    if (this.cart.length) {
      this.purchaseItem.push(...this.cart);
    }
  },

  beforeDestroy() {
    clearInterval(this.interval);
  },

  created() {
    this.$Progress.start();
    this.getResults();
    this.$Progress.finish();

    // update the time every second
    this.interval = setInterval(() => {
      this.time = Intl.DateTimeFormat(navigator.language, {
        hour: "numeric",
        minute: "numeric",
        second: "numeric",
      }).format();
    }, 1000);

    if (this.$route.params.from) {
      this.bottomSheet = true;
      this.bottomSheetKey++;
    }
  },
};
</script>

<style scoped>
.pad {
  padding-bottom: 20rem;
}
.label_color {
  letter-spacing: 0.163em !important;
  color: #990f02;
}

.bg_color {
  background-color: #990f02;
}

.custom_font_color {
  color: #990f02;
}

.title_item {
  font-size: 19px;
  font-weight: 500;
  text-transform: uppercase;
  letter-spacing: 4px;
  margin-top: 10px;
  border-bottom: 2px solid #990f02;
  border-top: 2px solid #990f02;
  display: inline-block;
}

/* offer banner */
.ribbon-2 {
  --f: 10px; /* control the folded part*/
  --r: 15px; /* control the ribbon shape */
  --t: 15px; /* the top offset */

  position: absolute;
  inset: var(--t) calc(-1 * var(--f)) auto auto;
  padding: 0 10px var(--f) calc(10px + var(--r));
  clip-path: polygon(
    0 0,
    100% 0,
    100% calc(100% - var(--f)),
    calc(100% - var(--f)) 100%,
    calc(100% - var(--f)) calc(100% - var(--f)),
    0 calc(100% - var(--f)),
    var(--r) calc(50% - var(--f) / 2)
  );
  background: #e9121b;
  color: white;
  box-shadow: 0 calc(-1 * var(--f)) 0 inset #0005;
}

/*  offer banner end */

/* horizontal scroll */
.pRow {
  overflow-x: scroll;
}
.pRow::-webkit-scrollbar {
  height: 6px;
}
.pRow::-webkit-scrollbar-thumb {
  background: #940808;
  border-radius: 6px;
}

/* horizontal scroll end*/

/* nav tabs (sort item) style */
.itemOneLine {
  overflow-x: auto;
  overflow-y: hidden;
  flex-wrap: nowrap !important;
}

@media screen and (max-width: 1080px) {
  * {
    font-size: 13px !important;
  }
}

.nav-tabs .nav-item .nav-link.active {
  color: white;
  background-color: #990f02;
}
.nav-tabs .nav-item .nav-link:hover {
  color: #990f02;
  border: 1px solid #990f02;
}
.nav-tabs .nav-item .nav-link.active:hover {
  color: white;
}
.tab-content .tab-pane {
  border-top: 2px solid #990f02;
  margin-top: 0;
}

.v-application ol,
.v-application ul {
  margin-bottom: -2px !important;
}
</style>
