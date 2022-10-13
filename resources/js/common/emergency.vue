<template>
  <div
    v-if="$vuetify.breakpoint.name == 'lg' || $vuetify.breakpoint.name == 'xl'"
  >
    <v-btn
      v-if="state == 'on'"
      v-show="fab"
      fab
      dark
      fixed
      bottom
      small
      left
      color="dark"
      @mousedown="toHold('out')"
      style="z-index: 8"
    >
      <v-icon>mdi-bomb</v-icon>
    </v-btn>
    <v-btn
      v-show="fab"
      fab
      fixed
      bottom
      left
      color="dark"
      style="z-index: 8"
      v-else
      @mouseup="toHold('on')"
    >
      <v-progress-circular
        :rotate="360"
        :size="60"
        :width="10"
        :value="value"
        color="error"
      >
        <v-icon style="color: red !important">mdi-bomb</v-icon>
      </v-progress-circular>
    </v-btn>
  </div>

  <div v-else>
    <v-btn
      v-show="fab"
      fab
      dark
      fixed
      bottom
      small
      left
      color="dark"
      @touchstart="touchStart($event, 'out')"
      @touchend="touchEnd($event)"
      style="z-index: 8"
    >
      <!-- <v-icon v-if="state == 'on'">mdi-bomb</v-icon> -->

      <v-progress-circular
        :rotate="360"
        :size="70"
        :width="15"
        :value="value"
        color="error"
      >
        <v-icon style="color: red !important">mdi-bomb</v-icon>
      </v-progress-circular>
    </v-btn>
  </div>
</template>

<script>
export default {
  data() {
    return {
      fab: true,
      value: -0,
      interval: {},
      state: "on",

      onlongtouch: "",
      timer: null,
    };
  },

  methods: {
    toHold(e) {
      this.state = e;

      if (e == "out") {
        this.value = 0;
        this.interval = setInterval(() => {
          if (this.state == "out") {
            if (this.value === 100) {
              this.blockUser();
              this.state = "on";
              this.value = 0;
            }
            this.value += 25;
          } else {
            this.value = 0;
            this.interval = {};
          }
        }, 500);
      } else {
        this.value = 0;
        this.interval = {};
      }
    },

    touchStart(e, val) {
      // this.state = val;

      if (this.vlaue != 0) {
        this.interval = setInterval(() => {
          if (this.value === 100) {
            this.value = 0;
          }
          this.value += 50;
        }, 500);
      }

      e.preventDefault();
      if (!this.timer) {
        this.timer = setTimeout(() => {
          this.timer = null;
          clearInterval(this.interval);
          this.blockUser();
        }, 1100);
      }
    },

    touchEnd(e) {
      this.value = 0;
      clearInterval(this.interval);
      if (this.timer) {
        clearTimeout(this.timer);
        this.timer = null;
      }
    },
  },

  beforeDestroy() {
    clearInterval(this.interval);
  },
};
</script>

<style>
</style>