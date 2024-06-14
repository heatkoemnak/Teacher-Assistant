<template>
  <v-container>
    <div class="fill-height mb-5">
      <v-row class="d-flex align-center">
        <v-col cols="12" md="8">
          <v-card class="pa-8" outlined>
            <v-row>
              <v-col cols="auto">
                <v-avatar size="80">
                  <v-img
                    src="https://cdn.vuetifyjs.com/images/profiles/marcus.jpg"
                  ></v-img>
                </v-avatar>
              </v-col>
              <v-col>
                <v-card-title class="headline"
                  >{{ items.first_name }} {{ items.last_name }}
                </v-card-title>
                <v-card-subtitle class="mb-4 text-gray-400">
                  Phileas is a rascal, scoundrel and raconteur
                </v-card-subtitle>
                <v-chip class="ma-2" color="primary" text-color="white" label
                  >{{ role }}
                </v-chip>
              </v-col>
            </v-row>
          </v-card>
        </v-col>
      </v-row>
    </div>
    <v-list density="compact" nav class="d-flex">
      <v-chip
        class="ma-2 mx-2"
        color="primary"
        text-color="white"
        :class="{ 'v-chip--active': activeChip === 'personal details' }"
        :to="`/admin/profile/basic-info/${this.$route.params.id}/personal-details`"
        @click="setActiveChip('personal details')"
      >
        <v-icon left class="ma-2">mdi-account</v-icon>
        Profile Info
      </v-chip>
      <v-chip
        v-if="role === 'teacher'"
        class="ma-2 mx-2"
        color="primary"
        text-color="white"
        :class="{ 'v-chip--active': activeChip === 'subject' }"
        :to="`/admin/profile/basic-info/${this.$route.params.id}/subject`"
        @click="setActiveChip('subject')"
      >
        <v-icon left class="ma-2">mdi-book-open-variant</v-icon>
        Subjects
      </v-chip>
      <v-chip
        class="ma-2 mx-2"
        color="primary"
        text-color="white"
        :class="{ 'v-chip--active': activeChip === 'permissions' }"
        :to="`/admin/profile/basic-info/${this.$route.params.id}/change-password`"
        @click="setActiveChip('permissions')"
      >
        <v-icon left class="ma-2">mdi-lock</v-icon>
        Permissions
      </v-chip>
    </v-list>
    <v-divider></v-divider>
    <router-view></router-view>
    <personal-details />
  </v-container>
</template>
<script>
import axios from "@/axios";

export default {
  data: () => ({
    valid: false,
    calenderModal: false,
    formLoading: false,
    activeChip: null,
    items: "",
    role: "",
    tab: null,
    loading: false,
    showErrorSnackbar: false,
    errorMessage: "",
    requiredRules: [(v) => !!v || "Please fill out this field!"],
    // numberRules: [
    //   (v) => !!v || "Please fill out this field!",
    //   (v) => Number.isInteger(Number(v)) || "Please enter numbers only!",
    // ],
    emailRules: [
      (v) => !!v || "Please fill out the field!",
      (v) =>
        !v ||
        /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) ||
        "E-mail must be valid",
    ],
  }),
  created() {
    this.fetchData();
  },
  methods: {
    async fetchData() {
      try {
        const response = await axios.get(`/users/${this.$route.params.id}`);
        this.role = response.data.role_name;
        this.items = response.data.profile;
        console.log(this.items);
        
      } catch (error) {
        console.error("Error fetching data:", error);
        this.showErrorSnackbar = true;
        this.errorMessage = "Error fetching data.";
      }
    },

    setActiveChip(chip) {
      this.activeChip = chip;
    },
  },
};
</script>
<style>
.v-chip--active {
  background-color: #6ca9eb !important; /* Change this to your desired active color */
  color: white !important;
}
</style>
