<template>
  <div>
    <v-app-bar extended color="#365BC2">
      <v-app-bar-title>Teacher's Assistant</v-app-bar-title>

      <v-spacer></v-spacer>
      <v-avatar size="30">
          <v-img
            alt="John"
            src="https://cdn.vuetifyjs.com/images/john.jpg"
          ></v-img>
        </v-avatar>
      <v-btn icon="mdi-dots-vertical">
      </v-btn>
    </v-app-bar>

    <v-main class="pt-0">
      <v-container>
        <v-row>
          <v-col cols="12">
            <span class="py-1 px-5 rounded-lg" style="background-color: #E84D72; color:white ;">Recently</span>
          </v-col>
          <v-col
            v-for="n in 4"
            :key="n"
            cols="3"
          >
            <v-card height="200" elevation="5">
              <v-row class="pa-2" no-gutters>
                <v-col cols="12">
                  <v-img src="https://letsenhance.io/static/8f5e523ee6b2479e26ecc91b9c25261e/1015f/MainAfter.jpg"></v-img>
                </v-col>
                <v-col cols="12" class="pt-2 d-flex justify-space-between align-center">
                  <span>ITE-G8-M4</span>
                  <v-btn variant="text" size="small" @click="ToClass">view class</v-btn>
                </v-col>
              </v-row>
            </v-card>
          </v-col>
          <v-col cols="12" class="d-flex justify-space-between align-center">
            <span>All Class</span>
            <v-col cols="3">
              <v-text-field
                append-icon="mdi-magnify"
                label="Search class"
                variant="underlined"
              ></v-text-field>
            </v-col>
          </v-col>
          <v-col
            v-for="n in 10"
            :key="n"
            cols="3"
          >
            <v-card height="200" elevation="5">
              <v-row class="pa-2" no-gutters>
                <v-col cols="12">
                  <v-img src="https://letsenhance.io/static/8f5e523ee6b2479e26ecc91b9c25261e/1015f/MainAfter.jpg"></v-img>
                </v-col>
                <v-col cols="12" class="pt-2 d-flex justify-space-between align-center">
                  <span>ITE-G8-M4</span>
                  <v-btn variant="text" size="small" @click="ToClass">view class</v-btn>
                </v-col>
              </v-row>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </v-main>
  </div>
</template>
<script>
  export default {
    data: () => ({
    }),

    methods: {
      ToClass () {
        this.$router.push('/user/dashboard')
      },
    },
  }
</script>
