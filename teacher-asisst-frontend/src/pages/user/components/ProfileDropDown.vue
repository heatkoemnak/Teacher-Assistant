<template>
  <div class="text-center">
    <v-menu>
      <template v-slot:activator="{ props: menu }">
        <v-tooltip location="top">
          <template v-slot:activator="{ props: tooltip }">
            <v-list-item
              v-bind="mergeProps(menu, tooltip)"
              :title="user.name"
              rounded="xl"
            >
              <template v-slot:append>
                <v-btn icon="mdi-menu-down" size="small" variant="text"></v-btn>
              </template>
            </v-list-item>
          </template>
        </v-tooltip>
      </template>
      <v-card class="mx-auto" width="200">
        <v-list density="compact">
          <v-list-subheader>REPORTS</v-list-subheader>
          <v-list-item
            v-for="(item, i) in items"
            :key="i"
            :value="item"
            color="primary"
          >
            <template v-slot:prepend>
              <v-icon :icon="item.icon"></v-icon>
            </template>
            <v-list-item-title>{{ item.text }}</v-list-item-title>
          </v-list-item>
          <v-list-item>
            <v-btn @click="handleLogout" variant="text" block> Logout</v-btn>
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
    items: [
      {
        text: "View profile",
        icon: "mdi-account-circle",
        route: "personal-details",
      },
      { text: "Account", icon: "mdi-account", route: "account" },
    ],
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
        this.logout();
        this.$router.push("/login"); // Adjust as needed
      } catch (error) {
        console.error("Logout failed", error);
      }
    },
  },
};
</script>
