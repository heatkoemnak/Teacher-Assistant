<template>
  <div>
    <h1>Dashboard</h1>
    <p v-if="user">Welcome, {{ user.name }}!</p>
  </div>
</template>

<script>
import axios from "@/axios";

export default {
  data() {
    return {
      user: "",
    };
  },
  created() {
    this.fetchUserDetails();
  },
  methods: {
    async fetchUserDetails() {
      try {
        const response = await axios.get("/auth/user");
        this.user = response.data;
        console.log(this.user);
      } catch (error) {
        console.error("Failed to fetch user details:", error);
      }
    },
  },
};
</script>
