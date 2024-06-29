<template>
  <div class="text-center ">
    <v-menu v-model="menu" :close-on-content-click="false" transition="slide-y-transition">
      <template v-slot:activator="{ props }">
        <v-list-item v-bind="props" :title="user.name" rounded="xl">
          <template v-slot:append>
            <v-btn icon="mdi-menu-down" size="small" variant="text"></v-btn>
          </template>
        </v-list-item>
      
      </template>

      <v-card min-width="250" >
        <v-list>
          <v-list-item
            prepend-avatar="https://cdn.vuetifyjs.com/images/john.jpg"
            :subtitle="user.email"
            :title="user.name"
          >
          </v-list-item>
        </v-list>

        <v-divider></v-divider>

        <v-list>
          <v-list-item
            v-for="(item, index) in items"
            :key="index"
            :to="`/admin/profile/basic-info/${user.id}/${item.link}`"
          >
            <v-list-item-title>
              <v-icon>{{ item.icon }}</v-icon> {{ item.title }}
            </v-list-item-title>
          </v-list-item>

          <v-list-item @click="logout">
            <v-list-item-title>
              <v-icon @click="handleLogout">mdi-logout</v-icon> Logout
            </v-list-item-title>
          </v-list-item>
        </v-list>
      </v-card>
    </v-menu>
  </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import { mergeProps } from "vue";
export default {
  data: () => ({
    fav: true,
    menu: false,
    items: [
      {
        title: "View profile",
        icon: "mdi-account-circle",
        link: "personal-details",
      },
      {
        title: "Change password",
        icon: "mdi-shield-refresh-outline",
        link: "https://google.com",
      },
      {
        title: "Change theme",
        icon: "mdi-palette-outline",
        link: "https://google.com",
      },
    ],
    username: "Username",
    userEmail: "user@example.com",
  }),
  computed: {
    ...mapGetters(["isAuthenticated"]),
    user() {
      return this.$store.state.user;
    },
  },
  created() {
    if (!this.isAuthenticated) {
      this.$router.push("/login"); // Adjust as needed
    }
  },
  methods: {
    mergeProps,

    ...mapActions(["logout"]),
    async handleLogout() {
      try {
        await this.logout();
        this.$router.push("/login"); // Adjust as needed
      } catch (error) {
        console.error("Logout failed", error);
      }
    },
  },
  
};
</script>
