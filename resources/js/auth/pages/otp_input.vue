<template>
  <div>
    <v-app class="login_bg">
      <v-main>
        <v-container fill-height>
          <v-layout align-center justify-center>
            <v-flex class="login-form text-xs-center">
              <v-card class="card_bg">
                <v-card-text>
                  <form @submit.prevent="verifyOtp()">
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
                    <v-alert dense text type="success">
                      An OTP has been sent to this
                      <strong>{{ $route.params.number }}</strong> number
                    </v-alert>

                    <h5>Enter OTP</h5>
                    <v-otp-input
                      length="4"
                      v-model="form.otp"
                      required
                    ></v-otp-input>

                    <v-btn block class="btn_color" type="submit">
                      <v-icon left>
                        mdi-checkbox-marked-circle-plus-outline
                      </v-icon>
                      Submit</v-btn
                    >

                    <div
                      class="text-muted mt-2 text-right"
                      v-if="this.countDown != 0"
                    >
                      Didn't receive OTP ?
                      <span v-if="this.countDown != 0">{{ countDown }}</span>
                    </div>

                    <v-btn
                      v-else
                      block
                      plain
                      text
                      small
                      @click="resendOTP()"
                      color="primary"
                      class="mt-2"
                    >
                      <v-icon left> mdi-message-badge-outine </v-icon> Resend
                      OTP
                    </v-btn>
                  </form>
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
      countDown: 60,
      form: new Form({
        otp: "",
        number: "",
      }),
    };
  },

  methods: {
    verifyOtp() {
      this.form.post("/verify_otp").then((response) => {
        Swal.fire(
          response.data.data,
          response.data.msg,
          response.data.status
        ).then(() => {
          if (response.data.status == "success") {
            // this.$router.go("/login/new-password");
            this.$router.push({
              name: "new_password",
              params: {
                owner_id: response.data.owner_id,
                reset_type: this.$route.params.reset_type,
                cv_code: this.$route.params.cv_code,
              },
            });
          }
          if (response.data.expire == "expired") {
            this.$router.go({
              name: "Login",
            });
          }
        });
      });
    },

    resendOTP() {
      axios
        .post("/reset_pass?login=" + this.$route.params.cv_code)
        .then((response) => {
          Swal.fire(
            response.data.data,
            response.data.msg,
            response.data.status
          ).then(() => {
            if (response.data.status == "success") {
            }
          });
        });
    },

    countDownTimer() {
      if (this.countDown > 0) {
        setTimeout(() => {
          this.countDown -= 1;
          this.countDownTimer();
        }, 1000);
      }
    },
  },

  mounted() {
    this.countDownTimer();
  },

  created() {
    if (!this.$route.params.number) {
      this.$router.push({
        name: "resetPassword",
      });
    } else {
      this.form.number = this.$route.params.number;
    }
  },
};
</script>