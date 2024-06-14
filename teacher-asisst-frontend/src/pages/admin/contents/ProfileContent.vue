<template>
  <v-card>
    <v-layout>
      <v-navigation-drawer location="left" permanent>
        <v-col cols="12">
          <v-list-item color="#0000" class="profile-text-name ma-4">
            <v-list-item-content
              class="text-center d-flex justify-center flex-column align-center"
            >
              <v-avatar
                class="profile avatar-center-height avatar-shadow"
                color="grey"
                size="78"
              >
                <v-img
                  src="https://cdn.vuetifyjs.com/images/profiles/marcus.jpg"
                ></v-img>
              </v-avatar>
              <v-list-item-title> {{ profile.first_name }} {{profile.last_name}} </v-list-item-title>
              <v-chip class="ma-2" color="primary" text-color="white" label>
                {{data.role_name}}
              </v-chip>
            </v-list-item-content>
          </v-list-item>
        </v-col>

        <v-divider></v-divider>

        <v-list density="compact" nav>
          <v-list-item
            prepend-icon="mdi-home-city"
            title="Profile Info"
            value="profile"
            :to="`/admin/profile/basic-info/${this.$route.params.id}/personal-details`"
          ></v-list-item>
          <v-list-item
            prepend-icon="mdi-account"
            title="Account"
            value="account"
            :to="`/admin/profile/basic-info/${this.$route.params.id}/account`"
          ></v-list-item>
          <v-list-item
            prepend-icon="mdi-lock"
            title="Change Password"
            value="password"
            :to="`/admin/profile/basic-info/${this.$route.params.id}/change-password`"
          ></v-list-item>
        </v-list>
      </v-navigation-drawer>
      <v-main style="overflow-y: auto; height: 90vh"
        ><v-progress-linear
          :active="formLoading"
          indeterminate
          top
          absolute
          color="primary accent-4"
        ></v-progress-linear>
        <router-view></router-view>
      </v-main>
    </v-layout>
  </v-card>
</template>
<script>
import axios from "@/axios";
// import fakeDataAPI from "../fakedata";

export default {
  data: () => ({
    valid: false,
    formLoading: false,
    data:"",
    profile:"",
    account: "",
    requiredRules: [(v) => !!v || "Please fill out this field!"],
    numberRules: [
      (v) => !!v || "Please fill out this field!",
      (v) => Number.isInteger(Number(v)) || "Please enter numbers only!",
    ],
    emailRules: [
      (v) => !!v || "Please fill out the field!",
      (v) =>
        !v ||
        /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) ||
        "E-mail must be valid",
    ],
    loading: true,
  }),
  created() {
    this.fetchData();
  },
    methods: {
      async fetchData() {
        try {
          const response = await axios.get(`/users/${this.$route.params.id}`);
          this.data = response.data;
          this.profile = response.data.profile;
        } catch (error) {
          console.error("Error fetching data:", error);
          this.showErrorSnackbar = true;
          this.errorMessage = "Error fetching data.";
        }
      },
    },
};
</script>
