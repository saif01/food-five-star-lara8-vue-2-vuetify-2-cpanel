<template>
  <div>
    <v-card elevation="0" max-width="85%" class="mx-auto">
      <v-row v-if="!dataLoading">
        <v-col cols="12" lg="4">
          <v-card-text>
            <form @submit.prevent="updateData()">
              <div class="avatar_align">
                <div class="avatar-upload">
                  <div class="avatar-edit">
                    <v-file-input
                      v-if="form.image != ''"
                      prepend-icon="mdi-camera"
                      @change="uploadImageByName($event, 'image')"
                      hide-input
                      accept=".jpg, .png, .jpeg"
                      dense
                    ></v-file-input>
                  </div>
                  <v-avatar size="150" class="avatar-preview">
                    <img
                      :src="showImageByName()"
                      alt="Image"
                      class="p-1 bg-primary"
                    />
                  </v-avatar>
                </div>
              </div>
              <div class="h3 mt-3 text-center">{{ auth.name }}</div>

              <div class="mb-10 text-center">
                <div class="d-flex align-items-center justify-content-center">
                  <v-icon left>mdi-cellphone</v-icon>
                  <div v-if="auth.personal_contact">
                    <a :href="`tel:${auth.personal_contact}`">{{
                      auth.personal_contact
                    }}</a>
                  </div>
                </div>
              </div>
              <!-- <div class="mb-5">
                <div>
                  {{ $t("created__at") }}:
                  <span v-if="auth.created_at">{{
                    auth.created_at | moment("YYYY-MM-DD")
                  }}</span>
                  <span v-else class="error--text">N/A</span>
                </div>
                <div>
                  {{ $t("updated__at") }}:
                  <span v-if="auth.updated_at">{{
                    auth.updated_at | moment("YYYY-MM-DD")
                  }}</span>
                  <span v-else class="error--text">N/A</span>
                </div>
              </div>-->

              <!-- language -->
              <div v-if="currentLang == 'bangla'">
                <v-autocomplete
                  :items="language"
                  item-text="name_bn"
                  item-value="value"
                  v-model="form.language"
                  :label="$t('select__language')"
                  dense
                  prepend-icon="mdi-translate"
                ></v-autocomplete>
                <div
                  v-if="form.errors.has('language')"
                  v-html="form.errors.get('language')"
                />
              </div>
              <div v-else>
                <v-autocomplete
                  :items="language"
                  item-text="name"
                  item-value="value"
                  v-model="form.language"
                  :label="$t('select__language')"
                  prepend-icon="mdi-translate"
                  dense
                ></v-autocomplete>
                <div
                  v-if="form.errors.has('language')"
                  v-html="form.errors.get('language')"
                />
              </div>

              <v-btn
                type="submit"
                block
                depressed
                :loading="editLoading"
                class="mt-2 btn_update"
              >
                <v-icon left>mdi-pencil</v-icon>
                {{ $t("update__btn") }}
              </v-btn>
            </form>
          </v-card-text>
        </v-col>
        <v-col cols="12" lg="8">
          <v-card class="p-2">
            <v-card-text style="background-color: #efefef">
              <div>
                <v-icon left>mdi-storefront-outline</v-icon>
                {{ $t("shop__name") }}:
                <b>{{ form.shop_name }}</b>
              </div>

              <div>
                <v-icon left>mdi-cellphone</v-icon>
                <span>{{ $t("number__") }}:</span>
                <span v-if="form.owner_mobile">
                  <b>
                    <a :href="`tel:${form.owner_mobile}`">{{
                      form.owner_mobile
                    }}</a>
                  </b>
                </span>
                <span v-else class="error--text">N/A</span>
              </div>

              <div>
                <v-icon left>mdi-map-marker-outline</v-icon>
                {{ $t("shop__address") }}:
                <span v-if="form.shop_address">
                  <b>{{ form.shop_address }}</b>
                </span>
                <span v-else class="error--text">N/A</span>

                <div class="ml-9">
                  <div>
                    {{ $t("division__") }}:
                    <span v-if="form.division">
                      <b>{{ getDivision(form.division) }}</b>
                    </span>
                    <span v-else class="error--text">N/A</span>
                  </div>

                  <div>
                    {{ $t("district__") }}:
                    <span v-if="form.district">
                      <b>{{ getDistrict(form.district) }}</b>
                    </span>
                    <span v-else class="error--text">N/A</span>
                  </div>
                  <div>
                    {{ $t("city__") }}:
                    <span v-if="form.city">
                      <b>{{ getCity(form.city) }}</b>
                    </span>
                    <span v-else class="error--text">N/A</span>
                  </div>
                </div>
              </div>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>

      <div v-else>
        <tbl-data-loader v-if="dataLoading" />
      </div>
    </v-card>
  </div>
</template>


<script>
import Form from "vform";
import district from "../../js/district";
import division from "../../js/division";
import city from "../../js/city";

export default {
  data() {
    return {
      form: new Form({
        id: "",
        shop_name: "",
        owner_mobile: "",
        shop_address: "",
        image: "",
        city: "",
        district: "",
        division: "",
        language: "",
        user_name: "",
      }),

      ...district,
      ...division,
      ...city,

      districts: [],
      divisions: [],
      cities: [],

      // language
      language: [
        {
          name: "বাংলা",
          name_bn: "বাংলা",
          value: "bangla",
        },
        {
          name: "English",
          name_bn: "English",
          value: "english",
        },
      ],

      editLoading: false,
      currentUrl: "/shop",

      imagePathSm: "/images/users/small/",
      imagePath: "/images/users/",
      imageMaxSize: "2111775",
    };
  },

  methods: {
    getShopInfo() {
      this.dataLoading = true;
      axios.get(this.currentUrl + "/index").then((response) => {
        this.form.fill(response.data);
        if (response.data.user.language) {
          this.form.language = response.data.user.language.language;
        }
        if (response.data.user.name) {
          this.form.user_name = response.data.user.name;
        }
        if (response.data.user.image) {
          this.form.image = response.data.user.image;
        }
        this.dataLoading = false;
      });
    },

    // changeDistrict
    changeDistrict() {
      this.districts = [];
      this.cities = [];
      this.district.forEach((element) => {
        if (element.division_id === this.form.division) {
          this.districts.push(element);
        }
      });
    },

    // changeCity
    changeCity() {
      this.cities = [];
      this.city.forEach((element) => {
        if (element.district_id === this.form.district) {
          this.cities.push(element);
        }
      });
    },

    updateData() {
      this.dataLoading = true;
      this.form.post(this.currentUrl + "/update" + "").then((response) => {
        // Swal.fire("Changed!", "Updated Successfully", "success");
        if (this.currentLang == "bangla") {
          Swal.fire({
            title: "সফল",
            text: "সফলভাবে আপডেট করা হয়েছে",
            icon: "success",
            showConfirmButton: false,
          });
        } else {
          Swal.fire({
            title: "success",
            text: "Updated Successfully",
            icon: "success",
            showConfirmButton: false,
          });
        }
        this.getShopInfo();
        this.translate(this.form.language);

        this.dataLoading = false;
      });
    },

    getDistrict(id) {
      let district = "";
      this.district.forEach((element) => {
        if (element.id == id) {
          if (this.currentLang == "bangla") {
            district = element.bn_name;
          } else {
            district = element.name;
          }
        }
      });
      return district;
    },

    getDivision(id) {
      let division = "";
      this.division.forEach((element) => {
        if (element.id === id) {
          if (this.currentLang == "bangla") {
            division = element.bn_name;
          } else {
            division = element.name;
          }
        }
      });
      return division;
    },

    getCity(id) {
      let city = "";
      this.city.forEach((element) => {
        if (element.id === id) {
          if (this.currentLang == "bangla") {
            city = element.bn_name;
          } else {
            city = element.name;
          }
        }
      });
      return city;
    },
  },

  created() {
    this.$Progress.start();
    this.getShopInfo();
    this.$Progress.finish();
  },
};
</script>

<style scoped>
.avatar-upload {
  position: relative;
  max-width: 179px;
}
.avatar-upload .avatar-edit {
  position: absolute;
  right: -14px;
  /* right: 12px;
  z-index: 1;
  top: 10px; */
}
.avatar_align {
  display: flex;
  justify-content: center;
}
</style>
