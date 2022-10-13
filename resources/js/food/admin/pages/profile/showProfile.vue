<template>
  <div>
    <div class="d-flex">
      <router-link
        link
        route
        :to="{ name: 'Dashboard' }"
        small
        exact
        class="primary--text text-decoration-none"
      >
        Dashboard
      </router-link>

      &nbsp;/ <span class="text-muted">&nbsp;Profile&nbsp;</span> /
      <span class="font-weight-bold">&nbsp;Profile </span>
    </div>
    <v-card elevation="0">
      <v-row v-if="!dataLoading">
        <v-col cols="12" lg="4" class="text-center">
          <v-card-text>
            <v-avatar size="170">
              <img
                :src="'/images/users/' + auth.image"
                alt="Image"
                class="p-1 bg-dark"
              />
            </v-avatar>
            <div class="h3 mt-3" style="color: #ff5722">
              {{ auth.name }}
            </div>

            <div>
              {{ auth.department }}
            </div>
          </v-card-text>
        </v-col>
        <v-col cols="12" lg="6">
          <v-card class="p-2">
            <v-card-text style="background-color: #efefef">
              <div>
                <v-icon left>mdi-account-key</v-icon> Login-ID:
                <b>{{ auth.login }}</b>
              </div>
              <div>
                <v-icon left>mdi-map-marker-outline</v-icon> Office:
                <span>
                  <b v-if="auth.office">
                    {{ auth.office }}
                  </b>
                  <span v-else class="error--text">N/A</span>
                </span>
              </div>
            </v-card-text>

            <v-card-text style="background-color: #efefef">
              <div>
                <v-icon left>mdi-cellphone</v-icon> Personal Contact:
                <span>
                  <b v-if="auth.personal_contact">
                    <a :href="`tel:${auth.personal_contact}`">
                      {{ auth.personal_contact }}
                    </a>
                  </b>
                  <span v-else class="error--text">N/A</span>
                </span>
              </div>
              <div>
                <v-icon left>mdi-cellphone-cog</v-icon> Office Contact:
                <span>
                  <b v-if="auth.office_contact">
                    <a :href="`tel:${auth.office_contact}`">
                      {{ auth.office_contact }}
                    </a>
                  </b>
                  <span v-else class="error--text">N/A</span>
                </span>
              </div>
            </v-card-text>

            <v-card-text style="background-color: #efefef">
              <div>
                <v-icon left>mdi-at</v-icon> Office Email:
                <span>
                  <b v-if="auth.office_email">
                    {{ auth.office_email }}
                  </b>
                  <span v-else class="error--text">N/A</span>
                </span>
              </div>
              <div>
                <v-icon left>mdi-email</v-icon> Personal Email:
                <span>
                  <b v-if="auth.personal_email">
                    {{ auth.personal_email }}
                  </b>
                  <span v-else class="error--text">N/A</span>
                </span>
              </div>
            </v-card-text>

            <v-card-text style="background-color: #efefef">
              <div>
                <v-icon left>mdi-card-account-details</v-icon> Office ID:
                <span>
                  <b v-if="auth.office_id">
                    {{ auth.office_id }}
                  </b>
                  <span v-else class="error--text">N/A</span>
                </span>
              </div>
              <div>
                <v-icon left>mdi-card-account-details-outline</v-icon> NID:
                <span>
                  <b v-if="auth.nid">
                    {{ auth.nid }}
                  </b>
                  <span v-else class="error--text">N/A</span>
                </span>
              </div>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
    </v-card>
  </div>
</template>

