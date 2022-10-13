"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[562],{9083:(r,n,t)=>{t.d(n,{Z:()=>i});var e=t(7897),s=t.n(e),a=t(1519),o=t.n(a)()(s());o.push([r.id,".login-form[data-v-448f040c]{max-width:600px}.login_bg[data-v-448f040c]{background:radial-gradient(circle at 50% 50%,#ed1c24,#ffd503)}.btn_color[data-v-448f040c]{background:#e63546!important;color:#fff}.label_color[data-v-448f040c]{color:#e63546}","",{version:3,sources:["webpack://./resources/js/auth/pages/otp_input.vue"],names:[],mappings:"AAyGA,6BACA,eACA,CAEA,2BAEA,6DACA,CAEA,4BAEA,4BAAA,CADA,UAEA,CAEA,8BACA,aACA",sourcesContent:['<template>\r\n  <div>\r\n    <v-app class="login_bg">\r\n      <v-main>\r\n        <v-container fill-height>\r\n          <v-layout align-center justify-center>\r\n            <v-flex class="login-form text-xs-center">\r\n              <v-card>\r\n                <v-card-text>\r\n                  <form @submit.prevent="verifyOtp()">\r\n                    <div\r\n                      class="\r\n                        d-flex\r\n                        align-items-center\r\n                        justify-content-center\r\n                        mb-3\r\n                      "\r\n                    >\r\n                      <img\r\n                        src="/all-assets/common/logo/cpb/cpfivestar.png"\r\n                        alt=""\r\n                        height="50px"\r\n                      />\r\n                      <h3 class="text-center label_color ml-4">\r\n                        Reset Password\r\n                      </h3>\r\n                    </div>\r\n                    <v-alert dense text type="success">\r\n                      An OTP has been sent to this\r\n                      <strong>{{ $route.params.number }}</strong> number\r\n                    </v-alert>\r\n\r\n                    <h5>Enter OTP</h5>\r\n                    <v-otp-input length="4" v-model="form.otp"></v-otp-input>\r\n\r\n                    <v-btn block small color="primary" type="submit"\r\n                      >Submit</v-btn\r\n                    >\r\n                  </form>\r\n                </v-card-text>\r\n              </v-card>\r\n            </v-flex>\r\n          </v-layout>\r\n        </v-container>\r\n      </v-main>\r\n    </v-app>\r\n  </div>\r\n</template>\r\n\r\n<script>\r\nimport Form from "vform";\r\nexport default {\r\n  data() {\r\n    return {\r\n      form: new Form({\r\n        otp: "",\r\n        number: "",\r\n      }),\r\n    };\r\n  },\r\n\r\n  methods: {\r\n    verifyOtp() {\r\n      this.form.post("/verify_otp").then((response) => {\r\n        Swal.fire(\r\n          response.data.data,\r\n          response.data.msg,\r\n          response.data.status\r\n        ).then(() => {\r\n          if (response.data.status == "success") {\r\n            console.log(this.$route.params.reset_type);\r\n            // this.$router.go("/login/new-password");\r\n            this.$router.push({\r\n              name: "new_password",\r\n              params: {\r\n                owner_id: response.data.owner_id,\r\n                reset_type: this.$route.params.reset_type,\r\n                cv_code: this.$route.params.cv_code,\r\n              },\r\n            });\r\n          }\r\n          if (response.data.expire == "expired") {\r\n            this.$router.go({\r\n              name: "Login",\r\n            });\r\n          }\r\n        });\r\n      });\r\n    },\r\n  },\r\n\r\n  created() {\r\n    if (!this.$route.params.number) {\r\n      this.$router.push({\r\n        name: "resetPassword",\r\n      });\r\n    } else {\r\n      this.form.number = this.$route.params.number;\r\n    }\r\n  },\r\n};\r\n<\/script>\r\n\r\n\r\n<style scoped>\r\n.login-form {\r\n  max-width: 600px;\r\n}\r\n\r\n.login_bg {\r\n  /* background: #990f02; */\r\n  background: radial-gradient(circle at 50% 50%, #ed1c24, #ffd503);\r\n}\r\n\r\n.btn_color {\r\n  color: white;\r\n  background: #e63546 !important;\r\n}\r\n\r\n.label_color {\r\n  color: #e63546;\r\n}\r\n</style>'],sourceRoot:""}]);const i=o},2562:(r,n,t)=>{t.r(n),t.d(n,{default:()=>l});var e=t(5714);const s={data:function(){return{form:new e.ZP({otp:"",number:""})}},methods:{verifyOtp:function(){var r=this;this.form.post("/verify_otp").then((function(n){Swal.fire(n.data.data,n.data.msg,n.data.status).then((function(){"success"==n.data.status&&(console.log(r.$route.params.reset_type),r.$router.push({name:"new_password",params:{owner_id:n.data.owner_id,reset_type:r.$route.params.reset_type,cv_code:r.$route.params.cv_code}})),"expired"==n.data.expire&&r.$router.go({name:"Login"})}))}))}},created:function(){this.$route.params.number?this.form.number=this.$route.params.number:this.$router.push({name:"resetPassword"})}};var a=t(3379),o=t.n(a),i=t(9083),c={insert:"head",singleton:!1};o()(i.Z,c);i.Z.locals;const l=(0,t(1900).Z)(s,(function(){var r=this,n=r._self._c;return n("div",[n("v-app",{staticClass:"login_bg"},[n("v-main",[n("v-container",{attrs:{"fill-height":""}},[n("v-layout",{attrs:{"align-center":"","justify-center":""}},[n("v-flex",{staticClass:"login-form text-xs-center"},[n("v-card",[n("v-card-text",[n("form",{on:{submit:function(n){return n.preventDefault(),r.verifyOtp()}}},[n("div",{staticClass:"d-flex align-items-center justify-content-center mb-3"},[n("img",{attrs:{src:"/all-assets/common/logo/cpb/cpfivestar.png",alt:"",height:"50px"}}),r._v(" "),n("h3",{staticClass:"text-center label_color ml-4"},[r._v("\n                      Reset Password\n                    ")])]),r._v(" "),n("v-alert",{attrs:{dense:"",text:"",type:"success"}},[r._v("\n                    An OTP has been sent to this\n                    "),n("strong",[r._v(r._s(r.$route.params.number))]),r._v(" number\n                  ")]),r._v(" "),n("h5",[r._v("Enter OTP")]),r._v(" "),n("v-otp-input",{attrs:{length:"4"},model:{value:r.form.otp,callback:function(n){r.$set(r.form,"otp",n)},expression:"form.otp"}}),r._v(" "),n("v-btn",{attrs:{block:"",small:"",color:"primary",type:"submit"}},[r._v("Submit")])],1)])],1)],1)],1)],1)],1)],1)],1)}),[],!1,null,"448f040c",null).exports}}]);
//# sourceMappingURL=562.js.map