<template>
  <div>
    <v-main>
      <v-container fill-height>
        <v-layout align-center justify-center>
          <v-flex class="form-size text-xs-center">
            <v-card>
              <v-card-title>Reset Password</v-card-title>

              <v-card-text v-if="!dataLoading">
                <form @submit.prevent="resetPassword()">
                  <v-text-field
                    v-model="form.current_pass"
                    label="Current Password"
                    placeholder="Enter Current Password"
                    outlined
                    dense
                    required
                    prepend-inner-icon="mdi-lock-outline"
                    class="is-invalid"
                    :error-messages="form.errors.get('current_pass')"
                    :type="passwordType1 ? 'text' : 'password'"
                    :append-icon="passwordType1 ? 'mdi-eye' : 'mdi-eye-off'"
                    @click:append="passwordType1 = !passwordType1"
                  ></v-text-field>

                  <v-text-field
                    v-model="form.new_pass"
                    label="New Password"
                    placeholder="Enter New Password"
                    outlined
                    dense
                    required
                    prepend-inner-icon="mdi-lock-outline"
                    class="is-invalid"
                    :error-messages="form.errors.get('new_pass')"
                    :type="passwordType2 ? 'text' : 'password'"
                    :append-icon="passwordType2 ? 'mdi-eye' : 'mdi-eye-off'"
                    @click:append="passwordType2 = !passwordType2"
                  ></v-text-field>

                  <v-text-field
                    v-model="form.confirm_pass"
                    label="Confirm Password"
                    placeholder="Enter Confirm Password"
                    outlined
                    dense
                    required
                    prepend-inner-icon="mdi-lock-outline"
                    class="is-invalid"
                    :error-messages="form.errors.get('confirm_pass')"
                    :type="passwordType3 ? 'text' : 'password'"
                    :append-icon="passwordType3 ? 'mdi-eye' : 'mdi-eye-off'"
                    @click:append="passwordType3 = !passwordType3"
                  ></v-text-field>

                  <v-btn
                    type="submit"
                    block
                    depressed
                    :loading="resetLoading"
                    class="mt-2 btn_update"
                  >
                    <v-icon left>mdi-pencil</v-icon> Update Password
                  </v-btn>
                </form>
              </v-card-text>

              <v-card-text v-else>
                <tbl-data-loader />
              </v-card-text>
            </v-card>
          </v-flex>
        </v-layout>
      </v-container>
    </v-main>
  </div>
</template>


<script>
import Form from "vform";
export default {
  data() {
    return {
      passwordType1: false,
      passwordType2: false,
      passwordType3: false,
      form: new Form({
        current_pass: "",
        new_pass: "",
        confirm_pass: "",
      }),

      resetLoading: false,
      currentUrl: "/owner/reset",
    };
  },

  methods: {
    resetPassword() {
      this.dataLoading = true;
      this.form
        .post(this.currentUrl + "/index")
        .then((response) => {
          Swal.fire(
            response.data.data,
            response.data.msg,
            response.data.status
          );
          if (response.data.status == "success") {
            this.form.reset();
          }

          this.dataLoading = false;
        })
        .catch((error) => {
          this.dataLoading = false;
        });
    },
  },

  created() {
    this.$Progress.start();
    this.$Progress.finish();
  },
};
</script>

<style scoped>
.form-size {
  max-width: 600px;
}
</style>