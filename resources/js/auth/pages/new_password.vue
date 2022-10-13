<template>
  <div>
    <v-app class="login_bg">
      <v-main>
        <v-container fill-height>
          <v-layout align-center justify-center>
            <v-flex class="login-form text-xs-center">
              <v-card class="card_bg">
                <v-card-text>
                  <div
                    class="
                      d-flex
                      align-items-center
                      justify-content-center
                      mb-3
                    "
                  >
                    <img
                      src="/all-assets/common/logo/cpb/cpfivestar.png"
                      alt=""
                      height="50px"
                    />
                    <h3 class="text-center animate-charcter ml-4">
                      Reset Password
                    </h3>
                  </div>
                  <form @submit.prevent="reset()" v-if="!dataLoading">
                    <v-text-field
                      v-model="form.new_pass"
                      label="New Password"
                      placeholder="Enter New Password"
                      dense
                      required
                      prepend-inner-icon="mdi-lock-outline"
                      :type="passwordType ? 'text' : 'password'"
                      :append-icon="passwordType ? 'mdi-eye' : 'mdi-eye-off'"
                      @click:append="passwordType = !passwordType"
                      :error-messages="form.errors.get('new_pass')"
                    ></v-text-field>

                    <v-text-field
                      v-model="form.confirm_pass"
                      label="Confirm Passowrd"
                      placeholder="Enter Confirm Password"
                      dense
                      required
                      prepend-inner-icon="mdi-lock-outline"
                      :type="passwordType2 ? 'text' : 'password'"
                      :append-icon="passwordType2 ? 'mdi-eye' : 'mdi-eye-off'"
                      @click:append="passwordType2 = !passwordType2"
                      :error-messages="form.errors.get('confirm_pass')"
                    ></v-text-field>

                    <v-btn block class="btn_color" type="submit">Submit</v-btn>
                  </form>
                  <v-card-text v-else>
                    <tbl-data-loader />
                  </v-card-text>
                </v-card-text>
              </v-card>
            </v-flex>
          </v-layout>
        </v-container>
      </v-main>
    </v-app>
  </div>
</template>

<script>
import Form from "vform";
export default {
  data() {
    return {
      passwordType: false,
      passwordType2: false,
      form: new Form({
        new_pass: "",
        confirm_pass: "",
        owner_id: "",
        reset_type: "",
        cv_code: "",
      }),
      dataLoading: false,
    };
  },

  methods: {
    reset() {
      this.dataLoading = true;
      this.form
        .post("/new_password")
        .then((response) => {
          Swal.fire(
            response.data.data,
            response.data.msg,
            response.data.status
          );
          if (response.data.status == "success") {
            this.$router.push({
              name: "Login",
            });
          }
          this.dataLoading = false;
        })
        .catch((error) => {
          this.dataLoading = false;
        });
    },
  },

  created() {
    if (!this.$route.params.owner_id) {
      this.$router.push({
        name: "resetPassword",
      });
    } else {
      this.form.owner_id = this.$route.params.owner_id;
      this.form.reset_type = this.$route.params.reset_type;
      this.form.cv_code = this.$route.params.cv_code;
    }
  },
};
</script>