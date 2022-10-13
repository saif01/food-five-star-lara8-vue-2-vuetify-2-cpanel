<template>
  <div>
    <v-dialog
      v-model="announce"
      :overlay="false"
      max-width="800"
      transition="dialog-transition"
      color="transparent"
    >
      <v-card>
        <v-img
          :src="'/images/announcement/' + data.image"
          alt="image"
          contain
        ></v-img>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
export default {
  props: ["data"],
  data() {
    return {
      announce: true,
    };
  },
};
</script>
