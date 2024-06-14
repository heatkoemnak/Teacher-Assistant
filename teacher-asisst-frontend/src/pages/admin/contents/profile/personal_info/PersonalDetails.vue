<template>
  <v-container>
    <v-form ref="form" v-model="valid" lazy-validation>
      <v-row>
        <v-col cols="12" class="d-flex">
          <v-card-title class="headline">Personal Details</v-card-title>
          <v-spacer></v-spacer>
          <v-btn
            color="red"
            class="text-none"
            size="small"
            to="/admin/profile/edit"
          >
            Edit
          </v-btn>
        </v-col>
        <v-col cols="12" md="6" class="mb-0">
          <div class="headline">First Name</div>
          <div class="headline">{{ formattedItems.first_name }}</div>
        </v-col>
        <v-col cols="12" md="6" class="mb-0">
          <div class="headline">Last Name</div>
          <div class="headline">{{ formattedItems.last_name }}</div>
        </v-col>
        <v-col cols="12" md="6" class="mb-0">
          <div class="headline">CODE</div>
          <div class="headline">{{ formattedItems.id }}</div>
        </v-col>
        <v-col cols="12" md="6" class="mb-0">
          <div class="headline">Date of Birth</div>
          <div class="headline">{{ formattedItems.dob }}</div>
        </v-col>
        <v-col cols="12" md="6" class="mb-0">
          <div class="headline">Phone</div>
          <div class="headline">{{ formattedItems.phone }}</div>
        </v-col>
        <v-col cols="12" md="6" class="mb-0">
          <div class="headline">Gender</div>
          <div class="headline">{{ formattedItems.gender }}</div>
        </v-col>
      </v-row>
    </v-form>
  </v-container>
</template>

<script>
import axios from "@/axios";

export default {
  data: () => ({
    valid: false,
    calenderModal: false,
    formLoading: false,
    items: {},
    tab: null,
    loading: false,
    showErrorSnackbar: false,
    errorMessage: "",
    requiredRules: [(v) => !!v || "Please fill out this field!"],
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
  computed: {
    formattedItems() {
      return {
        ...this.items,
        first_name: this.items.first_name || "N/A",
        last_name: this.items.last_name || "N/A",
        id: this.items.id || "N/A",
        dob: this.items.dob || "N/A",
        phone: this.items.phone || "N/A",
        gender: this.items.gender || "N/A",
      };
    },
  },
  methods: {
    async fetchData() {
      try {
        const response = await axios.get(`/users/${this.$route.params.id}`);
        this.items = response.data.profile;
        console.log(this.items);
      } catch (error) {
        console.error("Error fetching data:", error);
        this.showErrorSnackbar = true;
        this.errorMessage = "Error fetching data.";
      }
    },
  },
};
</script>

<style scoped>

</style>
