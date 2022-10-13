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

    <!-- for announce banner -->
    <div v-for="item in data" :key="item.id">
      <announce-msg v-if="announceMsg" :data="item"></announce-msg>
    </div>
  </v-app>
</template>

<script>
import sideBar from "./pages/common/sidebar.vue";
import pageFooter from "./pages/common/footer.vue";

import announce from "./pages/announce/index.vue";

export default {
  props: ["authuser"],

  components: {
    "side-bar": sideBar,
    "page-footer": pageFooter,
    "announce-msg": announce,
  },

  data() {
    return {
      data: "",
      announceMsg: false,
    };
  },

  methods: {
    getAnnouncement() {
      axios.get("/owner/announcement/get_announcement").then((response) => {
        if (response.data) {
          if (sessionStorage.counterOwner != 1) {
            this.data = response.data;
            this.announceMsg = true;
            sessionStorage.counterOwner = 1;
          }
        }
      });
    },
  },

  created() {
    this.$Progress.start();

    // // Set Auth and Role data in Store
    if (this.authuser) {
      this.$store.commit("setAuth", JSON.parse(this.authuser));
    }

    setTimeout(() => {
      this.getAnnouncement();
    }, 5000);

    this.$Progress.finish();
  },
};
</script>

