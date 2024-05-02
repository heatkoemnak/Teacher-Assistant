<template lang="">
  <div>
    <h1 class="text-center mt-5">{{ title }}</h1>
  </div>
</template>
<script>
import { ref } from "vue";
export default {
  setup() {
    const title = ref("Dashboard Content");
    return { title };
  },
};
</script>
<style lang=""></style>
