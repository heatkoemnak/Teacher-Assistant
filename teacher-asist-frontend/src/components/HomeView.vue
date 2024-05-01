<template>
  <div>
    <p>User Created: {{ user }} just testing</p>
  </div>
</template>

<script>
import { computed } from "vue";
import { useStore } from "vuex";

export default {
  setup() {
    const store = useStore();

    const user = computed(() => store.state.user);
    return { user };
  },
};
</script>
