<template>
  <div>
    <h2 class="text-center">Welcome to home page</h2>
    <p class="text-center">User Created: {{ user }} <br />just testing</p>
  </div>
</template>

<script>
import { computed } from "vue";
import { useStore } from "vuex";

export default {
  setup() {
    const store = useStore();

    const user = computed(() => store.state.user.user);
    return { user };
  },
};
</script>
