<template>
  <div>
    <v-app-bar app flat dense dark>
      <v-app-bar-nav-icon router link :to="{ name: 'Dashboard' }" exact>
        <v-img
          src="/all-assets/common/logo/cpb/cpfivestar.png"
          max-height="40"
          max-width="40"
          contain
        ></v-img>
      </v-app-bar-nav-icon>

      <v-toolbar-items class="hidden-sm-and-down">
        <v-btn small plain text router link :to="{ name: 'Dashboard' }" exact>Dashboard</v-btn>
        <v-btn small plain text router link :to="{ name: 'OutletInformation' }">Outlet Information</v-btn>
        <v-btn small plain text router link :to="{ name: 'ReportSales' }">Report</v-btn>

        <v-btn link route :to="{ name: 'CurrentStockOrder' }" plain small>Stock</v-btn>
      </v-toolbar-items>
      <v-spacer></v-spacer>

      <v-menu open-on-hover offset-y transition="scale-transition" class="hidden-sm-and-down">
        <template v-slot:activator="{ on, attrs }">
          <span v-if="auth" class="m-1">
            <span class="small">{{ auth.owner_login }}</span>
          </span>

          <v-avatar v-bind="attrs" v-on="on" contain>
            <img v-if="auth.image" :src="'/images/users/small/' + auth.image" alt="image" />
            <img v-else src="https://www.w3schools.com/howto/img_avatar.png" alt="image" />
          </v-avatar>
        </template>

        <v-list>
          <v-list-item router link :to="{ name: 'ShowProfile' }">
            <v-list-item-title>Profile</v-list-item-title>
          </v-list-item>

          <v-list-item router link :to="{ name: 'Reset' }">
            <v-list-item-title>Reset Password</v-list-item-title>
          </v-list-item>

          <v-list-item link router href="/logout" @click="logout()">
            <v-list-item-title>Logout</v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu>

      <v-app-bar-nav-icon class="hidden-md-and-up" @click.stop="drawer = !drawer" color="#e63546">
        <v-icon>mdi-hamburger</v-icon>
      </v-app-bar-nav-icon>
    </v-app-bar>

    <!-- sidebar -->
    <v-navigation-drawer dark v-model="drawer" app temporary right>
      <v-list-item class="px-2" link href="/owner">
        <v-list-item-icon>
          <img src="/all-assets/common/logo/cpb/cpfivestar.png" alt="Logo" height="30px" contain />
        </v-list-item-icon>
        <v-list-item-title>CP Food</v-list-item-title>
      </v-list-item>
      <v-divider></v-divider>

      <v-list dense nav>
        <v-list-item link router :to="{ name: 'Dashboard' }" exact>
          <v-list-item-icon>
            <v-icon>mdi-view-dashboard-outline</v-icon>
          </v-list-item-icon>

          <v-list-item-content>
            <v-list-item-title>Dasboard</v-list-item-title>
          </v-list-item-content>
        </v-list-item>

        <v-list-item link router :to="{ name: 'OutletInformation' }">
          <v-list-item-icon>
            <v-icon>mdi-storefront-outline</v-icon>
          </v-list-item-icon>

          <v-list-item-content>
            <v-list-item-title>Outlet Information</v-list-item-title>
          </v-list-item-content>
        </v-list-item>

        <v-list-item link router :to="{ name: 'ReportSales' }">
          <v-list-item-icon>
            <v-icon>mdi-file</v-icon>
          </v-list-item-icon>

          <v-list-item-content>
            <v-list-item-title>Report</v-list-item-title>
          </v-list-item-content>
        </v-list-item>

        <v-list-item link router :to="{ name: 'ShowProfile' }">
          <v-list-item-icon>
            <v-icon>mdi-account</v-icon>
          </v-list-item-icon>

          <v-list-item-content>
            <v-list-item-title>Profile</v-list-item-title>
          </v-list-item-content>
        </v-list-item>

        <v-list-item link route :to="{ name: 'CurrentStockOrder' }">
          <v-list-item-icon>
            <v-icon>mdi-database</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>Stock</v-list-item-title>
          </v-list-item-content>
        </v-list-item>

        <v-list-item link router href="/logout" @click="logout()">
          <v-list-item-icon>
            <v-icon>mdi-logout</v-icon>
          </v-list-item-icon>

          <v-list-item-content>
            <v-list-item-title>Logout</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>
  </div>
</template>

<script>
export default {
  data() {
    return {
      drawer: null
    };
  }
};
</script>




<style scoped>
.bg_gradient {
  background: #990f02;
}

a:hover {
  text-decoration: none;
}

.v-btn--active {
  color: #ffda1b;
}
</style>
