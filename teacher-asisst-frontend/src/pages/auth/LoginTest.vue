<template>
  <div>
    <input v-model="credentials.email" type="email" placeholder="email" />
    <input v-model="credentials.password" type="password" placeholder="Password" />
    <button @click="handleLogin">Login</button>
  </div>
</template>

<script>
import { mapActions } from "vuex";

export default {
  data() {
    return {
      credentials: {
        email: '',
        password: '',
      },
    };
  },
  methods: {
    ...mapActions(["login"]),
    async handleLogin() {
      try {
        await this.login(this.credentials);
      } catch (error) {
        console.error("Login failed", error);
      }
    },
  },
};
</script>
