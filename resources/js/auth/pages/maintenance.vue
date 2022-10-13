<template>
  <v-app class="maintenance_bg">
    <v-main>
      <v-container fill-height>
        <v-layout align-center justify-center>
          <v-flex class="login-form text-xs-center">
            <div
              class="
                d-flex
                flex-column
                justify-content-center
                align-items-center
              "
            >
              <img
                src="all-assets/errors/Under_construction.gif"
                alt="maintenance"
              />

              <div class="text-white font-weight-bold">
                503 SERVICE UNAVAILABLE
              </div>
            </div>

            <v-btn
              x-small
              absolute
              bottom
              right
              color="#FE715D"
              class="white--text"
              @click="
                $router.push({
                  name: 'maintenance-login',
                })
              "
            >
              <v-icon left>mdi-login</v-icon>
              Adminstrtor Login</v-btn
            >
          </v-flex>
        </v-layout>
      </v-container>
    </v-main>
  </v-app>
</template>

 