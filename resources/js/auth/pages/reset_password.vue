<template>
  <v-app class="login_bg">
    <v-main>
      <v-container fill-height>
        <v-layout align-center justify-center>
          <v-flex class="login-form text-xs-center">
            <v-card class="card_bg">
              <!-- Error -->
              <v-alert v-if="error" shaped prominent type="error" dismissible>{{
                errorMsg
              }}</v-alert>
              <v-card-text class="pt-2">
                <div class="d-flex align-items-center justify-content-center">
                  <img
                    src="/all-assets/common/logo/cpb/cpfivestar.png"
                    alt
                    height="50px"
                  />
                  <h3 class="text-center animate-charcter ml-4">
                    Reset Password
                  </h3>
                </div>

                <form @submit.prevent="resetPass()">
                  <v-text-field
                    v-model="form.login"
                    prepend-inner-icon="mdi-account-key"
                    label="Password Reset ID"
                    type="number"
                    placeholder="Enter Your Operator / Owner Login ID"
                    :rules="[(v) => !!v || 'Login ID is required!']"
                    required
                  ></v-text-field>

                  <v-btn
                    v-if="form.personal_contact == ''"
                    block
                    :loading="loading"
                    type="submit"
                    class="btn_color"
                  >
                    Check
                    <v-icon right>mdi-magnify</v-icon>
                  </v-btn>

                  <v-btn
                    v-if="form.personal_contact"
                    :loading="loading"
                    block
                    type="submit"
                    class="btn_color"
                  >
                    Send Password to Number
                    <v-icon right>mdi-phone-message-outline</v-icon>
                  </v-btn>
                </form>

                <div
                  class="d-flex align-items-center justify-content-between mt-2"
                >
                  <router-link :to="{ name: 'Login' }" block class="btn">
                    Login <v-icon rigth>mdi-login-variant</v-icon>
                  </router-link>

                  <div class="small">
                    <img
                      src="/all-assets/common/logo/cpb/cpbit.png"
                      alt=""
                      height="30px"
                    />
                    Powered by CPB-IT
                  </div>
                </div>
              </v-card-text>
            </v-card>
          </v-flex>
        </v-layout>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
// vform
import Form from "vform";

export default {
  data() {
    return {
      passwordType: false,
      loading: false,
      password: null,

      error: false,
      errorMsg: "",

      // Form
      form: new Form({
        login: "",
        personal_contact: "",
      }),
    };
  },

  methods: {
    // Reset Password
    resetPass() {
      this.loading = true;
      this.form
        .post("/reset_pass")
        .then((response) => {
          // console.log(response.data);
          let resData = response.data;

          // this.form.personal_contact = response.data;
          // this.readonly = true;

          this.loading = false;

          // Error
          if (resData.status == "error") {
            // console.log(resData);
            this.error = true;
            this.errorMsg = resData.msg;
          } else {
            this.$router.push({
              name: "otp_input",
              params: {
                number: response.data.data,
                reset_type: response.data.reset_type,
                cv_code: response.data.cv_code,
              },
            });
          }
        })
        .catch((error) => {
          this.loading = false;

          // console.log(error);
        });
    },
  },
};
</script>