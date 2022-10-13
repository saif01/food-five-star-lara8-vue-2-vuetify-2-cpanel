<template>
  <v-app>
    <router-view></router-view>
    <vue-progress-bar></vue-progress-bar>
  </v-app>
</template>

<script>
export default {
  props: ["maintenance"],

  created() {
    this.$Progress.start();

    if (this.maintenance == "safsdfdsyyyasfs") {
      this.$router.push({
        name: "maintenance",
      });
    }
    this.$Progress.finish();
  },
};
</script>

