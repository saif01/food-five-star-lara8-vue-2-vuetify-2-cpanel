<template>
  <v-app class="login_bg">
    <v-main>
      <v-container fill-height>
        <v-layout align-center justify-center>
          <v-flex class="login-form text-xs-center">
            <v-card class="card_bg rounded-lg">
              <!-- Error -->
              <v-alert v-if="error" shaped prominent type="error" dismissible>{{ errorMsg }}</v-alert>
              <v-card-text>
                <div class="d-flex align-items-center justify-content-center">
                  <img src="/all-assets/common/logo/cpb/cpfivestar.png" alt height="50px" />
                  <h3 class="text-center animate-charcter ml-4">CP Five Star</h3>
                </div>

                <v-form>
                  <form @submit.prevent="login()">
                    <v-text-field
                      v-model="form.login"
                      prepend-inner-icon="mdi-account-key"
                      label="Login"
                      type="text"
                      :rules="[(v) => !!v || 'Login ID is required!']"
                      placeholder="Enter Your Operator / Owner / Admin Login ID"
                      required
                    ></v-text-field>
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
                    ></v-text-field>

                    <v-btn
                      type="submit"
                      block
                      :loading="loading"
                      color="#ec1e26"
                      class="white--text"
                    >
                      Sign in
                      <v-icon right>mdi-login-variant</v-icon>
                    </v-btn>
                  </form>
                </v-form>

                <div class="d-flex align-items-center justify-content-between mt-2">
                  <v-btn plain text small class="error--text">Maintenance Mode</v-btn>

                  <div class="small">
                    <img src="/all-assets/common/logo/cpb/cpbit.png" alt height="30px" />
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
        user_type: "op"
      }),

      announcementMessage: "",

      items: [
        {
          value: "op",
          text: "As a Operator"
        },
        {
          value: "wn",
          text: "As a Owner"
        },
        {
          value: "ad",
          text: "As a Admin"
        }
      ],

      // reset
      reset: false,
      resetKey: 1
    };
  },
  methods: {
    // Login
    login() {
      this.loading = true;
      this.form
        .post("/login_action")
        .then(response => {
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
        .catch(error => {
          this.loading = false;
        });
    }
  }
};
</script>

<style scoped>
.submit_css {
  position: relative;
  display: block;
  color: #f62f63;
  font-size: 16px;
  text-decoration: none;
  overflow: hidden;
  transition: 0.5s;
  letter-spacing: 4px;
  margin-top: 10px;
}

.submit_css:hover {
  background: #f62f63;
  color: #fff;
  border-radius: 5px;
}

.submit_css span {
  position: absolute;
  display: block;
}

.submit_css span:nth-child(1) {
  top: 0;
  left: -100%;
  width: 100%;
  height: 2px;
  background: linear-gradient(90deg, transparent, #f62f63);
  animation: btn-anim1 1s linear infinite;
}

@keyframes btn-anim1 {
  0% {
    left: -100%;
  }
  50%,
  100% {
    left: 100%;
  }
}

.submit_css span:nth-child(2) {
  top: -100%;
  right: 0;
  width: 2px;
  height: 100%;
  background: linear-gradient(180deg, transparent, #f62f63);
  animation: btn-anim2 1s linear infinite;
  animation-delay: 0.25s;
}

@keyframes btn-anim2 {
  0% {
    top: -100%;
  }
  50%,
  100% {
    top: 100%;
  }
}

.submit_css span:nth-child(3) {
  bottom: 0;
  right: -100%;
  width: 100%;
  height: 2px;
  background: linear-gradient(270deg, transparent, #f62f63);
  animation: btn-anim3 1s linear infinite;
  animation-delay: 0.5s;
}

@keyframes btn-anim3 {
  0% {
    right: -100%;
  }
  50%,
  100% {
    right: 100%;
  }
}

.submit_css span:nth-child(4) {
  bottom: -100%;
  left: 0;
  width: 2px;
  height: 100%;
  background: linear-gradient(360deg, transparent, #f62f63);
  animation: btn-anim4 1s linear infinite;
  animation-delay: 0.75s;
}

@keyframes btn-anim4 {
  0% {
    bottom: -100%;
  }
  50%,
  100% {
    bottom: 100%;
  }
}
</style>
