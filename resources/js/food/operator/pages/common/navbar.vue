<template>
  <div>
    <v-app-bar app flat dense dark class="nav-color" style="z-index: 6">
      <v-app-bar-nav-icon link route :to="{ name: 'Dashboard' }" exact>
        <v-img
          src="/all-assets/common/logo/cpb/cpfivestar.png"
          max-height="40"
          max-width="40"
          contain
        ></v-img>
      </v-app-bar-nav-icon>

      <v-toolbar-items class="hidden-sm-and-down">
        <v-btn link route :to="{ name: 'Dashboard' }" plain small exact>
          {{ $t("nav__dashboard") }}
        </v-btn>

        <!-- <v-menu open-on-hover offset-y transition="scale-transition">
          <template v-slot:activator="{ on, attrs }">
            <v-btn small plain text v-bind="attrs" v-on="on">
              <v-avatar size="30">
                <img
                  v-if="auth && auth.image"
                  :src="'/images/users/small/' + auth.image"
                  alt="Image"
                />
                <img
                  v-else
                  src="/all-assets/common/img/no-image.png"
                  alt="No-Image"
                />
              </v-avatar>
              <span v-if="auth" class="ml-1">
                <b v-if="currentLang == 'bangla'">
                  {{ englishTobangla(auth.cv_code) }}
                </b>
                <b v-else>{{ auth.cv_code }}</b>
              </span>
              <span v-else>{{ $t("nav__profile") }}</span>
            </v-btn>
          </template>

          <v-list dense dark class="nav_color">
            <v-list-item router link :to="{ name: 'EditProfile' }">
              <v-list-item-title>{{
                $t("nav__editProfile")
              }}</v-list-item-title>
            </v-list-item>

            <v-list-item router link :to="{ name: 'ResetPass' }">
              <v-list-item-title>{{
                $t("nav__resetPassword")
              }}</v-list-item-title>
            </v-list-item>
          </v-list>
        </v-menu> -->

        <v-btn small plain text router link :to="{ name: 'Report' }">
          {{ $t("nav__report") }}
        </v-btn>

        <v-btn link route :to="{ name: 'AllSales' }" plain small>
          {{ $t("nav__allSales") }}
        </v-btn>

        <v-btn link route :to="{ name: 'AllOrder' }" plain small>
          {{ $t("nav__allOrder") }}
        </v-btn>

        <v-btn link route :to="{ name: 'CurrentStockOrder' }" plain small>
          {{ $t("nav__currentstock") }}
        </v-btn>
      </v-toolbar-items>

      <v-spacer></v-spacer>

      <!-- <div class="text-uppercase">
        <div v-if="currentLang == 'bangla'" class="d-flex align-items-center">
          <v-img
            src="all-assets/common/flag/bd.svg"
            alt
            contain
            max-width="30px"
            max-height="15px"
          ></v-img>
        </div>
        <div v-else class="d-flex align-items-center">
          <v-img
            src="all-assets/common/flag/us.svg"
            alt
            contain
            max-width="30px"
            max-height="15px"
          ></v-img>
        </div>
      </div> -->

      <!-- <v-menu open-on-hover offset-y>
        <template v-slot:activator="{ on, attrs }">
          <div v-if="currentLang == 'bangla'" class="d-flex align-items-center">
            <v-img
              v-bind="attrs"
              v-on="on"
              src="all-assets/common/flag/us.svg"
              alt
              contain
              max-width="30px"
              max-height="15px"
            ></v-img>
          </div>
          <div v-else class="d-flex align-items-center">
            <v-img
              v-bind="attrs"
              v-on="on"
              src="all-assets/common/flag/us.svg"
              alt
              contain
              max-width="30px"
              max-height="15px"
            ></v-img>
          </div>
        </template>
        <v-list dense>
          <v-list-item @click="translate('english')" active-class="border">
            <v-list-item-title>English</v-list-item-title>
          </v-list-item>

          <v-list-item @click="translate('bangla')">
            <v-list-item-title>Bangla</v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu> -->

      <v-btn icon @click="cart.length > 0 ? openSheet() : ''" class="mr-2">
        <v-icon>mdi-cart-outline</v-icon>
        <v-badge v-if="cart != ''">
          <template v-slot:badge>
            <!-- <span v-if="currentLang == 'bangla'">
              {{ englishTobangla(cart.length) }}
            </span> -->
            <span>{{ cart.length }}</span>
          </template>
        </v-badge>
        <v-badge v-else content="0"></v-badge>
      </v-btn>

      <!-- <v-btn
        text
        href="/logout"
        small
        icon
        class="hidden-sm-and-down ma-2"
        @click="logout()"
      >
        <v-icon>mdi-logout</v-icon>
      </v-btn> -->

      <v-menu open-on-hover offset-y transition="scale-transition">
        <template v-slot:activator="{ on, attrs }">
          <v-btn small plain text v-bind="attrs" v-on="on">
            <v-avatar size="30">
              <img
                v-if="auth && auth.image"
                :src="'/images/users/small/' + auth.image"
                alt="Image"
              />
              <img
                v-else
                src="/all-assets/common/img/no-image.png"
                alt="No-Image"
              />
            </v-avatar>
            <span v-if="auth" class="ml-1">
              <!-- <b v-if="currentLang == 'bangla'">
                {{ englishTobangla(auth.cv_code) }}
              </b> -->
              <b>{{ auth.cv_code }}</b>
            </span>
            <span v-else>{{ $t("nav__profile") }}</span>
          </v-btn>
        </template>

        <v-list dense dark class="nav_color">
          <v-list-item router link :to="{ name: 'EditProfile' }">
            <v-list-item-title>{{ $t("nav__editProfile") }}</v-list-item-title>
          </v-list-item>

          <v-list-item router link :to="{ name: 'ResetPass' }">
            <v-list-item-title>{{
              $t("nav__resetPassword")
            }}</v-list-item-title>
          </v-list-item>

          <v-list-item href="/logout" @click="logout()">
            <v-list-item-title> {{ $t("nav__logout") }}</v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu>

      <v-app-bar-nav-icon
        class="hidden-md-and-up"
        @click.stop="drawer = !drawer"
        color="#e63546"
      >
        <v-icon>mdi-hamburger</v-icon>
      </v-app-bar-nav-icon>
    </v-app-bar>

    <!-- sidebar -->
    <v-navigation-drawer app dark v-model="drawer" right temporary>
      <v-list-item class="px-2 bg-none">
        <v-list-item-avatar>
          <img
            src="/all-assets/common/logo/cpb/cpfivestar.png"
            alt="Logo"
            height="30px"
            contain
          />
        </v-list-item-avatar>
        <v-list-item-title>CP Food</v-list-item-title>
      </v-list-item>
      <v-divider></v-divider>

      <v-list dense nav>
        <v-list-item link router :to="{ name: 'Dashboard' }" exact>
          <v-list-item-icon>
            <v-icon>mdi-view-dashboard-outline</v-icon>
          </v-list-item-icon>

          <v-list-item-content>
            <v-list-item-title>{{ $t("nav__dashboard") }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>

        <v-list-item link router :to="{ name: 'Report' }" exact>
          <v-list-item-icon>
            <v-icon>mdi-file-chart</v-icon>
          </v-list-item-icon>

          <v-list-item-content>
            <v-list-item-title>{{ $t("nav__report") }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>

        <v-list-item link route :to="{ name: 'AllSales' }">
          <v-list-item-icon>
            <v-icon>mdi-update</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>{{ $t("nav__allSales") }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>

        <v-list-item link route :to="{ name: 'AllOrder' }">
          <v-list-item-icon>
            <v-icon>mdi-chart-pie</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>{{ $t("nav__allOrder") }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>

        <v-list-item link route :to="{ name: 'CurrentStockOrder' }">
          <v-list-item-icon>
            <v-icon>mdi-database</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>{{ $t("nav__currentstock") }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>

        <!-- <v-list-item link router href="/logout" @click="logout()">
          <v-list-item-icon>
            <v-icon>mdi-logout</v-icon>
          </v-list-item-icon>

          <v-list-item-content>
            <v-list-item-title>{{ $t("nav__logout") }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item> -->

        <!-- <v-list-group
          no-action
          prepend-icon="mdi-account-circle-outline"
          active-class="white--text"
        >
          <template v-slot:activator>
            <v-list-item-title>
              <v-avatar size="30">
                <img
                  v-if="auth && auth.image"
                  :src="'/images/users/small/' + auth.image"
                  alt="Image"
                />
                <img
                  v-else
                  src="/all-assets/common/img/no-image.png"
                  alt="No-Image"
                />
              </v-avatar>
              <span v-if="auth" class="ml-1">
                <b v-if="currentLang == 'bangla'">
                  {{ englishTobangla(auth.cv_code) }}
                </b>
                <b v-else>{{ auth.cv_code }}</b>
              </span>
              <span v-else>{{ $t("nav__profile") }}</span>
            </v-list-item-title>
          </template>

          <v-list-item link router :to="{ name: 'EditProfile' }">
            <v-list-item-icon>
              <v-icon>mdi-account-edit</v-icon>
            </v-list-item-icon>

            <v-list-item-content>
              <v-list-item-title>{{
                $t("nav__editProfile")
              }}</v-list-item-title>
            </v-list-item-content>
          </v-list-item>

          <v-list-item link router :to="{ name: 'ResetPass' }">
            <v-list-item-icon>
              <v-icon>mdi-lock-reset</v-icon>
            </v-list-item-icon>

            <v-list-item-content>
              <v-list-item-title>{{
                $t("nav__resetPassword")
              }}</v-list-item-title>
            </v-list-item-content>
          </v-list-item>

          <v-list-item link router href="/logout" @click="logout()">
            <v-list-item-icon>
              <v-icon>mdi-logout</v-icon>
            </v-list-item-icon>

            <v-list-item-content>
              <v-list-item-title>{{ $t("nav__logout") }}</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list-group> -->
        <v-list-item link router href="/logout" @click="logout()">
          <v-list-item-icon>
            <v-icon>mdi-logout</v-icon>
          </v-list-item-icon>

          <v-list-item-content>
            <v-list-item-title>{{ $t("nav__logout") }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>

    <!-- cart components -->
    <!-- <div>
      <sale-cart v-if="bottomSheet" :key="bottomSheetKey"></sale-cart>
    </div> -->
  </div>
</template>

<script>
export default {
  data() {
    return {
      drawer: null,
    };
  },

  methods: {
    openSheet() {
      if (this.cartTrigger == true) {
        this.$store.commit("setCartTrigger", false);
      } else {
        this.$store.commit("setCartTrigger", true);
      }

      // this.bottomSheetKey++;
      // this.bottomSheet = true;
    },
  },
};
</script>

<style scoped>
.label_color {
  color: #990f02;
}

.v-list-item--active {
  background: #ffda1b;
  color: #990f02;
}

.v-btn--active {
  color: #ffda1b;
}

a {
  text-decoration: none;
}
</style>
