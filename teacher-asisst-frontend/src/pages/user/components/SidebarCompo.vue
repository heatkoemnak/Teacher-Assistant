<template>
  <v-navigation-drawer
    v-model="drawer"
    :rail="rail"
    permanent
    color="white"
    @click="rail = false"
    class="overflow"
  >
    <div>
      <v-list-item class="px-2">
        <v-list-item-content>
          <h1>ITE-YS2-M6</h1>
        </v-list-item-content>
      </v-list-item>
    </div>

    <v-divider></v-divider>
    <v-list density="compact" nav>
      <v-list-item
        v-for="item in items"
        :key="item.title"
        :prepend-icon="item.icon"
        :title="item.title"
        :to="item.route"
      ></v-list-item>
    </v-list>
  </v-navigation-drawer>
</template>

<script>
import { ref } from "vue";

export default {
  name: "SidebarCompo",
  props: {
    items: {
      type: Array,
      required: true,
    },
    pathID: {
      type: String,
      required: true,
    },
  },
  setup() {
    const drawer = ref(true);
    const rail = ref(false);

    return { drawer, rail };
  },
};
</script>

<style scoped>
.overflow {
  overflow: hidden;
}
</style>
