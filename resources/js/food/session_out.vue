<template>
  <div>
    <v-dialog max-width="300" v-model="sessionOutDialog" persistent>
      <v-card>
        <v-card-title class="justify-center">
          <v-row>
            <v-col cols="10">Session Time Out</v-col>
            <v-col cols="2">
              <v-btn @click="sessionLogoutRedirect()" small class="float-right btn_close">
                <v-icon dark>mdi-close-octagon</v-icon>
              </v-btn>
            </v-col>
          </v-row>
        </v-card-title>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
export default {
  data() {
    return {
      //   sessionOutDialog: true
    };
  },
  methods: {
    sessionLogoutRedirect() {
      console.log("logout");
      window.location.href = "/";
    }
  }
};
</script>