<template lang="">
  <v-app>
    <v-navigation-drawer
      v-model="drawer"
      app
      :mini-variant="mini"
      :clipped="clipped"
      enable-resize-watcher
      fixed
    >
      <v-list dense>
        <v-list-item-group v-model="group" color="primary">
          <v-list-item v-for="(item, i) in items" :key="i" :to="item.name">
            <v-list-item-action>
              <v-icon>{{ item.icon }}</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title>{{ item.text }}</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list-item-group>
      </v-list>
    </v-navigation-drawer>

    <v-app-bar color="primary">
      <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
      <v-toolbar-title>Dashboard</v-toolbar-title>
    </v-app-bar>

    <v-content app class="content">
      <v-container fluid>
        <router-view></router-view>
      </v-container>
    </v-content>
  </v-app>
</template>
<script>
import { ref } from "vue";

export default {
  setup() {
    const drawer = ref(true);
    const clipped = ref(true);
    const mini = ref(true);
    const group = ref(null);
    const current = ref(null);
    const items = [
      {
        icon: "mdi-view-dashboard",
        text: "Dashboard",
        name: "dashboard",
      },
      { icon: "mdi-account", text: "Profile", name: "profile" },
      { icon: "mdi-settings", text: "Settings", name: "setting" },
    ];

    return {
      drawer,
      clipped,
      mini,
      group,
      current,
      items,
    };
  },
};
</script>

<style scoped>
.content {
  margin-top: 64px;
  background: #746565;
}
</style>
