<template>
  <v-app style="overflow-x: hidden">
    <side-bar></side-bar>

    <v-main>
      <div class="pa-3">
        <router-view></router-view>
        <vue-progress-bar></vue-progress-bar>

        <scroll-to-top></scroll-to-top>
      </div>
    </v-main>

    <page-footer></page-footer>
    <!-- <session-out-dialog /> -->
  </v-app>
</template>

<script>
import sideBar from "./pages/common/sidebar.vue";
import pageFooter from "./pages/common/footer.vue";
import sessionOutDialog from "./../session_out.vue";

export default {
  props: ["authuser", "permission"],

  components: {
    "side-bar": sideBar,
    "page-footer": pageFooter,
    sessionOutDialog
  },

  created() {
    // Set Auth and Role data in Store
    if (this.authuser) {
      this.$store.commit("setAuth", JSON.parse(this.authuser));
    }
    if (this.permission) {
      this.$store.commit("setRoles", JSON.parse(this.permission));
    }

    this.$Progress.start();

    //checkUserRole
    // console.log(JSON.parse(this.permission));

    this.$Progress.finish();
  }
};
</script>

<style>
.table_head_link a {
  text-decoration: none;
  color: white !important;
}
</style>
