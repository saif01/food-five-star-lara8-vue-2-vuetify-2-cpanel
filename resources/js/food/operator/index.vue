<template>
  <v-app style="overflow-x: hidden">
    <nav-bar></nav-bar>
    <v-main>
      <div class="pa-3">
        <router-view></router-view>
        <vue-progress-bar></vue-progress-bar>

        <scroll-to-top></scroll-to-top>
        <!-- <emergency></emergency> -->
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
import navBar from "./pages/common/navbar.vue";
import pageFooter from "./pages/common/footer.vue";

import announce from "./pages/announce/index.vue";

export default {
  props: ["authuser", "permission", "language"],

  components: {
    "nav-bar": navBar,
    "page-footer": pageFooter,
    "announce-msg": announce
  },

  data() {
    return {
      response: false,
      announceMsg: false,
      data: ""
    };
  },

  methods: {
    getAnnouncement() {
      axios.get("/announcement/get_announcement").then(response => {
        if (response.data) {
          this.$store.commit("setAnnounce", response.data);
          if (sessionStorage.counter != 1) {
            this.data = response.data;
            this.announceMsg = true;

            sessionStorage.counter = 1;
          }
        }
      });
    }
  },

  created() {
    // Set Auth and Role data in Store
    this.$Progress.start();

    if (this.authuser) {
      this.$store.commit("setAuth", JSON.parse(this.authuser));
    } else {
      sessionStorage.counter = 0;
    }
    if (this.permission) {
      this.$store.commit("setRoles", JSON.parse(this.permission));
    }

    if (this.language) {
      this.translate(this.language);
    }

    setTimeout(() => {
      this.getAnnouncement();
    }, 5000);

    this.$Progress.finish();
  }
};
</script>


<style>
/* * {
  font-family: "Trebuchet MS", "Lucida Sans Unicode", "Lucida Grande",
    "Lucida Sans", Arial, sans-serif;
} */

/* @media screen and (max-width: 400px) {
  * {
    font-size: 12px;
  }
} */
</style>

