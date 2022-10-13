<template>
  <div>
    <v-app-bar app flat dense class="bg-white">
      <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>

      <v-spacer></v-spacer>
      <v-btn icon @click="toggle()">
        <v-icon v-if="fullscreen">mdi-fullscreen</v-icon>
        <v-icon v-else>mdi-fullscreen-exit</v-icon>
      </v-btn>

      <v-menu open-on-hover offset-y transition="scale-transition">
        <template v-slot:activator="{ on, attrs }">
          <span v-if="auth" class="m-1">{{ auth.name }}</span>
          <span v-else class="m-1">admin</span>
          <v-avatar v-bind="attrs" v-on="on" contain>
            <img v-if="auth && auth.image" :src="'/images/users/small/' + auth.image" alt="Image" />
            <img v-else src="/all-assets/common/img/no-image.png" alt="No-Image" />
          </v-avatar>
        </template>

        <v-list>
          <v-list-item router link :to="{ name: 'showProfile' }">
            <v-list-item-title>Profile</v-list-item-title>
          </v-list-item>
          <v-list-item link router href="/logout">
            <v-list-item-title>Logout</v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu>
    </v-app-bar>

    <!-- sidebar -->
    <v-navigation-drawer
      app
      dark
      v-model="drawer"
      :class="maintanceMode ? 'bg_gradient_disabled' : 'bg_gradient'"
    >
      <v-list-item
        class="px-2"
        link
        href="/admin"
        :class="maintanceMode ? 'bg_gradient_disabled' : ''"
        :style="maintanceMode ? '' : 'background-color: #7d1717 !important'"
      >
        <v-list-item-icon>
          <img src="/all-assets/common/logo/cpb/cpfivestar.png" alt height="40px" contain />
        </v-list-item-icon>
        <v-list-item-title class="inline-block">Five Star Admin</v-list-item-title>
      </v-list-item>
      <v-divider></v-divider>

      <v-list dense nav>
        <v-list-item link router :to="{ name: 'Dashboard' }" exact>
          <v-list-item-icon>
            <v-icon>mdi-view-dashboard-outline</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>Dashboard</v-list-item-title>
          </v-list-item-content>
        </v-list-item>

        <v-list-group
          no-action
          prepend-icon="mdi-cart-variant"
          active-class="white--text"
          v-if="
            isAnyPermit(['order-all-order-view', 'order-adjust-order-view'])
          "
        >
          <template v-slot:activator>
            <v-list-item-title>Order</v-list-item-title>
          </template>

          <v-list-item link router :to="{ name: 'orders' }" v-if="isPermit('order-all-order-view')">
            <v-list-item-icon>
              <v-icon>mdi-file-document-outline</v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>All</v-list-item-title>
            </v-list-item-content>
          </v-list-item>

          <v-list-item
            link
            router
            :to="{ name: 'adjust_order' }"
            v-if="isPermit('order-adjust-order-view')"
          >
            <v-list-item-icon>
              <v-icon>mdi-file-document-edit-outline</v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>Adjust</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list-group>

        <v-list-group
          no-action
          prepend-icon="mdi-account-cog-outline"
          active-class="white--text"
          v-if="
            isAnyPermit([
              'admin-view',
              'owner-view',
              'operator-view',
              'zone-view',
              'login-log-view',
            ])
          "
        >
          <template v-slot:activator>
            <v-list-item-title>Users</v-list-item-title>
          </template>

          <v-list-item link router :to="{ name: 'admin' }" v-if="isPermit('admin-view')">
            <v-list-item-icon>
              <v-icon>mdi-briefcase-account</v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>Admin</v-list-item-title>
            </v-list-item-content>
          </v-list-item>

          <v-list-item link router :to="{ name: 'owners' }" v-if="isPermit('owner-view')">
            <v-list-item-icon>
              <v-icon>mdi-account-supervisor-circle</v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>Owners</v-list-item-title>
            </v-list-item-content>
          </v-list-item>

          <v-list-item link router :to="{ name: 'operators' }" v-if="isPermit('operator-view')">
            <v-list-item-icon>
              <v-icon>mdi-account-group-outline</v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>Operators</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item link router :to="{ name: 'role' }" v-if="isAdministrator()">
            <v-list-item-icon>
              <v-icon>mdi-alpha-r-circle-outline</v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>Roles</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item link router :to="{ name: 'zone' }" v-if="isPermit('zone-view')">
            <v-list-item-icon>
              <v-icon>mdi-map-marker</v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>Zones</v-list-item-title>
            </v-list-item-content>
          </v-list-item>

          <v-list-item link router :to="{ name: 'log' }" v-if="isPermit('login-log-view')">
            <v-list-item-icon>
              <v-icon>mdi-math-log</v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>Login Logs</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list-group>

        <v-list-item link router :to="{ name: 'outlet' }" v-if="isPermit('outlet-view')">
          <v-list-item-icon>
            <v-icon>mdi-storefront-outline</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>Outlets</v-list-item-title>
          </v-list-item-content>
        </v-list-item>

        <v-list-group
          no-action
          prepend-icon="mdi-format-list-group"
          active-class="white--text"
          v-if="
            isAnyPermit([
              'product-sale-category-view',
              'product-order-category-view',
            ])
          "
        >
          <template v-slot:activator>
            <v-list-item-title>Product Category</v-list-item-title>
          </template>

          <v-list-item
            link
            router
            :to="{ name: 'ProductSaleType' }"
            v-if="isPermit('product-sale-category-view')"
          >
            <v-list-item-icon>
              <v-icon>mdi-grid-large</v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>Sale</v-list-item-title>
            </v-list-item-content>
          </v-list-item>

          <v-list-item
            link
            router
            :to="{ name: 'productOrderType' }"
            v-if="isPermit('product-order-category-view')"
          >
            <v-list-item-icon>
              <v-icon>mdi-grid-large</v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>Order</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list-group>

        <v-list-group
          no-action
          prepend-icon="mdi-format-list-checkbox"
          active-class="white--text"
          v-if="isAnyPermit(['product-sale-view', 'product-order-view'])"
        >
          <template v-slot:activator>
            <v-list-item-title>Product</v-list-item-title>
          </template>

          <v-list-item
            link
            router
            :to="{ name: 'saleProduct' }"
            v-if="isPermit('product-sale-view')"
          >
            <v-list-item-icon>
              <v-icon>mdi-grid-large</v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>Sale</v-list-item-title>
            </v-list-item-content>
          </v-list-item>

          <v-list-item
            link
            router
            :to="{ name: 'orderProduct' }"
            v-if="isPermit('product-order-view')"
          >
            <v-list-item-icon>
              <v-icon>mdi-grid-large</v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>Order</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list-group>

        <v-list-item
          link
          router
          :to="{ name: 'announcement' }"
          v-if="isPermit('announcement-view')"
        >
          <v-list-item-icon>
            <v-icon>mdi-bullhorn</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>Announcement</v-list-item-title>
          </v-list-item-content>
        </v-list-item>

        <v-list-item link router :to="{ name: 'message' }" v-if="isPermit('message-view')">
          <v-list-item-icon>
            <v-icon>mdi-message-badge-outline</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>Messasge</v-list-item-title>
          </v-list-item-content>
        </v-list-item>

        <v-list-group
          no-action
          prepend-icon="mdi-file-chart-outline"
          active-class="white--text"
          v-if="isPermit('report')"
        >
          <template v-slot:activator>
            <v-list-item-title>Report</v-list-item-title>
          </template>

          <v-list-item link router :to="{ name: 'outletReport' }">
            <v-list-item-icon>
              <v-icon>mdi-grid-large</v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>Outlet</v-list-item-title>
            </v-list-item-content>
          </v-list-item>

          <v-list-item link router :to="{ name: 'zoneReport' }">
            <v-list-item-icon>
              <v-icon>mdi-grid-large</v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>Zone</v-list-item-title>
            </v-list-item-content>
          </v-list-item>

          <v-list-item link router :to="{ name: 'hourlyReport' }">
            <v-list-item-icon>
              <v-icon>mdi-grid-large</v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>Hourly</v-list-item-title>
            </v-list-item-content>
          </v-list-item>

          <v-list-item link router :to="{ name: 'sku' }">
            <v-list-item-icon>
              <v-icon>mdi-grid-large</v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>SKU</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list-group>

        <v-list-group
          no-action
          prepend-icon="mdi-cog-outline"
          active-class="white--text"
          v-if="isAdministrator() || isPermit('data-sync-view')"
        >
          <template v-slot:activator>
            <v-list-item-title>Settings</v-list-item-title>
          </template>

          <v-list-item link router :to="{ name: 'scheduleTimer' }" v-if="isAdministrator()">
            <v-list-item-icon>
              <v-icon>mdi-database-clock-outline</v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>Schedule Timer</v-list-item-title>
            </v-list-item-content>
          </v-list-item>

          <v-list-item link router :to="{ name: 'sync' }" v-if="isPermit('data-sync-view')">
            <v-list-item-icon>
              <v-icon>mdi-database-sync-outline</v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>Sync</v-list-item-title>
            </v-list-item-content>
          </v-list-item>

          <v-list-item link router :to="{ name: 'transferData' }" v-if="isAdministrator()">
            <v-list-item-icon>
              <v-icon>mdi-database-arrow-right-outline</v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>Transfer Data</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list-group>

        <v-list-item link router href="/logout">
          <v-list-item-icon>
            <v-icon>mdi-logout</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>Logout</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>

      <template v-slot:append>
        <v-btn
          v-if="isAdministrator()"
          @click="checkMaintanceMode('cs')"
          block
          plain
          text
          small
          :loading="maintenanceLoading"
        >
          <v-icon left>mdi-wrench-clock</v-icon>Maintenance Mode
          <v-badge v-if="maintanceMode" content="ON"></v-badge>
          <v-badge v-else content="OFF"></v-badge>
        </v-btn>

        <v-btn v-else readonly block plain text small>
          <v-icon left>mdi-wrench-clock</v-icon>Maintenance Mode
          <v-badge v-if="maintanceMode" content="ON"></v-badge>
          <v-badge v-else content="OFF"></v-badge>
        </v-btn>
      </template>
    </v-navigation-drawer>
  </div>
</template>


<script>
export default {
  data() {
    return {
      fullscreen: true,
      drawer: true,
      maintanceMode: false,
      maintenanceLoading: false
    };
  },
  methods: {
    toggle() {
      this.fullscreen = !this.fullscreen;
      if (this.fullscreen == false) {
        this.expand();
      } else {
        this.exitExpand();
      }
    },
    expand() {
      var elem = document.documentElement;
      if (elem.requestFullscreen) {
        elem.requestFullscreen();
      } else if (elem.msRequestFullscreen) {
        elem.msRequestFullscreen();
      } else if (elem.mozRequestFullScreen) {
        elem.mozRequestFullScreen();
      } else if (elem.webkitRequestFullscreen) {
        elem.webkitRequestFullscreen();
      }
    },
    exitExpand() {
      if (document.exitFullscreen) {
        document.exitFullscreen();
      } else if (document.webkitExitFullscreen) {
        document.webkitExitFullscreen();
      } else if (document.msExitFullscreen) {
        document.msExitFullscreen();
      }
    },

    // checkMaintanceMode
    checkMaintanceMode(val = null) {
      if (val) {
        var maintanceMoodeTimer = 0;
      } else {
        var maintanceMoodeTimer = 10000;
      }
      setTimeout(() => {
        this.maintenanceLoading = true;
        axios
          .post("/common/app_maintance_mode", { status: val })
          .then(response => {
            this.maintanceMode = response.data;
            // console.log(response);
            this.maintenanceLoading = false;
            this.runCount = 1;
          })
          .catch(error => {
            console.log(error);
          });
      }, maintanceMoodeTimer);
    }
  },

  created() {
    this.checkMaintanceMode();
  }
};
</script>


<style scoped>
.bg_gradient {
  background: #990f02;
}

.bg_gradient_disabled {
  background: #5a5857;
  /* background: #990f02; */
}

a:hover {
  text-decoration: none;
}

.v-list-item--active {
  background: #ffda1b;
  color: #990f02;
}

/* .v-application--is-ltr .v-list-item__action:first-child,
.v-application--is-ltr .v-list-item__icon:first-child {
  margin-right: 22px !important;
} */
</style>
