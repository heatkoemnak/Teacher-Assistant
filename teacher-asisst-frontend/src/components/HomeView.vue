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

<style scoped>
.text-center {
  text-align: center;
}

h2 {
  color: #4caf50; 
  font-size: 24px;
}

p {
  color: #555;
  font-size: 18px;
}

div {
  margin-top: 50px;
}
</style>
