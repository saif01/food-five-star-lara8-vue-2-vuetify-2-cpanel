<template>
  <v-app class="login_bg">
    <video src="/all-assets/common/bubble.mp4" autoplay muted loop></video>
    <v-main>
      <v-container fill-height>
        <v-layout align-center justify-center>
          <v-flex class="login-form text-xs-center">
            <v-card class="card_bg">
              <!-- Error -->
              <v-alert v-if="error" shaped prominent type="error" dismissible>
                {{ errorMsg }}
              </v-alert>
              <v-card-text>
                <div class="d-flex align-items-center justify-content-center">
                  <img
                    src="/all-assets/common/logo/cpb/cpfivestar.png"
                    alt=""
                    height="50px"
                  />
                  <h3 class="text-center label_color ml-4">CP Five Star</h3>
                </div>

                <v-form @submit.prevent="login()">
                  <v-text-field
                    v-model="form.login"
                    prepend-inner-icon="mdi-account-key"
                    label="Login"
                    type="text"
                    :rules="[(v) => !!v || 'Login ID is required!']"
                    placeholder="Enter Your Operator / Owner / Admin Login ID"
                    required
                    color="white"
                  >
                  </v-text-field>
                  <v-text-field
                    v-model="form.password"
                    prepend-inner-icon="mdi-lock"
                    label="Password"
                    placeholder="Enter Your Password"
                    :type="passwordType ? 'text' : 'password'"
                    :append-icon="passwordType ? 'mdi-eye' : 'mdi-eye-off'"
                    @click:append="passwordType = !passwordType"
                    :rules="[(v) => !!v || 'Password is required!']"
                    required
                    color="white"
                  ></v-text-field>

                  <v-btn
                    :loading="loading"
                    block
                    type="submit"
                    class="mt-3 btn_color"
                  >
                    Sign in <v-icon rigth>mdi-login-variant</v-icon>
                  </v-btn>
                </v-form>

                <div
                  class="d-flex align-items-center justify-content-between mt-2"
                >
                  <router-link
                    :to="{ name: 'resetPassword' }"
                    block
                    class="btn"
                  >
                    Forgot Password ?
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
      announce: true,

      passwordType: false,
      loading: false,
      password: null,

      error: false,
      errorMsg: "",

      viewDocument: false,

      // Form
      form: new Form({
        login: "",
        password: "",
        user_type: "op",
      }),

      announcementMessage: "",

      items: [
        {
          value: "op",
          text: "As a Operator",
        },
        {
          value: "wn",
          text: "As a Owner",
        },
        {
          value: "ad",
          text: "As a Admin",
        },
      ],

      // reset
      reset: false,
      resetKey: 1,
    };
  },
  methods: {
    // Login
    login() {
      this.loading = true;
      this.form
        .post("/login_action")
        .then((response) => {
          let resData = response.data;

          // Error
          if (resData.status == "error") {
            this.error = true;
            this.errorMsg = resData.msg;
          }

          // Success
          if (resData.status == "success") {
            this.error = false;

            if (resData.data.user_type == "ad") {
              // redirect with reload
              window.location.href = "/admin";
            } else if (resData.data.user_type == "wn") {
              // redirect with Owner
              window.location.href = "/owner";
            } else if (resData.data.user_type == "op") {
              // redirect with reload
              window.location.href = "/";
            }
          }

          this.loading = false;
        })
        .catch((error) => {
          this.loading = false;
        });
    },
  },
};
</script>
