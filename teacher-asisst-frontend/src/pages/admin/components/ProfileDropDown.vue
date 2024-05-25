<template>
  <div class="text-center">
    <v-menu>
      <template v-slot:activator="{ props: menu }">
        <v-tooltip location="top">
          <template v-slot:activator="{ props: tooltip }">
            <v-list-item
              v-bind="mergeProps(menu, tooltip)"
              prepend-avatar="https://cdn.vuetifyjs.com/images/john.png"
              subtitle="john@google.com"
              title="John Leider"
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
            <v-list-item-title :to="`/admin/profile/baseinfo/${this.$route.params.id}`">{{ item.text }}</v-list-item-title>
          </v-list-item>
        </v-list>
      </v-card>
    </v-menu>
  </div>
</template>
<script>
import { mergeProps } from "vue";

export default {
  data: () => ({
    items: [
      { text: "View profile", icon: "mdi-account-circle" },
      { text: "Account", icon: "mdi-account" },
      { text: "Settings", icon: "mdi-clock" },
      { text: "Log Out", icon: "mdi-logout" },
    ],
  }),
  methods: {
    mergeProps,
  },
};
</script>
